<?php

namespace App\Controllers;

use App\Libraries\LocalAuth;
use App\Libraries\RequestLimiter;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthApi extends FormApiController
{
    public function session(): ResponseInterface
    {
        $this->getAllowedOrigin();
        try {
            $auth = new LocalAuth();
            $user = $auth->currentUser();
            $session = $auth->session();
            $token = $session->get('registration_csrf');
            if (!is_string($token) || strlen($token) !== 64) {
                $token = bin2hex(random_bytes(32));
                $session->set('registration_csrf', $token);
            }
            return $this->reply(200, ['csrf' => $token, 'user' => $user]);
        } catch (\Throwable $exception) {
            // Record only the session exception, never request bodies or passwords.
            $detail = str_replace(["\r", "\n"], ' ', $exception->getMessage());
            $message = 'Local account session failed: ' . get_class($exception)
                . ': ' . $detail . ' at ' . basename($exception->getFile())
                . ':' . $exception->getLine();
            error_log($message); // Also visible in the local PHP server window.
            try {
                log_message('error', $message);
            } catch (\Throwable $loggingError) {
                // The server window still receives the diagnostic if log storage fails.
            }
            return $this->reply(503, ['error' => 'The local account service is unavailable. Please try again.']);
        }
    }

    public function login(): ResponseInterface
    {
        $error = $this->checkFormRequest();
        if ($error !== null) {
            return $error;
        }
        $data = $this->request->getJSON(true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        if (!is_string($username) || !is_string($password) ||
            !preg_match('/\A[a-zA-Z0-9_.-]{3,80}\z/', trim($username)) ||
            $password === '' || strlen($password) > 72 || str_contains($password, "\0")) {
            return $this->reply(422, ['error' => 'Enter your username and password.']);
        }
        $username = strtolower(trim($username));

        try {
            $limiter = new RequestLimiter();
            $ipAllowed = $limiter->allow('login-ip:' . $this->request->getIPAddress(), 30, 900);
            $userAllowed = $limiter->allow('login-user:' . $username, 10, 900);
            if (!$ipAllowed || !$userAllowed) {
                return $this->reply(429, ['error' => 'Too many sign-in attempts. Please try again in 15 minutes.']);
            }

            $user = (new UserModel())->where('username', $username)->first();
            // A fixed dummy hash keeps password checking work similar for unknown users.
            $hash = $user['password_hash'] ?? '$2y$12$6nmcWDGlV.VQ4G6V3To8AuawPs4XA.xVqwwa85OQ8rcQmJJz7P3xa';
            $passwordMatches = password_verify($password, $hash);
            if (!$user || !$passwordMatches || (int) $user['approved'] !== 1) {
                return $this->reply(401, ['error' => 'Unable to sign in. Check your credentials and ask IT to confirm account approval.']);
            }

            (new LocalAuth())->login($user);
            return $this->reply(200, ['message' => 'Signed in successfully.']);
        } catch (\Throwable $exception) {
            return $this->reply(503, ['error' => 'Sign-in is unavailable. Please ask the administrator to check the local database.']);
        }
    }

    public function logout(): ResponseInterface
    {
        $error = $this->checkFormRequest();
        if ($error !== null) {
            return $error;
        }
        (new LocalAuth())->logout();

        return $this->reply(200, ['message' => 'Signed out successfully.']);
    }
}
