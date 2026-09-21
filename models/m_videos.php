<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Base de Datos para Videos (m_videos.php)
 * ============================================================================
 * Este modelo gestiona las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) 
 * y consultas específicas sobre la tabla 'videos' de la base de datos, 
 * la cual almacena la configuración y rutas de los archivos multimedia 
 * utilizados en las pantallas de atención al cliente.
 * 
 * Incluye métodos para:
 * - Insertar nuevos videos.
 * - Actualizar videos existentes.
 * - Activar/desactivar y eliminar videos.
 * - Listar y buscar videos.
 * - Lógica especial para la reproducción en bucle secuencial (getdirecc2).
 * ============================================================================
 */

// Inclusión del archivo que contiene la lógica de conexión a la base de datos
require "../config/Conexion.php";

class Videos {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta un nuevo video en la tabla 'videos'
	 * 
	 * @param string $agenci Código de la agencia ('00' para todas)
	 * @param string $direcc Nombre del archivo de video
	 * @param string $descri Descripción del video
	 * @param string $format Formato ('horizontal' o 'vertical')
	 * @return bool
	 */
	public function insertar($agenci, $direcc, $descri, $format) {
		date_default_timezone_set('America/Costa_Rica');
		$sql = "INSERT INTO videos (agenci, direcc, descri, format) VALUES ('$agenci', '$direcc', '$descri', '$format')";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de un video existente
	 */
	public function editar($agenci, $codigo, $direcc, $descri, $format) {
		$sql = "UPDATE videos SET agenci='$agenci', direcc='$direcc', descri='$descri', format='$format'  
			WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva un video (estado = 0)
	 */
	public function desactivar($codigo) {
		$sql = "UPDATE videos SET estado=0 WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un video (estado = 1)
	 */
	public function activar($codigo) {
		$sql = "UPDATE videos SET estado=1 WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Marca un video como eliminado (estado = 2)
	 */
	public function eliminar($codigo) {
		$sql = "UPDATE videos SET estado=2 WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos de un video específico
	 */
	public function mostrar($codigo) {
		$sql = "SELECT * FROM videos WHERE codigo='$codigo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todos los videos no eliminados (estado < 2)
	 */
	public function listar() {
		$sql = "SELECT * FROM videos WHERE estado<2";
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista los videos activos (estado = 1)
	 */
	public function select() {
		$sql = "SELECT * FROM videos WHERE estado = 1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene la dirección del archivo de video por su código
	 */
	public function getdirecc($codigo) {
		$sql = "SELECT direcc FROM videos WHERE codigo='$codigo'";		
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Obtiene el siguiente video en la lista de reproducción continua
	 * (Filtra por sucursal, estado activo y orientación de video en sesión)
	 * 
	 * @param int $codigo Código del video actual
	 * @return array
	 */
	public function getdirecc2($codigo) {
		$sql = "SELECT direcc, codigo FROM videos WHERE agenci in ('00', '" . $_SESSION["agenci"] . "') and codigo>$codigo and estado=1 and format='" . $_SESSION["formatovideo"] . "' order by codigo limit 1";
		return ejecutarConsultaSimpleFila($sql);
	}

}
?>
