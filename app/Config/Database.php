<?php
/**
 * Conexión a la base de datos MySQL/MariaDB (mysqli).
 * Ajusta estas 4 constantes con los datos de tu servidor.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'estacionamiento');
define('DB_USER', 'root');       // <-- cambia por tu usuario real
define('DB_PASS', '');           // <-- cambia por tu contraseña real

function getConexion(): mysqli {
    static $conn = null;

    if ($conn === null) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            die('Error de conexión a la base de datos: ' . mysqli_connect_error());
        }
        mysqli_set_charset($conn, 'utf8mb4');
    }

    return $conn;
}
