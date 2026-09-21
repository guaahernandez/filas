<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Colaboradores (m_colaboradores.php)
 * ============================================================================
 * Este modelo gestiona las operaciones de base de datos sobre la tabla 'colaboradores',
 * la cual almacena los datos de los empleados, su fotografía, departamento y agencia.
 * 
 * Funcionalidades:
 * - Inserción y edición de colaboradores.
 * - Activación y desactivación de registros.
 * - Consultas individuales, listado general y conteo de usuarios activos.
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Colaborador {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {}

	/**
	 * Inserta un nuevo colaborador en la tabla 'colaboradores'
	 * 
	 * @param string $agenci Código de la sucursal/agencia
	 * @param string $usuari Nombre de usuario del sistema
	 * @param string $nombre Nombre completo del colaborador
	 * @param string $imagen Nombre del archivo de imagen/fotografía
	 * @param string $depart Departamento al que pertenece
	 * @return bool
	 */
	public function insertar($agenci, $usuari, $nombre, $imagen, $depart) {
		date_default_timezone_set('America/Costa_Rica');
		$sql = "INSERT INTO colaboradores (agenci, usuari, nombre, imagen, depart, fechac, estado) VALUES ('$agenci', '$usuari', '$nombre', '$imagen', '$depart', NOW(), 1)";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de un colaborador existente
	 * 
	 * @param string $agenci Código de la agencia
	 * @param string $usuari Usuario
	 * @param int    $codigo ID del colaborador
	 * @param string $nombre Nombre completo
	 * @param string $imagen Nombre del archivo de imagen
	 * @param string $depart Departamento
	 * @param int    $estado Estado (1: Activo, 0: Inactivo)
	 * @return bool
	 */
	public function editar($agenci, $usuari, $codigo, $nombre, $imagen, $depart, $estado) {
		$sql = "UPDATE colaboradores SET agenci='$agenci', usuari='$usuari', nombre='$nombre', imagen='$imagen', depart='$depart', fechac=NOW(), estado=$estado    
			WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva un colaborador (estado = 0)
	 */
	public function desactivar($codigo) {
		$sql = "UPDATE colaboradores SET estado='0' WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un colaborador (estado = 1)
	 */
	public function activar($codigo) {
		$sql = "UPDATE colaboradores SET estado='1' WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene la información de un colaborador por su código
	 * 
	 * @param int $codigo ID del colaborador
	 * @return array
	 */
	public function mostrar($codigo) {
		$sql = "SELECT * FROM colaboradores WHERE codigo='$codigo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todos los colaboradores registrados
	 * 
	 * @return resource|mysqli_result
	 */
	public function listar() {
		$sql = "SELECT * FROM colaboradores";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene la cantidad total de colaboradores activos
	 * 
	 * @return resource|mysqli_result
	 */
	public function cantidad_usuario() {
		$sql = "SELECT count(*) nombre FROM colaboradores WHERE estado=1";
		return ejecutarConsulta($sql);
	}

}
?>
