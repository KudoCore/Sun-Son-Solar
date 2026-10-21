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
        $fallback = !str_starts_with(strtolower($this->request->getHeaderLine('Content-Type')), 'application/json');
        if (!$fallback) {
            $error = $this->checkFormRequest();
            if ($error !== null) {
                return $error;
            }
            $data = $this->request->getJSON(true);
        } else {
            if (strtoupper($this->request->getMethod()) !== 'POST' ||
                !str_starts_with(strtolower($this->request->getHeaderLine('Content-Type')), 'application/x-www-form-urlencoded')) {
                return $this->loginFailure(true, 415, 'Please reload the sign-in page and try again.');
            }
            if ($this->request->getHeaderLine('Origin') !== $this->getAllowedOrigin()) {
                return $this->loginFailure(true, 403, 'This sign-in request was not accepted. Reload the page and try again.');
            }
            if (strlen($this->request->getBody()) > self::MAX_REQUEST_BYTES) {
                return $this->loginFailure(true, 413, 'The sign-in request is too large.');
            }
            $expectedToken = session()->get('registration_csrf');
            $submittedToken = $this->request->getPost('csrf_token');
            if (!is_string($expectedToken) || !is_string($submittedToken) || !hash_equals($expectedToken, $submittedToken)) {
                return $this->loginFailure(true, 403, 'Reload the sign-in page and try again.');
            }
            $data = $this->request->getPost();
        }
        if (!is_array($data)) {
            return $this->loginFailure($fallback, 400, 'Enter your username and password.');
        }
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        if (!is_string($username) || !is_string($password) ||
            !preg_match('/\A[a-zA-Z0-9_.-]{3,80}\z/', trim($username)) ||
            $password === '' || strlen($password) > 72 || str_contains($password, "\0")) {
            return $this->loginFailure($fallback, 422, 'Enter your username and password.');
        }
        $username = strtolower(trim($username));

        try {
            $limiter = new RequestLimiter();
            $ipAllowed = $limiter->allow('login-ip:' . $this->request->getIPAddress(), 30, 900);
            $userAllowed = $limiter->allow('login-user:' . $username, 10, 900);
            if (!$ipAllowed || !$userAllowed) {
                return $this->loginFailure($fallback, 429, 'Too many sign-in attempts. Please try again in 15 minutes.');
            }

            $user = (new UserModel())->where('username', $username)->first();
            // A fixed dummy hash keeps password checking work similar for unknown users.
            $hash = $user['password_hash'] ?? '$2y$12$6nmcWDGlV.VQ4G6V3To8AuawPs4XA.xVqwwa85OQ8rcQmJJz7P3xa';
            $passwordMatches = password_verify($password, $hash);
            if (!$user || !$passwordMatches || (int) $user['approved'] !== 1 || (int) ($user['is_active'] ?? 1) !== 1) {
                return $this->loginFailure($fallback, 401, 'Unable to sign in. Check your credentials and ask IT to confirm account approval.');
            }

            (new LocalAuth())->login($user);
            if ($fallback) {
                $destination = $user['role'] === 'customer' ? site_url('') : site_url('portal');
                return redirect()->to($destination);
            }
            return $this->reply(200, ['message' => 'Signed in successfully.', 'role' => $user['role']]);
        } catch (\Throwable $exception) {
            return $this->loginFailure($fallback, 503, 'Sign-in is unavailable. Please ask the administrator to check the local database.');
        }
    }

    private function loginFailure(bool $fallback, int $status, string $message): ResponseInterface
    {
        if ($fallback) {
            return redirect()->to(site_url('login'))->with('login_error', $message);
        }

        return $this->reply($status, ['error' => $message]);
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
