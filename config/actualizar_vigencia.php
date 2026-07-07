<?php
/**
 * Recibe el cambio del switch "Vigencia" de la tabla y actualiza
 * la tabla sin_vigencia (agrega o quita al trabajador). Responde en JSON.
 */
require_once __DIR__ . '/Trabajadores.php';

header('Content-Type: application/json; charset=utf-8');

$id      = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$vigente = filter_input(INPUT_POST, 'vigente', FILTER_VALIDATE_INT);

if (!$id || $vigente === null || $vigente === false) {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

$guardado = establecerVigencia($id, (bool) $vigente);

echo json_encode(
    $guardado
        ? ['ok' => true]
        : ['ok' => false, 'error' => 'No se pudo actualizar la vigencia']
);