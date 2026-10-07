<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

/** Shared response and CSRF checks for local JSON forms. */
abstract class FormApiController extends BaseController
{
    protected const MAX_REQUEST_BYTES = 12000;

    protected function reply(int $status, array $body): ResponseInterface
    {
        return $this->response
            ->setStatusCode($status)
            ->setHeader('Cache-Control', 'no-store')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setJSON($body);
    }

    protected function getAllowedOrigin(): string
    {
        $parts = parse_url(config('App')->baseURL);
        $scheme = $parts['scheme'] ?? '';
        $host = $parts['host'] ?? '';
        if ($scheme !== 'https' && !in_array($host, ['localhost', '127.0.0.1', '[::1]'], true)) {
            throw new \RuntimeException('Public hosting requires HTTPS.');
        }
        config('Cookie')->secure = $scheme === 'https';
        config('Cookie')->httponly = true;
        config('Cookie')->samesite = 'Lax';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        return $scheme . '://' . $host . $port;
    }

    protected function checkFormRequest(): ?ResponseInterface
    {
        // Check origin as well as a session-bound token; never trust a form role.
        if ($this->request->getHeaderLine('Origin') !== $this->getAllowedOrigin()) {
            return $this->reply(403, ['error' => 'Origin not allowed.']);
        }
        // The browser must send the token issued to this PHP session.
        $expectedToken = session()->get('registration_csrf');
        $submittedToken = $this->request->getHeaderLine('X-CSRF-Token');
        if (!is_string($expectedToken) || $expectedToken === '' || !hash_equals($expectedToken, $submittedToken)) {
            return $this->reply(403, ['error' => 'Reload the page and try again.']);
        }
        if (!str_starts_with(strtolower($this->request->getHeaderLine('Content-Type')), 'application/json')) {
            return $this->reply(415, ['error' => 'JSON request required.']);
        }
        if (strlen($this->request->getBody()) > self::MAX_REQUEST_BYTES) {
            return $this->reply(413, ['error' => 'Request too large.']);
        }
        // Reject invalid JSON before processing the form.
        try {
            $data = json_decode($this->request->getBody(), false, 32, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->reply(400, ['error' => 'Invalid request.']);
        }
        if (!$data instanceof \stdClass) {
            return $this->reply(400, ['error' => 'Invalid form.']);
        }
        return null;
    }
}
