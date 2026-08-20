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
}