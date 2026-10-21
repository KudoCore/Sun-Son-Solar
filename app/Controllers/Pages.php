<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

/** Displays the public pages. Each view keeps its HTML, CSS and JavaScript together. */
class Pages extends BaseController
{
    public function home(): ResponseInterface
    {
        $testimonials = [];
        $auth = new \App\Libraries\LocalAuth();
        $user = null;
        $csrf = '';
        try {
            $db = db_connect();
            $testimonials = $db->table('customer_testimonials')->select('quote_text,display_name')->where('is_published', 1)->orderBy('created_at', 'DESC')->limit(6)->get()->getResultArray();
        } catch (\Throwable $exception) {
            // Keep public pages available before the local portal database is imported.
        }
        try {
            $user = $auth->currentUser();
            if ($user !== null) {
                $session = $auth->session();
                $csrf = $session->get('registration_csrf') ?: bin2hex(random_bytes(32));
                $session->set('registration_csrf', $csrf);
            }
        } catch (\Throwable $exception) {
            $user = null;
            $csrf = '';
        }

        return $this->renderPage('home', 200, [
            'testimonials' => $testimonials,
            'user' => $user,
            'csrf' => $csrf,
        ]);
    }

    public function profile(): ResponseInterface
    {
        return $this->customerPage('profile');
    }

    public function serviceRequest(): ResponseInterface
    {
        return $this->customerPage('service_request');
    }

    private function customerPage(string $view): ResponseInterface
    {
        helper('url');
        try {
            $auth = new \App\Libraries\LocalAuth();
            $user = $auth->currentUser();
            if ($user === null) return redirect()->to(site_url('login'));
            if (($user['role'] ?? '') !== 'customer') return redirect()->to(site_url('portal'));
            $session = $auth->session();
            $csrf = $session->get('registration_csrf');
            if (!is_string($csrf) || strlen($csrf) !== 64) {
                $csrf = bin2hex(random_bytes(32));
                $session->set('registration_csrf', $csrf);
            }
            $preferences = db_connect()->table('user_preferences')->where('user_id', $user['id'])->get()->getRowArray() ?? ['promotions' => 0, 'schedule_updates' => 1];
            $services = db_connect()->table('service_categories')->select('id,name,base_price')->where('is_active', 1)->orderBy('name')->get()->getResultArray();
            return $this->renderPage($view, 200, ['user' => $user, 'csrf' => $csrf, 'preferences' => $preferences, 'customerServices' => $services]);
        } catch (\Throwable $exception) {
            return $this->renderPage('account_unavailable', 503);
        }
    }

    public function products(): ResponseInterface
    {
        return $this->renderPage('products');
    }

    public function services(): ResponseInterface
    {
        return $this->renderPage('services');
    }

    public function projects(): ResponseInterface
    {
        return $this->renderPage('projects');
    }

    public function about(): ResponseInterface
    {
        return $this->renderPage('about');
    }

    public function contact(): ResponseInterface
    {
        return $this->renderPage('contact');
    }

    public function faq(): ResponseInterface
    {
        return $this->renderPage('faq');
    }

    public function privacy(): ResponseInterface
    {
        return $this->renderPage('privacy');
    }

    public function terms(): ResponseInterface
    {
        return $this->renderPage('terms');
    }

    public function cookies(): ResponseInterface
    {
        return $this->renderPage('cookies');
    }

    public function refunds(): ResponseInterface
    {
        return $this->renderPage('refunds');
    }

    public function thankYou(): ResponseInterface
    {
        return $this->renderPage('thank_you', 200, [
            'reference' => session()->get('enquiry_reference'),
        ]);
    }

    public function registration(): ResponseInterface
    {
        return $this->renderPage('registration');
    }

    public function login(): ResponseInterface
    {
        $session = (new \App\Libraries\LocalAuth())->session();
        $token = $session->get('registration_csrf');
        if (!is_string($token) || strlen($token) !== 64) {
            $token = bin2hex(random_bytes(32));
            $session->set('registration_csrf', $token);
        }

        return $this->renderPage('login', 200, [
            'csrf' => $token,
            'login_error' => $session->getFlashdata('login_error'),
        ]);
    }

    public function account(): ResponseInterface
    {
        $user = (new \App\Libraries\LocalAuth())->currentUser();
        if ($user === null) {
            return redirect()->to(site_url('login'));
        }
        if ($user['role'] === 'customer') {
            return redirect()->to(site_url('profile'));
        }

        return redirect()->to(site_url('portal'));
    }

    public function portal(): ResponseInterface
    {
        helper('url');
        try {
            $user = (new \App\Libraries\LocalAuth())->currentUser();
            if ($user === null) {
                return redirect()->to(site_url('login'))->setHeader('Cache-Control', 'no-store');
            }
            if ($user['role'] === 'customer') {
                return redirect()->to(site_url(''));
            }
            return $this->renderPage('portal', 200, ['user' => $user]);
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
        $styleAttributeHashes = $this->getInlineAttributeHashes($html, 'style');

        $rules = [
            "default-src 'self'",
            "script-src 'self' {$scriptHashes} https://www.googletagmanager.com",
            // CSP requires 'unsafe-hashes' before hashes can authorize style attributes.
            "style-src 'self' 'unsafe-hashes' {$styleHashes} {$styleAttributeHashes}",
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
        preg_match_all('~<' . $tagName . '\\b[^>]*>(.*?)</' . $tagName . '\\s*>~is', $html, $matches);
        $hashes = [];

        foreach ($matches[1] as $content) {
            $hash = base64_encode(hash('sha256', $content, true));
            $hashes[] = "'sha256-{$hash}'";
        }

        return implode(' ', $hashes);
    }

    private function getInlineAttributeHashes(string $html, string $attributeName): string
    {
        preg_match_all("~\\b" . preg_quote($attributeName, '~') . "\\s*=\\s*([\"'])(.*?)\\1~is", $html, $matches);
        $hashes = [];

        foreach ($matches[2] as $content) {
            $hash = base64_encode(hash('sha256', $content, true));
            $hashes[] = "'sha256-{$hash}'";
        }

        return implode(' ', array_unique($hashes));
    }
}
