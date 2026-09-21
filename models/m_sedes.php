<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Sedes / Sucursales (m_sedes.php)
 * ============================================================================
 * Este modelo gestiona las operaciones de base de datos sobre la tabla 'sedes',
 * controlando las sucursales físicas donde opera el sistema de control de filas (filas = 1).
 * 
 * Funcionalidades:
 * - Insertar o reemplazar sedes mediante la instrucción REPLACE INTO.
 * - Activar y desactivar sedes.
 * - Consultar información individual, listado completo y datos para selectores.
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Sede {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta o reemplaza un registro de sede en la tabla 'sedes'
	 * 
	 * @param int    $id      ID de la sede
	 * @param string $sede    Código de la sede (ej: 'S-01', 'S-02')
	 * @param string $descrip Nombre descriptivo de la sucursal
	 * @param string $direcci Dirección física
	 * @param string $grupo   Grupo o zona geográfica
	 * @return bool
	 */
	public function replace($id, $sede, $descrip, $direcci, $grupo) {
		date_default_timezone_set('America/Costa_Rica');
		$sql = "REPLACE INTO sedes (id, sede, descrip, direcci, grupo, filas) VALUES ('$id', '$sede', '$descrip', '$direcci', '$grupo', 1)";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva una sede (estado = 0)
	 */
	public function desactivar($id) {
		$sql = "UPDATE sedes SET estado='0' WHERE id='$id' and filas=1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa una sede (estado = 1)
	 */
	public function activar($id) {
		$sql = "UPDATE sedes SET estado='1' WHERE id='$id' and filas=1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos de una sede específica
	 * 
	 * @param int $id ID de la sede
	 * @return array
	 */
	public function mostrar($id) {
		$sql = "SELECT * FROM sedes WHERE id='$id' and filas=1";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todas las sedes habilitadas para el control de filas
	 * 
	 * @return resource|mysqli_result
	 */
	public function listar() {
		$sql = "SELECT * FROM sedes WHERE filas=1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista las sedes activas para elementos <select>
	 * 
	 * @return resource|mysqli_result
	 */
	public function select() {
		$sql = "SELECT s.`sede` codigo, s.`descrip` nombre FROM sedes s WHERE s.estado=1 and s.filas=1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Consulta el nombre de una sede por su ID
	 * 
	 * @param int $id ID de la sede
	 * @return resource|mysqli_result
	 */
	public function regresaRolSedes($id) {
		$sql = "SELECT nombre FROM sedes WHERE id='$id' and filas=1";		
		return ejecutarConsulta($sql);
	}

}
?>
