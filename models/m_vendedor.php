<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Vendedores / Agentes (m_vendedor.php)
 * ============================================================================
 * Este modelo gestiona las tablas 'cias_vendedores' y 'cias_vendedores_img',
 * administrando la sincronización de agentes de ventas, imágenes de perfil,
 * activación para atención en quioscos y asignación a programas especiales (Guaca Club / Call Center).
 * 
 * Funcionalidades:
 * - Insertar y editar vendedores y su información multimedia / atributos especiales.
 * - Activar y desactivar vendedores para quioscos.
 * - Consultar información de agentes agregados y pendientes de agregar.
 * - Listado general de vendedores con imágenes y agencias.
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Vendedor {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {}

	/**
	 * Inserta un nuevo vendedor en 'cias_vendedores' y registra/actualiza sus datos en 'cias_vendedores_img'
	 * 
	 * @param string $agent  Código de agente
	 * @param string $nombl  Nombre largo / completo
	 * @param string $nombc  Nombre corto / usuario
	 * @param string $apart  Apartado / Identificador
	 * @param string $direc  Dirección / Agencia
	 * @param string $imagen Nombre del archivo de fotografía
	 * @param int    $gc     Pertenencia a Guaca Club (1: Sí, 0: No)
	 * @param int    $cc     Pertenencia a Call Center (1: Sí, 0: No)
	 * @return bool
	 */
	public function insertar($agent, $nombl, $nombc, $apart, $direc, $imagen, $gc, $cc) {
		$sql = "INSERT INTO cias_vendedores (agent03, nombl03, nombc03, apart03, direc03, local) VALUES ('$agent', '$nombl', '$nombc', '$apart', '$direc', 1)";
		$insert = ejecutarConsulta($sql);
		
		if (existe("SELECT nombc03 FROM cias_vendedores_img WHERE nombc03='$nombc'")) {
			$sql = "UPDATE cias_vendedores_img SET activo='1', imagen='$imagen', gc='$gc', cc='$cc' WHERE nombc03='$nombc'";
			return ejecutarConsulta($sql);
		} else {
			$sql = "INSERT INTO cias_vendedores_img (nombc03, imagen, activo, gc, cc) VALUES ('$nombc', '$imagen', 1, $gc, $cc)";
			ejecutarConsulta($sql);
		}
		return $insert;
	}

	/**
	 * Actualiza los datos y configuración del vendedor en 'cias_vendedores_img'
	 */
	public function editar($agent, $nombl, $nombc, $apart, $direc, $local, $imagen, $activo, $gc, $cc) {
		if (existe("SELECT nombc03 FROM cias_vendedores_img WHERE nombc03='$nombc'")) {
			$sql = "UPDATE cias_vendedores_img SET nombl03='$nombl', apart03='$apart', imagen='$imagen', activo=$activo, gc=$gc, cc=$cc WHERE nombc03='$nombc'";
			$res = ejecutarConsulta($sql);
		} else {
			$sql = "INSERT INTO cias_vendedores_img (nombl03, nombc03, apart03, imagen, activo, gc, cc) VALUES ('$nombl', '$nombc', '$apart', '$imagen', $activo, $gc, $cc)";
			$res = ejecutarConsulta($sql);
		}
		return $res;
	}

	/**
	 * Desactiva un vendedor para la selección en quioscos
	 */
	public function desactivar($nombc) {
		if (existe("SELECT nombc03 FROM cias_vendedores_img WHERE nombc03='$nombc'")) {
			$sql = "UPDATE cias_vendedores_img SET activo='0' WHERE nombc03='$nombc'";
		} else {
			$sql = "INSERT INTO cias_vendedores_img(nombc03, activo) VALUES('$nombc', 0)";
		}
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un vendedor para la selección en quioscos
	 */
	public function activar($nombc) {
		if (existe("SELECT nombc03 FROM cias_vendedores_img WHERE nombc03='$nombc'")) {
			$sql = "UPDATE cias_vendedores_img SET activo='1' WHERE nombc03='$nombc'";
		} else {
			$sql = "INSERT INTO cias_vendedores_img(nombc03, activo) VALUES('$nombc', 1)";
		}
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos detallados de un vendedor (cruzando ambas tablas)
	 */
	public function mostrar($nombc) {
		$sql = "SELECT a.agent03, ifnull(b.nombl03, a.nombl03) nombl03, a.nombc03, b.apart03, a.direc03, a.local, IFNULL(b.imagen, 'guaca.jpg') imagen, IFNULL(b.`activo`, 1) activo, IFNULL(b.`gc`, 0) gc, IFNULL(b.`cc`, 0) cc FROM `cias_vendedores` a
			LEFT JOIN `cias_vendedores_img` b ON b.`nombc03`=a.`nombc03` WHERE a.nombc03='$nombc'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Obtiene los datos de un vendedor directamente de la tabla 'cias_vendedores'
	 */
	public function mostrarVend($nombc) {
		$sql = "SELECT a.agent03, a.nombl03, a.nombc03, a.apart03, a.direc03 FROM `cias_vendedores` a
			WHERE a.nombc03='$nombc'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista todos los vendedores con sus fotos y atributos para la administración
	 */
	public function listar() {
		$sql = "SELECT a.agent03, IFNULL(b.nombl03, a.`nombl03`) nombl03, a.nombc03, b.apart03, a.direc03, a.local, IFNULL(b.imagen, 'guaca.jpg') imagen, IFNULL(b.`activo`, 1) activo, IFNULL(b.`gc`, 0) gc, IFNULL(b.`cc`, 0) cc FROM `cias_vendedores` a
			LEFT JOIN `cias_vendedores_img` b ON b.`nombc03`=a.`nombc03`;";
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista los vendedores registrados en 'cias_vendedores' que aún no tienen configuración en 'cias_vendedores_img'
	 */
	public function listarVend() {
		$sql = "SELECT a.agent03, a.nombl03, a.nombc03, a.apart03, a.direc03 FROM `cias_vendedores` a
			LEFT JOIN `cias_vendedores_img` b ON b.`nombc03`=a.`nombc03`
			WHERE b.nombc03 IS NULL;";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene el listado de agencias/direcciones únicas asociadas a los vendedores
	 */
	public function agencias() {
		$sql = "SELECT DISTINCT(direc03) agencia FROM `cias_vendedores` ORDER BY direc03";
		return ejecutarConsulta($sql);
	}

}
?>
