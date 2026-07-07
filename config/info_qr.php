<?php
//info del qr - fusión texto + link verificar
function construirPayloadQr(array $trabajador): string {
    $lineas = [
        'PASE DE ESTACIONAMIENTO',
        ' '   . ($trabajador['tipo']   ?? ''),
        '',
        'Nombre: ' . ($trabajador['nombre'] ?? ''),
        'Plaza: '  . ($trabajador['numero'] ?? ''),
        'Marca: '  . ($trabajador['marca']  ?? ''),
        'Placa: '  . ($trabajador['placa']  ?? ''),
        '',
        'Verificar en: ' . str_replace('https://', '', BASE_URL) . '/verificar.php'
            . '?numero=' . urlencode($trabajador['numero'] ?? '')
            . '&tipo='   . urlencode($trabajador['tipo']   ?? ''),
    ];
    return implode("\n", $lineas);
}

$trabajadores = [];
if (isset($_GET['datos'])) {
    $trabajadores = json_decode($_GET['datos'], true);
}
?>