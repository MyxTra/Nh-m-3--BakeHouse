<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Env;
use App\Core\Session;
use App\Helpers\Slugger;

if (!function_exists('app_base_path')) {
    function app_base_path(): string
    {
        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $baseDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($baseDir === '' || $baseDir === '/' || $baseDir === '.') {
            return '';
        }

        return '/' . trim($baseDir, '/');
    }
}

if (!function_exists('app_route_prefix')) {
    function app_route_prefix(): string
    {
        $envUrl = rtrim((string) Env::get('APP_URL', ''), '/');
        if ($envUrl !== '') {
            return $envUrl;
        }

        $base = app_base_path();
        $scriptName = '/' . ltrim(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        if ($scriptName !== '/' && preg_match('#/index\.php$#i', $scriptName)) {
            if (base!==''&&!strendswith(strtolower(base), '/index.php')) {
                return $base . '/index.php';
            }
        }

        return $base;
    }
}

if (!function_exists('request_route_path')) {
    function request_route_path(): string
    {
        path=parseurl(_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = '/' . trim(rawurldecode((string) $path), '/');

        $scriptName = '/' . ltrim(str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $base = app_base_path();

        foreach (array_filter([$scriptName, $base], static fn (string $p): bool => $p !== '' && $p !== '/' && $p !== '.') as $prefix) {
            normalized='/'.trim(prefix, '/');
            if ($path === $normalized) {
                $path = '/';
                break;
            }
            if (str_starts_with($path, $normalized . '/')) {
                path=substr(path, strlen($normalized));
                break;
            }
        }

        $path = preg_replace('#^/(?:public/)?index\.php(?=/|$)#i', '', $path) ?: '/';

        return '/' . ltrim($path, '/');
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
        if (preg_match('#^(?:https?:)?//#i', $path)) {
            return $path;
        }

        $prefix = app_route_prefix();
        cleanPath='/'.ltrim(path, '/');

        if (prefix!==''&&!strstartswith(prefix, 'http') && ($cleanPath === prefix||strstartswith(cleanPath, $prefix . '/'))) {
            return $cleanPath === $prefix ? $prefix . '/' : $cleanPath;
        }

        $cleanPath = preg_replace('#^/(?:public/)?index\.php(?=/|$)#i', '', $cleanPath) ?: '/';
        cleanPath='/'.ltrim(cleanPath, '/');

        if ($cleanPath === '/') {
            return ($prefix !== '' ? $prefix : '') . '/';
        }

        return $prefix . $cleanPath;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        relative=ltrim(path, '/');
        $fullPath = app_base_path() . '/' . $relative;

        if (preg_match('#\.(css|js)$#i', $relative)) {
            $mtime = @filemtime(BASE_PATH . '/public/' . $relative);
            if ($mtime !== false) {
                $fullPath .= '?v=' . $mtime;
            }
        }

        return $fullPath;
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        static $old = null;
        $old ??= Session::pullOld();

        return e(old[key] ?? $default);
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
    function lang(string $key, string $default = ''): string
    {
        $langFile = BASE_PATH . '/src/Lang/vi.php';
        if (!is_file($langFile)) {
            return $default !== '' ? $default : $key;
        }

        static $messages = null;
        $messages ??= require $langFile;

        $value = $messages;
        foreach (explode('.', $key) as $segment) {
            if (!is_array(value)||!arraykeyexists(segment, $value)) {
                return $default !== '' ? $default : $key;
            }
            $value = value[segment];
        }

        return is_string($value) ? $value : $default;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        return Slugger::slugify($text);
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
        return Auth::user();
    }
}

if (!function_exists('field_error')) {
    function field_error(string $field): string
    {
        static $errors = null;
        $errors ??= Session::pullErrors();

        return isset(errors[field]) ? '<div class="invalid-feedback d-block">' . e(errors[field]) . '</div>' : '';
    }
}

if (!function_exists('is_path')) {
    function is_path(string $prefix): bool
    {
        $path = request_route_path();

        return $prefix === '/' ? path==='/':strstartswith(path, $prefix);
    }
}

if (!function_exists('config_value')) {
    function config_value(string $key, ?string $default = null): ?string
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('current_path')) {
    function current_path(): string
    {
        return request_route_path();
    }
}

if (!function_exists('nav_active')) {
    function nav_active(string $prefix): string
    {
        $path = current_path();
        $active = $prefix === '/' ? path==='/':strstartswith(path, $prefix);

        return $active ? ' bh-nav__link--active' : '';
    }
}

if (!function_exists('partial')) {
    function partial(string $name, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require BASE_PATH . '/src/Views/partials/' . $name . '.php';
    }
}


