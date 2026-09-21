<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Menús y Botones de Quiosco (m_menus.php)
 * ============================================================================
 * Este modelo gestiona la estructura dinámica de menús, botones, encabezados
 * y opciones de navegación para los quioscos de autoservicio (opcmenu, opmenu_enc, opmenu_det)
 * y la consulta de vendedores con sesión activa (cias_vendedores, cias_vendedores_img).
 * 
 * Funcionalidades:
 * - CRUD de opciones de menú (opcmenu).
 * - Consulta de configuración de encabezados de menú (opmenu_enc).
 * - Consulta de botones y opciones detalladas por submenú y sede anfitriona (opmenu_det).
 * - Consulta de vendedores activos en el ERP o sistema para selección en quioscos (listar_v).
 * - Enlaces a promociones de la app (getpromo).
 * ============================================================================
 */

// Inclusión del archivo de conexión a la base de datos
require "../config/Conexion.php";

class Menus {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta una nueva opción de menú en la tabla 'opcmenu'
	 * 
	 * @param string $agenci Código de la agencia o '00' para global
	 * @param string $ubmenu Clave del submenú al que pertenece
	 * @param string $codigo Código del botón/trámite
	 * @param string $descri Descripción visible
	 * @param string $nombre Nombre interno
	 * @param int    $orden  Orden de visualización
	 * @return bool
	 */
	public function insertar($agenci, $ubmenu, $codigo, $descri, $nombre, $orden) {
		date_default_timezone_set('America/Costa_Rica');
		$sql = "INSERT INTO opcmenu (agenci, ubmenu, codigo, descri, nombre, orden) VALUES ('$agenci', '$ubmenu', '$codigo', '$descri', '$nombre', $orden)";
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de una opción de menú existente
	 */
	public function editar($agenci, $ubmenu, $codigo, $descri, $nombre, $orden) {
		$sql = "UPDATE opcmenu SET agenci='$agenci', ubmenu='$ubmenu', codigo='$codigo', descri='$descri', nombre='$nombre', orden=$orden  
			WHERE agenci='$agenci' and ubmenu=$ubmenu and codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva una opción de menú
	 */
	public function desactivar($agenci, $ubmenu, $codigo) {
		$sql = "UPDATE opcmenu SET estado=0 WHERE agenci='$agenci' and ubmenu='$ubmenu' and codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa una opción de menú
	 */
	public function activar($agenci, $ubmenu, $codigo) {
		$sql = "UPDATE opcmenu SET estado=1 WHERE agenci='$agenci' and ubmenu='$ubmenu' and codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos de una opción de menú para la sede vinculada a la IP de la sesión
	 */
	public function mostrar($agenci, $ubmenu, $codigo) {
		$sql = "SELECT * FROM opcmenu WHERE agenci=(SELECT n_sede FROM mhosts WHERE dir_ip='" . $_SESSION["ip"] . "') and ubmenu='$ubmenu' and codigo='$codigo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Lista las opciones de menú activas filtradas por sede del anfitrión e identificador de submenú
	 * 
	 * @param string $ubmenu Clave del submenú
	 * @return resource|mysqli_result
	 */
	public function listar($ubmenu) {
		$sql = "SELECT * FROM opcmenu WHERE agenci in ('00', (SELECT n_sede FROM mhosts WHERE dir_ip='" . $_SESSION["ip"] . "')) and ubmenu='$ubmenu' and estado=1 order by ordenm";
		//echo $sql;
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene la configuración de encabezado, título, subtítulo y tipo de botón de un menú
	 * 
	 * @param string $nombre Nombre identificador del menú
	 * @return array
	 */
	public function opmenu_enc($nombre) {
		$sql = "SELECT e.*, d.`codigo` FROM opmenu_enc e 
			LEFT JOIN `opmenu_det` d ON d.`agenci` IN ('00', (SELECT n_sede FROM mhosts WHERE dir_ip='" . $_SESSION["ip"] . "')) AND d.`submen`=e.`nombre`
			WHERE e.agenci IN ('00', (SELECT n_sede FROM mhosts WHERE dir_ip='" . $_SESSION["ip"] . "')) AND e.nombre='$nombre' AND e.estado=1 ORDER BY e.`agenci` LIMIT 1;";
		//echo $sql;
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Consulta los botones y opciones detalladas para renderizar en el quiosco
	 * 
	 * @param string $ubmenu Clave del submenú
	 * @return resource|mysqli_result
	 */
	public function opmenu_det($ubmenu) {
		// Ajuste de filtro para quiosco central
		$agenciaglobal = '00';
		if (isset($_SESSION["ip"]) && ($_SESSION["ip"] == 'QUIOSCO01' || $_SESSION["ip"] == 'QUIOSCO_SED')) {
			$agenciaglobal = '';
		}
		$sql = "SELECT * FROM opmenu_det WHERE agenci in ('" . $agenciaglobal . "', (SELECT n_sede FROM mhosts WHERE dir_ip='" . $_SESSION["ip"] . "')) and ubmenu='$ubmenu' and estado=1 order by ordenm";
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista los vendedores que tienen sesión iniciada (logueados en ERP o App) para la sede activa
	 * 
	 * @param string $agenci Código de la sede
	 * @param string $nombre Filtro de categoría (ej: 'guacaclub')
	 * @return resource|mysqli_result
	 */
	public function listar_v($agenci, $nombre) {
		$sql = "SELECT DISTINCT i.`nombc03`, IFNULL(i.`nombl03`, b.`nombl03`) nombl03, IF(IFNULL(i.imagen,'')='', 'guaca.jpg', i.imagen) imagen, IFNULL(i.`activo`, 1) activ, IFNULL(i.gc, 0) gc, b.local FROM 
			`cias_vendedores` b
			INNER JOIN `cias_vendedores_img` i ON i.`agente`=b.`agent03` 
			AND ((i.`fechlog` > DATE_FORMAT(NOW(),'%Y-%m-%d') AND i.logue03=1) OR (i.`fechlogerp` > DATE_FORMAT(NOW(),'%Y-%m-%d') and i.logerp=1))
			WHERE i.sucur03=(SELECT n_sede FROM mhosts WHERE dir_ip='" . $_SESSION["ip"] . "')";
		$sql .= " HAVING activ=1";
		$sql .= ($nombre == 'guacaclub') ? " and gc=1" : " and b.local=0";	
		//echo $sql;
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista las opciones de menú activas
	 */
	public function select() {
		$sql = "SELECT * FROM opcmenu where estado = 1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene la dirección o enlace de una opción por su código
	 */
	public function getdirecc($codigo) {
		$sql = "SELECT direcc FROM opcmenu where codigo='$codigo'";		
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Consulta el enlace activo de promociones de la base de datos de Guaca Club
	 */
	public function getpromo() {
		$sql = "SELECT link FROM app_guacaclub.applinks where descripcion='Promociones'";		
		return ejecutarConsultaSimpleFila($sql);
	}

}
?>
