<?php
/**
 * Front Controller: único punto de entrada de toda la aplicación.
 * Todas las peticiones (gracias a .htaccess) llegan aquí.
 */

require_once __DIR__ . '/../app/Config/config.php';
require_once __DIR__ . '/../app/Core/Router.php';

$rutas  = require __DIR__ . '/../routes.php';
$router = new Router($rutas);

// Quita el prefijo de subcarpeta (ej. "/MVC/public") antes de comparar rutas
$uri = $_SERVER['REQUEST_URI'];
if (BASE_PATH !== '' && strpos($uri, BASE_PATH) === 0) {
    $uri = substr($uri, strlen(BASE_PATH));
}

$router->despachar($_SERVER['REQUEST_METHOD'], $uri);
