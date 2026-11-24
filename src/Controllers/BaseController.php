<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\View;

abstract class BaseController
{
    protected function render(string $view, array $data = [], string $layout = 'layouts.main'): Response
    {
        return Response::html(View::render($view, $data, $layout));
    }

    private function normalizeRedirectUrl(string $url): string
    {
        if ($url === '' || preg_match('#^(https?:)?//#i', $url) === 1) {
            return $url;
        }

        return function_exists('url') ? url($url) : $url;
    }

    protected function redirect(string $url): Response
    {
        return Response::redirect($this->normalizeRedirectUrl($url));
    }

    /** Redirect to the same-site page the user came from, or $fallback (never to an external URL). */
    protected function redirectBack(string $fallback): Response
    {
        $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
        $parts = parse_url($referer);
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if ($parts !== false && isset($parts['path']) && (!isset($parts['host']) || $parts['host'] === strtok($host, ':'))) {
            $target = $parts['path'] . (isset($parts['query']) ? '?' . $parts['query'] : '');

            return Response::redirect($this->normalizeRedirectUrl($target));
        }

        return Response::redirect($this->normalizeRedirectUrl($fallback));
    }

    protected function flash(string $type, string $message): void
    {
        Session::flash($type, $message);
    }

    /** Flash input + validation errors, then redirect. */
    protected function redirectWithErrors(Request $request, string $url, array $errors): Response
    {
        Session::flashInput($request->getAllInput(), $errors);

        return Response::redirect($this->normalizeRedirectUrl($url));
    }

    protected function json(array $data, int $status = 200): Response
    {
        return Response::json($data, $status);
    }
}