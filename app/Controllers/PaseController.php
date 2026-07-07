<?php

class PaseController
{
    /**
     * Arma el texto que va dentro del QR (nombre, plaza, y link de verificación).
     * Antes: parte de config/info_qr.php
     */
    private function construirPayloadQr(array $trabajador): string
    {
        $lineas = [
            'PASE DE ESTACIONAMIENTO',
            ' '   . ($trabajador['tipo']   ?? ''),
            '',
            'Nombre: ' . ($trabajador['nombre'] ?? ''),
            'Plaza: '  . ($trabajador['numero'] ?? ''),
            'Marca: '  . ($trabajador['marca']  ?? ''),
            'Placa: '  . ($trabajador['placa']  ?? ''),
            '',
            'Verificar en: ' . str_replace('https://', '', BASE_URL) . '/verificar'
                . '?numero=' . urlencode($trabajador['numero'] ?? '')
                . '&tipo='   . urlencode($trabajador['tipo']   ?? ''),
        ];
        return implode("\n", $lineas);
    }

    /**
     * Recibe los trabajadores seleccionados (por GET, como antes) y
     * muestra la vista imprimible con los pases + QR.
     * Antes: view/generar_pase.php
     */
    public function generar(): void
    {
        $trabajadores = [];
        if (isset($_GET['datos'])) {
            $trabajadores = json_decode($_GET['datos'], true) ?? [];
        }

        // La vista usa $trabajadores y el método construirPayloadQr() vía $this
        require APP_PATH . '/Views/pases/generar.php';
    }

    /** Expuesto para que la vista pueda llamarlo: $this->construirPayloadQr($t) */
    public function payloadQr(array $trabajador): string
    {
        return $this->construirPayloadQr($trabajador);
    }
}
