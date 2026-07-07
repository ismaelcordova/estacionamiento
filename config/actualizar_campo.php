<?php
/**
 * Recibe la edición de una sola celda de la tabla (doble clic / doble toque)
 * y la guarda en la base de datos. Responde en JSON.
 */
require_once __DIR__ . '/Trabajadores.php';

header('Content-Type: application/json; charset=utf-8');

$id    = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$campo = trim($_POST['campo'] ?? '');
$valor = trim($_POST['valor'] ?? '');

if (!$id || $campo === '' || $valor === '') {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

if (($campo === 'Numero_plaza' || $campo === 'Folio') && !ctype_digit($valor)) {
    echo json_encode(['ok' => false, 'error' => 'Este campo solo acepta números']);
    exit;
}

$guardado = actualizarCampoTrabajador($id, $campo, $valor);

echo json_encode(
    $guardado
        ? ['ok' => true]
        : ['ok' => false, 'error' => 'No se pudo guardar el cambio']
);