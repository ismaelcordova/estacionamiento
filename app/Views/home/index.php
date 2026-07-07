<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estacionamiento</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/index.css">
</head>
<body>
    <div class="container">
        <?php require APP_PATH . '/Views/layout/navbar.php'; ?>
        <div class="content-wrapper">
            <?php require APP_PATH . '/Views/layout/aside.php'; ?>

            <main>

                <div class="barra-superior">
                    <div class="filtro-activo">
                        <button class="btn-filtro" data-filtro="todos">Todos</button>
                        <button class="btn-filtro activo" data-filtro="si">Activos</button>
                        <button class="btn-filtro" data-filtro="no">Inactivos</button>
                    </div>
                    <div class="acciones-superiores">
                        <button id="btnToggleVigencia" class="btn-vigencia" type="button">👁 Vigencia</button>
                        <button id="btnAgregarTrabajador" class="btn-agregar" type="button">+ Agregar trabajador</button>
                    </div>
                </div>

                <div class="tablas-wrapper">
                <?php foreach ($secciones as $titulo => $trabajadores): ?>
                <div class="sub-contenedor">
                    <h1><?= htmlspecialchars($titulo) ?></h1>
                    <div class="tabla-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Nombre</th>
                                    <th>No. Plaza</th>
                                    <th>Marca</th>
                                    <th>Placa</th>
                                    <th class="col-vigencia">Vigencia</th>
                                    <th>Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($trabajadores as $t): ?>
                                <tr data-id="<?= (int) $t['Id_Trabajador'] ?>" <?= $t['activo'] ? '' : 'class="fila-inactiva"' ?>>
                                    <td data-campo="Folio"><?= htmlspecialchars($t['Folio']) ?></td>
                                    <td data-campo="Nombre_completo"><?= htmlspecialchars($t['Nombre_completo']) ?></td>
                                    <td data-campo="Numero_plaza"><?= htmlspecialchars($t['Numero_plaza']) ?></td>
                                    <td data-campo="Marca_automovil"><?= htmlspecialchars($t['Marca_automovil']) ?></td>
                                    <td data-campo="Placa"><?= htmlspecialchars($t['Placa']) ?></td>
                                    <td class="col-vigencia">
                                        <label class="switch-vigencia">
                                            <input type="checkbox" class="vigencia-toggle"
                                                data-id="<?= (int) $t['Id_Trabajador'] ?>"
                                                <?= $t['activo'] ? 'checked' : '' ?>>
                                            <span class="switch-slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="trabajador-check"
                                            data-nombre="<?= htmlspecialchars($t['Nombre_completo']) ?>"
                                            data-numero="<?= htmlspecialchars($t['Numero_plaza']) ?>"
                                            data-marca="<?= htmlspecialchars($t['Marca_automovil']) ?>"
                                            data-placa="<?= htmlspecialchars($t['Placa']) ?>"
                                            data-tipo="<?= htmlspecialchars($titulo) ?>">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>

                <button id="generarPasesBtn" class="btn-generar">
                    Generar pases seleccionados
                </button>
            </main>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/buscar.js"></script>

    <script>
    document.getElementById("generarPasesBtn").addEventListener("click", () => {
        const seleccionados = [];
        document.querySelectorAll(".trabajador-check:checked").forEach(check => {
            seleccionados.push({
                nombre: check.dataset.nombre,
                numero: check.dataset.numero,
                marca:  check.dataset.marca,
                placa:  check.dataset.placa,
                tipo:   check.dataset.tipo
            });
        });
        if (seleccionados.length === 0) {
            alert("Selecciona al menos un trabajador");
            return;
        }
        const datos = encodeURIComponent(JSON.stringify(seleccionados));
        window.open(`<?= BASE_URL ?>/pases?datos=${datos}`, "_blank");
    });
    </script>

    <div id="modalAgregar" class="modal-overlay" hidden>
        <div class="modal-caja">
            <h2>Agregar trabajador</h2>
            <form id="formAgregarTrabajador">
                <label>
                    Tipo de personal
                    <select name="tipo" required>
                        <option value="Administrativo">Administrativo</option>
                        <option value="Docente">Docente</option>
                    </select>
                </label>
                <label>
                    Nombre completo
                    <input type="text" name="nombre" required autocomplete="off">
                </label>
                <label>
                    No. Plaza
                    <input type="text" name="numero" inputmode="numeric" required autocomplete="off">
                </label>
                <label>
                    Marca
                    <input type="text" name="marca" required autocomplete="off">
                </label>
                <label>
                    Placa
                    <input type="text" name="placa" required autocomplete="off">
                </label>

                <p class="modal-error" id="modalError"></p>

                <div class="modal-acciones">
                    <button type="button" id="btnCancelarAgregar" class="btn-cancelar">Cancelar</button>
                    <button type="submit" class="btn-guardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/generar_pase_qr.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/filtro_activo.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/editar_celda.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/agregar_trabajador.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vigencia.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/toggle_columna_vigencia.js"></script>
</body>
</html>
