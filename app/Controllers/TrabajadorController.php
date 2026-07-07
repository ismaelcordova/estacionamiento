<?php
require_once __DIR__ . '/../Models/TrabajadorModel.php';
require_once __DIR__ . '/../Core/Response.php';

class TrabajadorController
{
    private TrabajadorModel $model;

    public function __construct()
    {
        $this->model = new TrabajadorModel();
    }

    /**
     * Recibe los datos del formulario "Agregar trabajador" y los guarda.
     * Antes: config/agregar_trabajador.php
     */
    public function agregar(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $marca  = trim($_POST['marca']  ?? '');
        $placa  = trim($_POST['placa']  ?? '');
        $tipo   = trim($_POST['tipo']   ?? '');

        if ($nombre === '' || $numero === '' || $marca === '' || $placa === '' || $tipo === '') {
            Response::json(['ok' => false, 'error' => 'Completa todos los campos']);
        }

        if (!in_array($tipo, ['Administrativo', 'Docente'], true)) {
            Response::json(['ok' => false, 'error' => 'Tipo de personal inválido']);
        }

        if (!ctype_digit($numero)) {
            Response::json(['ok' => false, 'error' => 'El número de plaza solo acepta números']);
        }

        $nuevo = $this->model->crear($nombre, $numero, $marca, $placa, $tipo);

        Response::json(
            $nuevo
                ? ['ok' => true, 'trabajador' => $nuevo]
                : ['ok' => false, 'error' => 'No se pudo guardar el trabajador']
        );
    }

    /**
     * Recibe la edición de una sola celda de la tabla.
     * Antes: config/actualizar_campo.php
     */
    public function actualizarCampo(): void
    {
        $id    = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $campo = trim($_POST['campo'] ?? '');
        $valor = trim($_POST['valor'] ?? '');

        if (!$id || $campo === '' || $valor === '') {
            Response::json(['ok' => false, 'error' => 'Datos incompletos']);
        }

        if (($campo === 'Numero_plaza' || $campo === 'Folio') && !ctype_digit($valor)) {
            Response::json(['ok' => false, 'error' => 'Este campo solo acepta números']);
        }

        $guardado = $this->model->actualizarCampo($id, $campo, $valor);

        Response::json(
            $guardado
                ? ['ok' => true]
                : ['ok' => false, 'error' => 'No se pudo guardar el cambio']
        );
    }

    /**
     * Recibe el cambio del switch "Vigencia".
     * Antes: config/actualizar_vigencia.php
     */
    public function actualizarVigencia(): void
    {
        $id      = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $vigente = filter_input(INPUT_POST, 'vigente', FILTER_VALIDATE_INT);

        if (!$id || $vigente === null || $vigente === false) {
            Response::json(['ok' => false, 'error' => 'Datos incompletos']);
        }

        $guardado = $this->model->establecerVigencia($id, (bool) $vigente);

        Response::json(
            $guardado
                ? ['ok' => true]
                : ['ok' => false, 'error' => 'No se pudo actualizar la vigencia']
        );
    }
}
