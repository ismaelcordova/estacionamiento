<?php
require_once __DIR__ . '/../Models/TrabajadorModel.php';

class HomeController
{
    public function index(): void
    {
        $model = new TrabajadorModel();

        $secciones = [
            'Administrativo' => $model->obtenerPorTipo('Administrativo'),
            'Docente'        => $model->obtenerPorTipo('Docente'),
        ];

        // La vista recibe $secciones ya lista para pintar
        require APP_PATH . '/Views/home/index.php';
    }
}
