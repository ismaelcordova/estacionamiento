<?php
/**
 * Recibe los datos del formulario "Agregar trabajador" y los guarda
 * en la base de datos. Responde en JSON.
 */
require_once __DIR__ . '/Trabajadores.php';

header('Content-Type: application/json; charset=utf-8');

$nombre = trim($_POST['nombre'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$marca  = trim($_POST['marca']  ?? '');
$placa  = trim($_POST['placa']  ?? '');
$tipo   = trim($_POST['tipo']   ?? '');

if ($nombre === '' || $numero === '' || $marca === '' || $placa === '' || $tipo === '') {
    echo json_encode(['ok' => false, 'error' => 'Completa todos los campos']);
    exit;
}

if (!in_array($tipo, ['Administrativo', 'Docente'], true)) {
    echo json_encode(['ok' => false, 'error' => 'Tipo de personal inválido']);
    exit;
}

if (!ctype_digit($numero)) {
    echo json_encode(['ok' => false, 'error' => 'El número de plaza solo acepta números']);
    exit;
}

$nuevo = agregarTrabajador($nombre, $numero, $marca, $placa, $tipo);

echo json_encode(
    $nuevo
        ? ['ok' => true, 'trabajador' => $nuevo]
        : ['ok' => false, 'error' => 'No se pudo guardar el trabajador']
);