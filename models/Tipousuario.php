<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Tipos de Usuario / Roles (Tipousuario.php)
 * ============================================================================
 * Este modelo gestiona las operaciones de base de datos sobre la tabla 'tipousuario',
 * la cual define los diferentes perfiles o roles de acceso para los usuarios del sistema.
 * 
 * Funcionalidades:
 * - Insertar y editar roles de usuario.
 * - Activar y desactivar perfiles.
 * - Consultar información para formularios, listados y selectores.
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Tipousuario {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta un nuevo tipo de usuario / rol en la tabla 'tipousuario'
	 * 
	 * @param string $nombre      Nombre del rol
	 * @param string $descripcion Descripción de los permisos o alcance
	 * @param int    $idusuario   ID del usuario creador
	 * @return bool
	 */
	public function insertar($nombre, $descripcion, $idusuario) {
		date_default_timezone_set('America/Costa_Rica');
		$fechacreada = date('Y-m-d H:i:s');
		$sql = "INSERT INTO tipousuario (nombre, descripcion, fechacreada, idusuario) VALUES ('$nombre', '$descripcion', '$fechacreada', '$idusuario')";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de un rol existente
	 * 
	 * @param int    $idtipousuario ID del rol
	 * @param string $nombre        Nuevo nombre
	 * @param string $descripcion   Nueva descripción
	 * @param int    $idusuario     ID del usuario editor
	 * @return bool
	 */
	public function editar($idtipousuario, $nombre, $descripcion, $idusuario) {
		$sql = "UPDATE tipousuario SET nombre='$nombre', descripcion='$descripcion', idusuario='$idusuario' 
			WHERE idtipousuario='$idtipousuario'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva un tipo de usuario
	 */
	public function desactivar($idtipousuario) {
		$sql = "UPDATE tipousuario SET fechacreada='0' WHERE idtipousuario='$idtipousuario'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un tipo de usuario
	 */
	public function activar($idtipousuario) {
		$sql = "UPDATE tipousuario SET fechacreada='1' WHERE idtipousuario='$idtipousuario'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos de un tipo de usuario por su ID
	 * 
	 * @param int $idtipousuario ID del rol
	 * @return array
	 */
	public function mostrar($idtipousuario) {
		$sql = "SELECT * FROM tipousuario WHERE idtipousuario='$idtipousuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todos los tipos de usuario registrados
	 * 
	 * @return resource|mysqli_result
	 */
	public function listar() {
		$sql = "SELECT * FROM tipousuario";
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista los tipos de usuario para elementos <select>
	 * 
	 * @return resource|mysqli_result
	 */
	public function select() {
		$sql = "SELECT * FROM tipousuario";
		return ejecutarConsulta($sql);
	}
}
?>
