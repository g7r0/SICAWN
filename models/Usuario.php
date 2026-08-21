<?php
/**
 * models/Usuario.php
 * Acceso a datos de la tabla usuarios — CU-01, CU-02, CU-04.
 */

require_once __DIR__ . '/../config/conexion.php';

class Usuario
{
    /**
     * Devuelve todos los usuarios activos, incluyendo los que
     * aún no tienen rol asignado (rol IS NULL).
     */
    public static function obtenerTodos(): array
    {
        $db = conectarDB();
        $stmt = $db->query(
            "SELECT id_usuario, nombre_completo, nombre_usuario, telefono, rol
             FROM usuarios
             WHERE activo = 1
             ORDER BY nombre_completo ASC"
        );
        return $stmt->fetchAll();
    }

    public static function obtenerPorId(int $idUsuario): ?array
    {
        $db = conectarDB();
        $stmt = $db->prepare(
            "SELECT id_usuario, nombre_completo, nombre_usuario, telefono, rol
             FROM usuarios
             WHERE id_usuario = ? AND activo = 1"
        );
        $stmt->execute([$idUsuario]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    /**
     * Asigna o cambia el rol de un usuario.
     * Devuelve true si se actualizó correctamente.
     */
    public static function asignarRol(int $idUsuario, string $rol): bool
    {
        if (!in_array($rol, ['presidente', 'cobrador'], true)) {
            return false; // rol inválido, no se procesa (RNF-06)
        }

        $db = conectarDB();
        $stmt = $db->prepare(
            "UPDATE usuarios SET rol = ? WHERE id_usuario = ? AND activo = 1"
        );
        return $stmt->execute([$rol, $idUsuario]);
    }
    /**
     * Busca un usuario por su nombre de usuario, trayendo TODOS los
     * campos necesarios para validar el login (incluye contraseña,
     * intentos fallidos y bloqueo).
     */
    public static function obtenerPorNombreUsuario(string $nombreUsuario): ?array
    {
        $db = conectarDB();
        $stmt = $db->prepare(
            "SELECT id_usuario, nombre_completo, nombre_usuario, contrasena,
                    rol, intentos_fallidos, bloqueado_hasta
             FROM usuarios
             WHERE nombre_usuario = ? AND activo = 1"
        );
        $stmt->execute([$nombreUsuario]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    /**
     * Suma un intento fallido. Si llega a 5, bloquea 30 minutos.
     */
    public static function registrarIntentoFallido(int $idUsuario): void
    {
        $db = conectarDB();

        $stmt = $db->prepare("SELECT intentos_fallidos FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        $intentosActuales = (int) $stmt->fetchColumn();
        $nuevosIntentos = $intentosActuales + 1;

        if ($nuevosIntentos >= 5) {
            $db->prepare(
                "UPDATE usuarios
                 SET intentos_fallidos = ?, bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                 WHERE id_usuario = ?"
            )->execute([$nuevosIntentos, $idUsuario]);
        } else {
            $db->prepare(
                "UPDATE usuarios SET intentos_fallidos = ? WHERE id_usuario = ?"
            )->execute([$nuevosIntentos, $idUsuario]);
        }
    }

    /**
     * Reinicia el contador de intentos fallidos (login exitoso).
     */
    public static function reiniciarIntentos(int $idUsuario): void
    {
        $db = conectarDB();
        $db->prepare(
            "UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id_usuario = ?"
        )->execute([$idUsuario]);
    }

    /**
     * Devuelve los minutos restantes de bloqueo, o 0 si ya no está bloqueado.
     */
    public static function minutosDeBloqueoRestantes(array $usuario): int
    {
        if (empty($usuario['bloqueado_hasta'])) {
            return 0;
        }

        $ahora = new DateTime();
        $finBloqueo = new DateTime($usuario['bloqueado_hasta']);

        if ($finBloqueo <= $ahora) {
            return 0;
        }

        $diferencia = $ahora->diff($finBloqueo);
        return ($diferencia->h * 60) + $diferencia->i + 1; // +1 para redondear hacia arriba
    }
}