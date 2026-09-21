<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Textos de Marquesina (m_textos.php)
 * ============================================================================
 * Este modelo gestiona las operaciones de base de datos sobre la tabla 'textos',
 * correspondiente a los mensajes informativos y de noticias proyectados en
 * la marquesina inferior de las pantallas de sala de espera.
 * 
 * Funcionalidades:
 * - Inserción y actualización de mensajes por sede o globales ('00').
 * - Activación y desactivación de mensajes.
 * - Concatenación de textos activos para la cinta de noticias (gettexto).
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Textos {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta un nuevo texto informativo en la tabla 'textos'
	 * 
	 * @param string $agenci Código de la agencia o '00' para global
	 * @param string $texto  Contenido del mensaje
	 * @param string $usuari Usuario creador
	 * @param int    $estado Estado (1: Activo, 0: Inactivo)
	 * @return bool
	 */
	public function insertar($agenci, $texto, $usuari, $estado) {
		date_default_timezone_set('America/Costa_Rica');
		$sql = "INSERT INTO textos (agenci, texto, usuari, estado) VALUES ('$agenci', '$texto', '$usuari', '$estado')";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de un texto existente
	 */
	public function editar($agenci, $codigo, $texto, $usuari, $estado) {
		$sql = "UPDATE textos SET agenci='$agenci', texto='$texto', usuari='$usuari', estado=$estado  
			WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva un mensaje (estado = 0)
	 */
	public function desactivar($codigo) {
		$sql = "UPDATE textos SET estado=0 WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un mensaje (estado = 1)
	 */
	public function activar($codigo) {
		$sql = "UPDATE textos SET estado=1 WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos de un mensaje por su código
	 */
	public function mostrar($codigo) {
		$sql = "SELECT * FROM textos WHERE codigo='$codigo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todos los mensajes registrados
	 */
	public function listar() {
		$sql = "SELECT * FROM textos";
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista los mensajes con estado activo
	 */
	public function select() {
		$sql = "SELECT * FROM textos WHERE estado = 1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene todos los textos activos vigentes concatenados con separador para la marquesina
	 * (Filtra por la agencia activa en sesión y mensajes globales '00')
	 * 
	 * @return array
	 */
	public function gettexto() {
		$sql = "SELECT GROUP_CONCAT(texto SEPARATOR ' - - - ') texto FROM textos WHERE agenci in ('00', '" . $_SESSION["agenci"] . "') and estado=1 order by codigo limit 1";		
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Obtiene el siguiente texto en orden correlativo para rotación
	 */
	public function gettexto2($codigo) {
		$sql = "SELECT texto, codigo FROM textos WHERE agenci in ('00', '" . $_SESSION["agenci"] . "') and codigo>'$codigo' and estado=1 order by codigo limit 1";
		return ejecutarConsultaSimpleFila($sql);
	}

}
?>
