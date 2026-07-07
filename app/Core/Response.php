<?php
/**
 * Helper para respuestas JSON desde los controladores.
 */
class Response
{
    public static function json(array $datos, int $codigoHttp = 200): void
    {
        http_response_code($codigoHttp);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos);
        exit;
    }
}
