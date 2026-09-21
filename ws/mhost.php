<?php
/**
 * ============================================================================
 * Sistema de Control de Filas - Web Service de Configuración de Host (mhost.php)
 * ============================================================================
 * Endpoint API REST que consulta y retorna la configuración de una estación,
 * quiosco o pantalla anfitriona a partir de su dirección IP (tabla 'mhosts').
 * 
 * Parámetros (GET):
 * - ip: Dirección IP o identificador del equipo.
 * 
 * Respuesta:
 * - JSON con los datos del anfitrión (sede, ubicación, etc.) y código HTTP 200 o 404.
 * ============================================================================
 */

try {
    // Inclusión del archivo de conexión a la base de datos
    require_once "../config/Conexion.php";
    
    // Captura de la dirección IP enviada por parámetro GET
    $dir_ip = (isset($_GET['ip'])) ? $_GET['ip'] : '';
    
    // Conexión PDO a la base de datos
    $dbcon = connect($db);

	// Consulta preparada para obtener el registro del host por su IP
	$sql = $dbcon->prepare("SELECT * FROM mhosts WHERE dir_ip = '$dir_ip'");
	$sql->execute();

    // Encabezado de respuesta JSON
	header('Content-type: application/json');

	if ($sql) {
		header('HTTP/1.1 200 OK');
		echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
	} else {
		header('HTTP/1.1 404 Not Found');
		echo json_encode(array('Null' => '404'));
	}
	$sql->closeCursor();
	
} catch (PDOException $ex) {
	die($ex->getMessage());
}
?>