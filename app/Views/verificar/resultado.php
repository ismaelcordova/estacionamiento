<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de pase</title>
    <style>
        *, *::before, *::after {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        body {
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .tarjeta {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 40px 32px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .estado {
            font-size: 72px;
            margin-bottom: 16px;
            line-height: 1;
        }
        .badge {
            display: inline-block;
            padding: 6px 22px;
            border-radius: 99px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .badge.activo   { background: #16a34a; color: #fff; }
        .badge.inactivo { background: #dc2626; color: #fff; }
        .badge.no-found { background: #475569; color: #fff; }
        h2 {
            font-size: 18px;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 4px;
        }
        .subtitulo {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 24px;
        }
        .info {
            text-align: left;
            border-top: 1px solid #334155;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .info-fila {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .info-label { color: #64748b; }
        .info-valor { color: #e2e8f0; font-weight: 600; }
        .mensaje-error {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<div class="tarjeta">

    <?php if (!$encontrado): ?>

        <span class="badge no-found">No encontrado</span>
        <h2>Trabajador no encontrado</h2>
        <p class="mensaje-error">
            No se encontró ningún registro con número de plaza
            <strong><?= htmlspecialchars($numero) ?></strong>
            en la lista de <?= htmlspecialchars($tipo) ?: 'trabajadores' ?>.
        </p>

    <?php elseif ($encontrado['activo']): ?>

        <span class="badge activo">Activo</span>
        <h2><?= htmlspecialchars($encontrado['Nombre_completo']) ?></h2>
        <p class="subtitulo"><?= htmlspecialchars($encontrado['Tipo_personal']) ?></p>
        <div class="info">
            <div class="info-fila">
                <span class="info-label">No. Plaza</span>
                <span class="info-valor"><?= htmlspecialchars($encontrado['Numero_plaza']) ?></span>
            </div>
            <div class="info-fila">
                <span class="info-label">Estado</span>
                <span class="info-valor" style="color:#4ade80;">Activo ✔</span>
            </div>
        </div>

    <?php else: ?>

        <span class="badge inactivo">Inactivo</span>
        <h2><?= htmlspecialchars($encontrado['Nombre_completo']) ?></h2>
        <p class="subtitulo"><?= htmlspecialchars($encontrado['Tipo_personal']) ?></p>
        <div class="info">
            <div class="info-fila">
                <span class="info-label">No. Plaza</span>
                <span class="info-valor"><?= htmlspecialchars($encontrado['Numero_plaza']) ?></span>
            </div>
            <div class="info-fila">
                <span class="info-label">Estado</span>
                <span class="info-valor" style="color:#f87171;">Inactivo ✘</span>
            </div>
        </div>

    <?php endif; ?>

</div>
</body>
</html>
