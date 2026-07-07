<?php
/**
 * Router muy simple: compara método HTTP + ruta contra el arreglo
 * definido en routes.php y ejecuta el método del controlador.
 */
class Router
{
    private array $rutas;

    public function __construct(array $rutas)
    {
        $this->rutas = $rutas;
    }

    public function despachar(string $metodo, string $uri): void
    {
        // Quita query string y slash final (excepto para "/")
        $ruta = parse_url($uri, PHP_URL_PATH);
        $ruta = rtrim($ruta, '/');
        if ($ruta === '') {
            $ruta = '/';
        }

        $clave = strtoupper($metodo) . ' ' . $ruta;

        if (!isset($this->rutas[$clave])) {
            http_response_code(404);
            echo '404 - Ruta no encontrada: ' . htmlspecialchars($clave);
            return;
        }

        [$controlador, $accion] = $this->rutas[$clave];

        require_once APP_PATH . '/Controllers/' . $controlador . '.php';
        $instancia = new $controlador();
        $instancia->$accion();
    }
}
