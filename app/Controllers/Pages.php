<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

/** Displays the public pages. Each view keeps its HTML, CSS and JavaScript together. */
class Pages extends BaseController
{
    public function home(): ResponseInterface
    {
        return $this->renderPage('home');
    }

    public function registration(): ResponseInterface
    {
        return $this->renderPage('registration');
    }

    public function login(): ResponseInterface
    {
        return $this->renderPage('login');
    }

    public function account(): ResponseInterface
    {
        helper('url');
        try {
            $user = (new \App\Libraries\LocalAuth())->currentUser();
            if ($user === null) {
                return redirect()->to(site_url('login'))->setHeader('Cache-Control', 'no-store');
            }
            return $this->renderPage('account', 200, ['user' => $user]);
        } catch (\Throwable $exception) {
            return $this->renderPage('account_unavailable', 503);
        }
    }

    public function notFound(): ResponseInterface
    {
        return $this->renderPage('not_found', 404);
    }

    private function renderPage(string $viewName, int $statusCode = 200, array $data = []): ResponseInterface
    {
        helper('url');
        $html = view($viewName, $data);

        return $this->response
            ->setStatusCode($statusCode)
            ->setHeader('Content-Security-Policy', $this->buildContentPolicy($html))
            ->setHeader('Cache-Control', 'no-store')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->setHeader('Permissions-Policy', 'geolocation=(self), camera=(), microphone=()')
            ->setBody($html);
    }

    private function buildContentPolicy(string $html): string
    {
        // Only the inline scripts/styles included in our rendered view are approved.
        // This keeps CSS in the page without enabling arbitrary inline scripts.
        $scriptHashes = $this->getInlineHashes($html, 'script');
        $styleHashes = $this->getInlineHashes($html, 'style');

        $rules = [
            "default-src 'self'",
            "script-src 'self' {$scriptHashes} https://www.googletagmanager.com",
            "style-src 'self' {$styleHashes}",
            "img-src 'self' data: https://*.google-analytics.com",
            "connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://www.googletagmanager.com",
            "font-src 'self'",
            "object-src 'none'",
            "frame-src 'none'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        return implode('; ', $rules);
    }

    private function getInlineHashes(string $html, string $tagName): string
    {
        preg_match_all('~<' . $tagName . '>(.*?)</' . $tagName . '>~s', $html, $matches);
        $hashes = [];

        foreach ($matches[1] as $content) {
            $hash = base64_encode(hash('sha256', $content, true));
            $hashes[] = "'sha256-{$hash}'";
        }

        return implode(' ', $hashes);
    }
}
