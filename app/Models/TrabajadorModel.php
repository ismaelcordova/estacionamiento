<?php
require_once __DIR__ . '/../Config/Database.php';

/**
 * Modelo: consultas relacionadas con la tabla `trabajadores` y `sin_vigencia`.
 * Un trabajador se considera INACTIVO si su Id_Trabajador aparece en
 * la tabla `sin_vigencia`.
 *
 * IMPORTANTE: esta clase solo habla con la base de datos.
 * No valida entrada de usuario ni imprime JSON — eso es del Controller.
 */
class TrabajadorModel
{
    /** Lista blanca de columnas que se pueden editar en línea desde la tabla. */
    public const CAMPOS_EDITABLES = ['Folio', 'Nombre_completo', 'Numero_plaza', 'Marca_automovil', 'Placa'];

    /**
     * Devuelve todos los trabajadores de un tipo ('Administrativo' o 'Docente'),
     * junto con su estado (activo = 1, inactivo = 0).
     */
    public function obtenerPorTipo(string $tipo): array
    {
        $conn = getConexion();

        $sql = "SELECT t.Id_Trabajador, t.Folio, t.Nombre_completo, t.Numero_plaza,
                       t.Marca_automovil, t.Placa, t.Tipo_personal,
                       IF(sv.Id_Trabajador IS NULL, 1, 0) AS activo
                FROM trabajadores t
                LEFT JOIN sin_vigencia sv ON sv.Id_Trabajador = t.Id_Trabajador
                WHERE t.Tipo_personal = ?
                ORDER BY t.Folio";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $tipo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    /**
     * Busca un trabajador por su número de plaza y tipo de personal.
     * Devuelve null si no se encuentra ninguno.
     */
    public function buscarPorPlaza(string $numero, string $tipo): ?array
    {
        $conn = getConexion();

        $sql = "SELECT t.Nombre_completo, t.Numero_plaza, t.Tipo_personal,
                       IF(sv.Id_Trabajador IS NULL, 1, 0) AS activo
                FROM trabajadores t
                LEFT JOIN sin_vigencia sv ON sv.Id_Trabajador = t.Id_Trabajador
                WHERE t.Numero_plaza = ? AND t.Tipo_personal = ?
                LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $numero, $tipo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $fila = mysqli_fetch_assoc($resultado);

        return $fila ?: null;
    }

    /**
     * Inserta un nuevo trabajador. El Folio se calcula automáticamente como
     * el siguiente disponible dentro de su tipo de personal. Devuelve el
     * registro insertado (listo para pintar en la tabla) o null si falla.
     */
    public function crear(string $nombre, string $numero, string $marca, string $placa, string $tipo): ?array
    {
        $conn = getConexion();

        $sqlFolio = "SELECT COALESCE(MAX(Folio), 0) + 1 AS siguiente FROM trabajadores WHERE Tipo_personal = ?";
        $stmt = mysqli_prepare($conn, $sqlFolio);
        mysqli_stmt_bind_param($stmt, 's', $tipo);
        mysqli_stmt_execute($stmt);
        $folio = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['siguiente'];

        $sqlInsert = "INSERT INTO trabajadores (Folio, Nombre_completo, Numero_plaza, Marca_automovil, Placa, Tipo_personal)
                      VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sqlInsert);
        mysqli_stmt_bind_param($stmt, 'isssss', $folio, $nombre, $numero, $marca, $placa, $tipo);

        if (!mysqli_stmt_execute($stmt)) {
            return null;
        }

        return [
            'Id_Trabajador'   => mysqli_insert_id($conn),
            'Folio'           => $folio,
            'Nombre_completo' => $nombre,
            'Numero_plaza'    => $numero,
            'Marca_automovil' => $marca,
            'Placa'           => $placa,
            'Tipo_personal'   => $tipo,
            'activo'          => 1,
        ];
    }

    /**
     * Actualiza un solo campo de un trabajador (usado por la edición en línea
     * de la tabla, tipo phpMyAdmin). $campo debe estar en CAMPOS_EDITABLES.
     */
    public function actualizarCampo(int $id, string $campo, string $valor): bool
    {
        if (!in_array($campo, self::CAMPOS_EDITABLES, true)) {
            return false;
        }

        $conn = getConexion();
        $sql  = "UPDATE trabajadores SET `$campo` = ? WHERE Id_Trabajador = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $valor, $id);

        return mysqli_stmt_execute($stmt);
    }

    /**
     * Marca o quita la vigencia de un trabajador.
     * $vigente = true  -> el trabajador queda ACTIVO (se elimina de sin_vigencia si estaba)
     * $vigente = false -> el trabajador queda INACTIVO (se agrega a sin_vigencia si no estaba)
     */
    public function establecerVigencia(int $id, bool $vigente): bool
    {
        $conn = getConexion();

        if ($vigente) {
            $sql  = "DELETE FROM sin_vigencia WHERE Id_Trabajador = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            return mysqli_stmt_execute($stmt);
        }

        // Evita duplicados si ya estaba marcado como sin vigencia
        $sql  = "INSERT INTO sin_vigencia (Id_Trabajador)
                 SELECT * FROM (SELECT ? AS id) AS tmp
                 WHERE NOT EXISTS (SELECT 1 FROM sin_vigencia WHERE Id_Trabajador = ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $id, $id);
        return mysqli_stmt_execute($stmt);
    }
}
