<?php
require_once __DIR__ . '/../Models/TrabajadorModel.php';

class VerificarController
{
    public function index(): void
    {
        $numero = trim($_GET['numero'] ?? '');
        $tipo   = trim($_GET['tipo']   ?? '');

        $encontrado = null;
        if ($numero !== '' && $tipo !== '') {
            $model = new TrabajadorModel();
            $encontrado = $model->buscarPorPlaza($numero, $tipo);
        }

        // La vista recibe $numero, $tipo, $encontrado
        require APP_PATH . '/Views/verificar/resultado.php';
    }
}
