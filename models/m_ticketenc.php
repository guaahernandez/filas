<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Modelo de Tiquetes y Turnos (m_ticketenc.php)
 * ============================================================================
 * Este modelo gestiona todas las operaciones de base de datos relacionadas con:
 * 1. Emisión y registro de encabezados de tiquetes (tabla 'ticket_enc').
 * 2. Registro de trazabilidad, detalles y llamados a ventanilla (tabla 'ticket_det').
 * 3. Control de consecutivos diarios por sucursal y tipo de trámite (tabla 'consecutivos').
 * 4. Integración de facturas para entrega y despacho de repuestos (tabla 'factura_entrega').
 * 5. Consultas en tiempo real para pantallas de sala de espera y formato de impresión.
 * ============================================================================
 */

// Inclusión del archivo que contiene la conexión a la base de datos y funciones de consulta
require "../config/Conexion.php";

class TicketEnc {

	/**
	 * Constructor de la clase
	 */
	public function __construct() {

	}

	/**
	 * Inserta un nuevo encabezado de tiquete (turno) en la tabla 'ticket_enc'
	 * 
	 * @param string  $agenci  Código de la sucursal/sede
	 * @param string  $ticket  Número o código visible del tiquete (ej: V-01, E-05)
	 * @param string  $vended  Código o nombre del vendedor asignado (si aplica)
	 * @param int     $prefer  Indicador de atención preferencial (1: Sí, 0: No)
	 * @param string  $destin  Destino o departamento del trámite (Ventas, Entregas, Cajas, etc.)
	 * @param string  $tipoco  Tipo de código de trámite (ej: PV, PE, PC, PGC, PCC)
	 * @param string  $factur  Número de factura vinculada (opcional)
	 * @param string  $tidoc   Tipo de documento (opcional)
	 * @param string  $monto   Monto de la transacción (opcional)
	 * @return int|bool Retorna el ID autoincremental generado para el nuevo registro
	 */
	public function insertar($agenci, $ticket, $vended, $prefer, $destin, $tipoco, $factur = "", $tidoc = "", $monto = "") {
		// Establece la zona horaria oficial
		date_default_timezone_set('America/Costa_Rica');

		// Normaliza los prefijos de menús a sus códigos cortos de destino
		if ($tipoco == "PV")  $tipoco = "V";  // Ventas
		if ($tipoco == "PE")  $tipoco = "E";  // Entregas / Despacho
		if ($tipoco == "PC")  $tipoco = "C";  // Cajas
		if ($tipoco == "PGC") $tipoco = "GC"; // Guaca Club
		if ($tipoco == "PCC") $tipoco = "CC"; // Call Center
		if ($tipoco == "AV") $tipoco = "V"; // BUSCA VENDEDOR
		if ($tipoco == "PAV") $tipoco = "V"; // BUSCA VENDEDOR PREFERENCIAL

		$sql = "INSERT INTO ticket_enc (n_sede, ticket, vended, prefer, destin, fechac, horenv, factur, monto, tidoc) VALUES (
			'$agenci', '$ticket', '$vended', $prefer, '$tipoco', NOW(), NOW(), '$factur', $monto, '$tidoc')";
		
		return ejecutarConsulta_retornarID($sql);
	}

	/**
	 * Inserta un registro de detalle/evento para el tiquete (llamado a estación, reasignación, etc.)
	 * 
	 * @param string $agenci   Código de la sucursal
	 * @param string $ticket   Código del tiquete
	 * @param int    $segund   Segundos transcurridos en atención
	 * @param string $ubicac   Ubicación o módulo de atención
	 * @param string $destin   Destino del trámite
	 * @param int    $codigt   ID del encabezado (ticket_enc.id)
	 * @param string $prefac   Prefactura (si aplica)
	 * @param string $factur   Número de factura
	 * @param float  $montof   Monto facturado
	 * @param int    $estacion Número de estación o módulo que atiende
	 * @return bool
	 */
	public function insertardet($agenci, $ticket, $segund, $ubicac, $destin, $codigt, $prefac, $factur, $montof, $estacion) {
		$sql = "INSERT INTO ticket_det (n_sede, ticket, fechac, segund, ubicac, destin, codigt, prefac, factur, montof, estacion) VALUES (
			'$agenci', '$ticket', NOW(), '$segund', '$ubicac', '$destin', $codigt, '$prefac', '$factur', $montof, $estacion)";
		
		return ejecutarConsulta($sql);
	}

	/**
	 * Registra o vincula una factura con un tiquete de entrega en la tabla 'factura_entrega'
	 * 
	 * @param int    $codigt Código de transacción / ID de tiquete
	 * @param string $agenci Código de la sucursal
	 * @param string $ticket Número de tiquete
	 * @param string $factur Número de factura
	 * @return bool
	 */
	public function guarda_fact_ticket($codigt, $agenci, $ticket, $factur) {
		$sql = "INSERT INTO `factura_entrega`(`codtrans`, `sucursal`, `destino`, `estado`, `tiquete`, `factura`, `estadot`)";
		$sql .= " VALUES ($codigt, '$agenci', 'E', '3', '" . trim($ticket) . "', '$factur', 'PE');";
		//echo $sql;
		return ejecutarConsulta($sql);
	}

	/**
	 * Actualiza los datos de un encabezado de tiquete existente
	 */
	public function editar($agenci, $codigo, $direcc, $usuari, $estado, $descri) {
		$sql = "UPDATE ticket_enc SET agenci='$agenci', direcc='$direcc', usuari='$usuari', estado=$estado, descri='$descri'  
			WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva un registro de tiquete (estado = 0)
	 */
	public function desactivar($codigo) {
		$sql = "UPDATE ticket_enc SET estado=0 WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa un registro de tiquete (estado = 1)
	 */
	public function activar($codigo) {
		$sql = "UPDATE ticket_enc SET estado=1 WHERE codigo='$codigo'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Desactiva la bandera de llamado sonoro/voz para un turno en la pizarra
	 */
	public function desactivarllamar($consec) {
		$sql = "UPDATE ticket_det SET llamar=0 WHERE consec='$consec'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Activa la bandera de llamado sonoro/voz para reproducir aviso
	 */
	public function activarllamar($consec) {
		$sql = "UPDATE ticket_det SET llamar=1 WHERE consec='$consec'";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene los datos generales de un tiquete por su código
	 */
	public function mostrar($codigo) {
		$sql = "SELECT * FROM ticket_enc WHERE codigo='$codigo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Obtiene la información completa requerida para imprimir el tiquete térmico
	 * (Cruza datos de sedes, opciones de menú, vendedores y detalles asociados)
	 * 
	 * @param int $codigo ID del tiquete (ticket_enc.id)
	 * @return array Datos completos para la plantilla de impresión
	 */
	public function obtener_imprimir($codigo) {
		$sql = "SELECT DISTINCT e.`id`, e.`ticket`, IFNULL(v2.`nombl03`, v.`nombl03`) vended, c.`descrip` agencia, e.`fechac`, d.`vengoa`, td.factur
			FROM `ticket_enc` e
			LEFT JOIN ticket_det td on td.n_sede=e.n_sede and td.codigt=e.id
			LEFT JOIN `sedes` c ON c.`sede`=e.`n_sede`
			LEFT JOIN `opmenu_det` d ON d.`codigo`=e.`destin`
			LEFT JOIN `cias_vendedores_img` v2 ON v2.`nombc03`=e.`vended`
			LEFT JOIN `cias_vendedores` v ON v.`agent03`=v2.`agente`
			WHERE e.`id`='$codigo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	/**
	 * Consulta los últimos 5 turnos llamados del día actual para la pantalla de sala (pantalla.php)
	 * Filtra por la IP del equipo anfitrión (mhosts) y los destinos configurados en la sesión.
	 * 
	 * @return resource|mysqli_result
	 */
	public function listar_det_pant() {
		$destin = str_replace(",", "','", $_SESSION["destin"]);

		// Construye la consulta filtrando por sede y ubicación asociadas a la IP de la pantalla
		$sql = "SELECT t.*, h.n_sede as 'agencia', IFNULL(u.`ver_numero`, 1) ver_numero FROM ticket_det t
			INNER JOIN mhosts h ON h.`n_sede`=t.`n_sede` AND h.`ubicac`=t.`ubicac` and h.`dir_ip`='" . $_SESSION["ip"] . "'";
		// Filtra por los destinos asignados (V=Ventas, E=Entregas, GC=Guaca Club, etc.)
		$sql .= " INNER JOIN ticket_enc e on e.n_sede=t.n_sede and e.id=t.codigt and e.destin IN ('" . $destin . "')";
		$sql .= " LEFT JOIN estacion u ON u.`n_sede`=t.`n_sede` AND t.`estacion`=u.`estacion`";
		$sql .= " WHERE t.`n_sede`=h.`n_sede` AND t.fechac>date(NOW()) and t.estado='1' ORDER BY t.fechac DESC LIMIT 5;";
		//echo $sql;
		return ejecutarConsulta($sql);
	}

	/**
	 * Consulta las últimas 8 facturas en proceso/terminadas para la pantalla de entregas (pantentregas.php)
	 * 
	 * @return resource|mysqli_result
	 */
	public function l_pant_entreg() {
		$sql = "SELECT t.*, h.n_sede AS 'agencia', e.factur factura
			, IF(IFNULL((SELECT 1 FROM ticket_det WHERE codigt=t.`codigt` AND ubicac=9 limit 1), '')='', 'PROCESO', 'TERMINADO') estadof 
			FROM ticket_det t
			INNER JOIN mhosts h ON h.`n_sede`=t.`n_sede` AND h.`ubicac`=t.`ubicac` and h.`dir_ip`='" . $_SESSION["ip"] . "'
			LEFT JOIN `ticket_enc` e ON e.`id`=t.`codigt`
			WHERE t.fechac>date(NOW()) AND t.estado='1' ORDER BY t.fechac DESC LIMIT 8;";
		
		return ejecutarConsulta($sql);
	}

	/**
	 * Lista todos los tiquetes con estado activo
	 */
	public function select() {
		$sql = "SELECT * FROM ticket_enc where estado = 1";
		return ejecutarConsulta($sql);
	}

	/**
	 * Obtiene y actualiza el consecutivo numérico diario para un tipo de trámite y sucursal.
	 * Si el consecutivo es de un día anterior, reinicia el contador a 1; de lo contrario, lo incrementa.
	 * 
	 * @param string $agenci Código de la sucursal
	 * @param string $tipoco Tipo de trámite / código (ej: V, E, C)
	 * @return int Número correlativo asignado
	 */
	public function getconsec($agenci, $tipoco) {
		$conse = "";
		// Verifica si el consecutivo actual pertenece a una fecha anterior
		$sql = "SELECT IF(fechac<DATE(NOW()), 0, consec) consec FROM consecutivos where agenci='$agenci' and tipoco='$tipoco'";		
		$conse = ejecutarConsultaConsec($sql);
		
		if ($conse == '') {
			// Si no existe registro previo para este tipo y agencia, crea el consecutivo inicial en 1
			$sql = "INSERT INTO consecutivos(agenci, tipoco, consec, fechac) VALUES ('$agenci', '$tipoco', 1, NOW())";
			ejecutarConsulta($sql);
			$conse = 1;
		} else {
			// Si ya existe, incrementa el valor en 1 y actualiza la fecha
			$conse++;
			$sql = "UPDATE consecutivos SET consec = $conse, fechac=NOW() WHERE agenci = '$agenci' and tipoco='$tipoco'";
			ejecutarConsulta($sql);
		}
		return $conse;
	}

	/**
	 * Obtiene el siguiente registro activo en orden secuencial para la agencia dada
	 */
	public function getdirecc2($agenci, $codigo) {
		$sql = "SELECT direcc, codigo FROM ticket_enc WHERE agenci in ('00', '$agenci') and codigo>$codigo and estado=1 order by codigo limit 1";
		return ejecutarConsultaSimpleFila($sql);
	}

}

?>
