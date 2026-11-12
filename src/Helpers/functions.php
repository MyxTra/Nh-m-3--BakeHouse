<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Env;
use App\Core\Session;

// Tự động nạp file .env khi chạy trên XAMPP / môi trường không dùng Docker
(static function (): void {
    $envFile = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)) . '/.env';
    if (!is_file($envFile)) {
        return;
    }
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if ((str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }
        if ($name !== '' && !isset($_ENV[$name])) {
            $_ENV[$name] = $value;
            putenv("{$name}={$value}");
        }
    }
})();

if (!function_exists('base_url_prefix')) {
    /** Trả về tiền tố thư mục trên XAMPP (vd: "/BakeHouse-main/public") hoặc "" nếu chạy ở root */
    function base_url_prefix(): string
    {
        $appUrl = (string) Env::get('APP_URL', '');
        if ($appUrl !== '') {
            $parsedPath = parse_url($appUrl, PHP_URL_PATH);
            if (is_string($parsedPath) && $parsedPath !== '' && $parsedPath !== '/') {
                return rtrim($parsedPath, '/');
            }
        }

        $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        return ($scriptDir === '' || $scriptDir === '/') ? '' : $scriptDir;
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $prefix = base_url_prefix();
        $cleanPath = '/' . ltrim($path, '/');

        if ($prefix !== '' && (str_starts_with($cleanPath, $prefix . '/') || $cleanPath === $prefix)) {
            return $cleanPath;
        }

        return $prefix . ($cleanPath === '/' && $prefix !== '' ? '' : $cleanPath);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        if (preg_match('#^https?://#i', $path)) {
            return $path; // absolute URL (e.g. demo images hosted externally)
        }

        return base_url_prefix() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        static $old = null;
        $old ??= Session::pullOld();

        return e($old[$key] ?? $default);
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('format_vnd')) {
    function format_vnd(int|float $amount): string
    {
        return number_format((float) $amount, 0, ',', '.') . ' ₫';
    }
}

if (!function_exists('lang')) {
    /** Look up a translation by dot key from src/Lang/vi.php. */
    function lang(string $key, string $default = ''): string
    {
        static $messages = null;
        $messages ??= require BASE_PATH . '/src/Lang/vi.php';

        $value = $messages;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default !== '' ? $default : $key;
            }
            $value = $value[$segment];
        }

        return is_string($value) ? $value : $default;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = str_replace(['đ', 'Đ'], 'd', $text);
        if (class_exists(Transliterator::class)) {
            $text = Transliterator::create('Any-Latin; Latin-ASCII; Lower()')?->transliterate($text) ?? mb_strtolower($text);
        }
        $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-');

        return $slug !== '' ? $slug : 'item';
    }
}

if (!function_exists('image_url')) {
    function image_url(?string $path, string $fallback = 'assets/img/placeholder.svg'): string
    {
        return asset($path !== null && $path !== '' ? $path : $fallback);
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        return \App\Core\Auth::user();
    }
}

if (!function_exists('field_error')) {
    function field_error(string $field): string
    {
        static $errors = null;
        $errors ??= \App\Core\Session::pullErrors();

        return isset($errors[$field]) ? '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>' : '';
    }
}

if (!function_exists('is_path')) {
    function is_path(string $prefix): bool
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        return $prefix === '/' ? $path === '/' : str_starts_with($path, $prefix);
    }
}

if (!function_exists('config_value')) {
    function config_value(string $key, ?string $default = null): ?string
    {
        return \App\Core\Env::get($key, $default);
    }
}

if (!function_exists('img_url')) {
    /** Public URL for an uploaded/static image path, with a placeholder fallback. */
    function img_url(?string $path, string $fallback = 'assets/img/product-placeholder.svg'): string
    {
        return asset(($path === null || $path === '') ? $fallback : $path);
    }
}

if (!function_exists('form_error')) {
    function form_error(string $field): string
    {
        static $errors = null;
        $errors ??= \App\Core\Session::pullErrors();

        return isset($errors[$field])
            ? '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>'
            : '';
    }
}

if (!function_exists('query_url')) {
    /** Build a URL for the current path, merging/removing query parameters. */
    function query_url(array $merge = [], array $remove = []): string
    {
        $params = array_merge($_GET, $merge);
        foreach ($remove as $key) {
            unset($params[$key]);
        }
        $params = array_filter($params, static fn ($v): bool => $v !== '' && $v !== null);
        $path = url(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

        return $path . ($params === [] ? '' : '?' . http_build_query($params));
    }
}

if (!function_exists('current_path')) {
    function current_path(): string
    {
        return '/' . trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    }
}

if (!function_exists('nav_active')) {
    function nav_active(string $prefix): string
    {
        $path = current_path();
        $active = $prefix === '/' ? $path === '/' : str_starts_with($path, $prefix);

        return $active ? ' bh-nav__link--active' : '';
    }
}

if (!function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        $enum = \App\Enums\OrderStatus::from($status);

        return '<span class="badge ' . $enum->badgeClass() . '">' . e($enum->label()) . '</span>';
    }
}

if (!function_exists('cart_count')) {
    function cart_count(): int
    {
        $id = \App\Core\Auth::id();
        if ($id === null) {
            return 0;
        }
        try {
            return (new \App\Repositories\CartRepository())->countItems($id);
        } catch (\Throwable) {
            return 0;
        }
    }
}

if (!function_exists('partial')) {
    /** Include a view from src/Views/partials with the given variables. */
    function partial(string $name, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require BASE_PATH . '/src/Views/partials/' . $name . '.php';
    }
}

if (!function_exists('render_stars')) {
    function render_stars(float $rating): string
    {
        $html = '<span class="bh-stars" aria-label="' . e(number_format($rating, 1)) . ' trên 5 sao">';
        for ($i = 1; $i <= 5; $i++) {
            $icon = $rating >= $i ? 'bi-star-fill' : ($rating >= $i - 0.5 ? 'bi-star-half' : 'bi-star');
            $html .= '<i class="bi ' . $icon . '"></i>';
        }

        return $html . '</span>';
    }
}

if (!function_exists('method_field')) {
    function method_field(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . e($method) . '">';
    }
}