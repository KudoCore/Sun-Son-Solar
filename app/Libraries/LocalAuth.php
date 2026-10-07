<?php

namespace App\Libraries;

use App\Models\UserModel;
use CodeIgniter\Session\Session;

/** Authentication data lives in a server-side PHP session, not browser storage. */
class LocalAuth
{
    public function session(): Session
    {
        config('Cookie')->secure = parse_url(config('App')->baseURL, PHP_URL_SCHEME) === 'https';
        config('Cookie')->httponly = true;
        config('Cookie')->samesite = 'Lax';

        return session();
    }

    public function currentUser(): ?array
    {
        $session = $this->session();
        $userId = $session->get('user_id');
        if (!$userId) {
            return null;
        }

        $signedInAt = (int) $session->get('signed_in_at');
        if ($signedInAt === 0 || time() - $signedInAt > 7200) {
            $this->logout();
            return null;
        }

        // Recheck approval and password changes; session data cannot grant a role.
        $user = (new UserModel())->find($userId);
        $passwordVersion = (string) $session->get('password_version');
        if (!$user || (int) $user['approved'] !== 1 ||
            !hash_equals(hash('sha256', $user['password_hash']), $passwordVersion)) {
            $this->logout();
            return null;
        }

        // Only these fields may reach the account page or session endpoint.
        return array_intersect_key($user, array_flip([
            'id', 'first_name', 'last_name', 'middle_name', 'username', 'email', 'department', 'role',
            'birthday', 'gender', 'phone', 'phone_type', 'address',
        ]));
    }

    public function login(array $user): void
    {
        $session = $this->session();
        $session->regenerate(true);
        $session->set([
            'user_id' => (int) $user['id'],
            'signed_in_at' => time(),
            'password_version' => hash('sha256', $user['password_hash']),
            'registration_csrf' => bin2hex(random_bytes(32)),
        ]);
    }

    public function logout(): void
    {
        $session = $this->session();
        $session->remove(['user_id', 'signed_in_at', 'password_version']);
        $session->regenerate(true);
        $session->set('registration_csrf', bin2hex(random_bytes(32)));
    }
}
