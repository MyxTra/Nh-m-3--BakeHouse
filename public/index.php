<?php

declare(strict_types=1);

use App\Core\ErrorHandler;
use App\Core\Request;
use App\Core\Router;
use App\Core\Session;
use App\Middleware\CsrfMiddleware;

define('BASE_PATH', dirname(__DIR__));

if (is_file(BASE_PATH . '/vendor/autoload.php')) {
    require BASE_PATH . '/vendor/autoload.php';
} else {
    spl_autoload_register(static function (string $class): void {
        if (!str_starts_with($class, 'App\\')) {
            return;
        }
        $file = BASE_PATH . '/src/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (is_file(BASE_PATH . '/src/' . str_replace('\\', '/', substr($class, 4)) . '.php')) {
            require $file;
        }
    });
}
require_once BASE_PATH . '/src/Helpers/functions.php';

// Tự động xử lý loại bỏ thư mục con trên XAMPP khỏi REQUEST_URI để không bị lỗi 404
if (isset($_SERVER['SCRIPT_NAME'], $_SERVER['REQUEST_URI'])) {
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']); // Ra được /Back-House/public
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
    
    if ($scriptDir !== '/' && str_starts_with($requestUri, $scriptDir)) {
        $trimmedUri = substr($requestUri, strlen($scriptDir));
        $_SERVER['REQUEST_URI'] = $trimmedUri === '' ? '/' : $trimmedUri;
    }
}

ErrorHandler::register();
$request = Request::capture();

try {
    Session::start();

    $router = new Router();
    $router->group('', [CsrfMiddleware::class], static function (Router $router): void {
        require BASE_PATH . '/routes/web.php';
        $router->group('/admin', [], static function (Router $router): void {
            require BASE_PATH . '/routes/admin.php';
        });
        $router->group('/api', [], static function (Router $router): void {
            require BASE_PATH . '/routes/api.php';
        });
    });

    $router->dispatch($request)->send();
} catch (Throwable $e) {
    ErrorHandler::logException($e);
    ErrorHandler::render(500, $request, $e)->send();
}