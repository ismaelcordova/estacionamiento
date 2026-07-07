<?php
/**
 * Mapeo de rutas: "METODO /ruta" => [Controlador, método]
 */
return [
    'GET /'                        => ['HomeController', 'index'],
    'GET /verificar'               => ['VerificarController', 'index'],
    'GET /pases'                   => ['PaseController', 'generar'],

    'POST /trabajadores'           => ['TrabajadorController', 'agregar'],
    'POST /trabajadores/campo'     => ['TrabajadorController', 'actualizarCampo'],
    'POST /trabajadores/vigencia'  => ['TrabajadorController', 'actualizarVigencia'],
];
