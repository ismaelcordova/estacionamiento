<!DOCTYPE html>
<html lang="es-mx">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pases</title>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/generar_pase.css">
    </head>

    <body>
        <div class="container">
            <?php require APP_PATH . '/Views/layout/navbar.php'; ?>
            <div class="content-wrapper">
                <?php require APP_PATH . '/Views/layout/aside.php'; ?>

                <main>
                    <button onclick="window.print()" class="btn-imprimir">
                        Imprimir
                    </button>

                    <?php
                    $grupos = array_chunk($trabajadores, 4);
                    foreach ($grupos as $grupo):
                    ?>
                    <div class="hoja hoja-frontal">
                        <?php foreach ($grupo as $trabajador): ?>
                        <div class="pase">
                            <img src="<?= BASE_URL ?>/assets/imagen/pase_de_estacionamiento_frontal.png" class="fondo-pase">
                            <div class="titulo-pase">
                                <h1>Pase de Estacionamiento</h1>
                            </div>

                            <!-- info del pase-->
                            <div class="datos-pase">
                                <p><strong> </strong> <?= htmlspecialchars($trabajador['tipo']) ?></p>

                                <p><strong>Nombre:</strong> <?= htmlspecialchars($trabajador['nombre']) ?></p>
                                <p><strong>No. Plaza:</strong> <?= htmlspecialchars($trabajador['numero']) ?></p>
                                <p><strong>Marca:</strong> <?= htmlspecialchars($trabajador['marca']) ?></p>
                                <p><strong>Placa:</strong> <?= htmlspecialchars($trabajador['placa']) ?></p>
                            </div>
                            <canvas class="qrcode" data-qr="<?= htmlspecialchars($this->payloadQr($trabajador), ENT_QUOTES, 'UTF-8') ?>"></canvas>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="hoja hoja-reverso">
                        <?php foreach ($grupo as $trabajador): ?>
                        <div class="pase_reverso">
                            <img src="<?= BASE_URL ?>/assets/imagen/pase_de_estacionamiento_reverso.png" class="fondo-pase">
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <?php endforeach; ?>
                </main>
            </div>
        </div>

        <script src="<?= BASE_URL ?>/assets/js/qrcode.min.js"></script>
        <script src="<?= BASE_URL ?>/assets/js/generar_pase_qr.js"></script>
    </body>
</html>
