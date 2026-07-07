<?php
/**
 * Configuración general de la aplicación.
 *
 * IMPORTANTE: como usamos un .htaccess en la raíz del proyecto que
 * reescribe internamente hacia public/ (para que la URL visible sea
 * http://localhost/MVC/ en vez de http://localhost/MVC/public/),
 * ya no podemos detectar la ruta base automáticamente. Debes indicarla
 * aquí manualmente, tal como se ve en la URL del navegador:
 *
 *   - Si accedes como http://localhost/MVC/       -> '/MVC'
 *   - Si el proyecto vive en la raíz del dominio   -> ''
 */
$raiz = '/estacionamiento';

define('BASE_PATH', $raiz);
define('BASE_URL', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . BASE_PATH);

// Ruta absoluta a la raíz del proyecto (útil para requires seguros)
define('APP_PATH', dirname(__DIR__));   // .../app
define('ROOT_PATH', dirname(APP_PATH)); // raíz del proyecto
