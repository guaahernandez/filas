<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Departamentos / Compañías (Departamento.php)
 * ============================================================================
 * Este modelo gestiona las operaciones de base de datos sobre la tabla 'cias',
 * correspondiente al catálogo de departamentos o áreas organizacionales.
 * 
 * Funcionalidades:
 * - Insertar y editar registros de departamentos.
 * - Activar y desactivar el estado del registro.
 * - Consultar información individual, listado completo y datos para selectores.
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Departamento {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta un nuevo departamento en la tabla 'cias'
	 * 
	 * @param string $nombre      Nombre del departamento
	 * @param string $descripcion Descripción del departamento
	 * @param int    $idusuario   ID del usuario que realiza la creación
	 * @return bool
	 */
	public function insertar($nombre, $descripcion, $idusuario) {
		date_default_timezone_set('America/Costa_Rica');
		$fechacreada = date('Y-m-d H:i:s');
		$sql = "INSERT INTO cias (nombre, descripcion, fechacreada, idusuario) VALUES ('$nombre', '$descripcion', '$fechacreada', '$idusuario')";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de un departamento existente
	 * 
	 * @param int    $iddepartamento ID del departamento
	 * @param string $nombre         Nuevo nombre
	 * @param string $descripcion    Nueva descripción
	 * @param int    $idusuario      ID del usuario editor
	 * @return bool
	 */
	public function editar($iddepartamento, $nombre, $descripcion, $idusuario) {
		$sql = "UPDATE cias SET nombre='$nombre', descripcion='$descripcion', idusuario='$idusuario', estado=1  
			WHERE iddepartamento='$iddepartamento'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva un departamento (estado = 0)
	 */
	public function desactivar($iddepartamento) {
		$sql = "UPDATE cias SET estado='0' WHERE iddepartamento='$iddepartamento'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un departamento (estado = 1)
	 */
	public function activar($iddepartamento) {
		$sql = "UPDATE cias SET estado='1' WHERE iddepartamento='$iddepartamento'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos de un departamento específico
	 * 
	 * @param int $iddepartamento ID del departamento
	 * @return array
	 */
	public function mostrar($iddepartamento) {
		$sql = "SELECT * FROM cias WHERE iddepartamento='$iddepartamento'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todos los departamentos registrados
	 * 
	 * @return resource|mysqli_result
	 */
	public function listar() {
		$sql = "SELECT * FROM cias";
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista los departamentos con estado activo para selectores HTML
	 * 
	 * @return resource|mysqli_result
	 */
	public function select() {
		$sql = "SELECT * FROM cias WHERE estado = 1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Consulta el nombre de un departamento a partir de su ID
	 * 
	 * @param int $departamento ID del departamento
	 * @return resource|mysqli_result
	 */
	public function regresaRolDepartamento($departamento) {
		$sql = "SELECT nombre FROM cias WHERE iddepartamento='$departamento'";		
		return ejecutarConsulta($sql);
	}

}
?>
