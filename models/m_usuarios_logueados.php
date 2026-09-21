<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Usuarios Logueados (m_usuarios_logueados.php)
 * ============================================================================
 * Este modelo gestiona la consulta y control de sesiones activas de los vendedores
 * y agentes en la tabla 'cias_vendedores_img' (logueados en ERP o en la app de filas).
 * 
 * Funcionalidades:
 * - Listar vendedores conectados en la fecha actual por sucursal.
 * - Desloguear / forzar cierre de sesión de un vendedor (logue03 = 0, logerp = 0).
 * - Listar sedes disponibles.
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class UsuariosLogueados {

    /**
     * Constructor de la clase
     */
    public function __construct() {

    }

    /**
     * Lista los vendedores logueados en la fecha actual filtrados por sucursal
     * 
     * @param string $sede Código de la sede (sucur03)
     * @return resource|mysqli_result
     */
    public function listar($sede) {
        $sql = "SELECT nombc03, estac03, fechlogerp fechlog FROM cias_vendedores_img 
            WHERE sucur03='$sede' AND ((logue03='1' AND fechlog > DATE_FORMAT(NOW(),'%Y-%m-%d')) OR (logerp=1 AND fechlogerp > DATE_FORMAT(NOW(),'%Y-%m-%d')))";
        return ejecutarConsulta($sql);
    }

    /**
     * Fuerza el cierre de sesión de un vendedor en el sistema y en el ERP
     * 
     * @param string $nombc03 Nombre de usuario del vendedor
     * @return bool
     */
    public function desloguear($nombc03) {
        $sql = "UPDATE cias_vendedores_img SET logue03='0', logerp='0' WHERE nombc03='$nombc03'";
        return ejecutarConsulta($sql);
    }

    /**
     * Lista todas las sedes registradas
     * 
     * @return resource|mysqli_result
     */
    public function listarSedes() {
        $sql = "SELECT * FROM sedes";
        return ejecutarConsulta($sql);
    }
}
?>
