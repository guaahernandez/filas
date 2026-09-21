<?php
/**
 * ============================================================================
 * Sistema de Control de Filas - Web Service de Resumen de Turnos (turnos.php)
 * ============================================================================
 * Endpoint API REST para la consulta consolidada de turnos y ventas por vendedor.
 * 
 * Parámetros (GET / POST):
 * - fecha_inicio (obligatorio): Fecha inicial del rango (formato YYYY-MM-DD).
 * - fecha_fin    (obligatorio): Fecha final del rango (formato YYYY-MM-DD, máx. 1 mes de diferencia).
 * - sede         (opcional)   : Código de la sucursal/sede.
 * 
 * Respuesta:
 * - JSON con el resumen por agente (turnos totales, turnos con monto, ventas netas y tiempo promedio).
 * ============================================================================
 */

// Configuración de encabezados CORS para permitir consumo desde aplicaciones externas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json; charset=utf-8');

// Manejo de la solicitud preliminar OPTIONS (Pre-flight CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "../config/Conexion.php";

// Captura y sanitización de parámetros por GET o POST
$fecha_inicio = isset($_REQUEST['fecha_inicio']) ? limpiarCadena($_REQUEST['fecha_inicio']) : '';
$fecha_fin = isset($_REQUEST['fecha_fin']) ? limpiarCadena($_REQUEST['fecha_fin']) : '';
$sede = isset($_REQUEST['sede']) ? limpiarCadena($_REQUEST['sede']) : '';

// 1. Validación de presencia de fechas obligatorias
if (empty($fecha_inicio) || empty($fecha_fin)) {
    echo json_encode([
        "status" => "error", 
        "message" => "Los parámetros fecha_inicio y fecha_fin son requeridos (formato YYYY-MM-DD)."
    ]);
    exit();
}

// 2. Validación de coherencia cronológica
if (strtotime($fecha_fin) < strtotime($fecha_inicio)) {
    echo json_encode([
        "status" => "error", 
        "message" => "La fecha_fin no puede ser menor a la fecha_inicio."
    ]);
    exit();
}

// 3. Validación de rango máximo permitido (1 mes de diferencia)
$fecha_limite = date('Y-m-d', strtotime($fecha_inicio . ' + 1 month'));
if (strtotime($fecha_fin) > strtotime($fecha_limite)) {
    echo json_encode([
        "status" => "error", 
        "message" => "El rango de fechas no puede tener más de un mes de diferencia."
    ]);
    exit();
}

// Filtro condicional por sucursal/sede
$filtro_sede = "";
if (!empty($sede)) {
    $filtro_sede = " AND e.n_sede = '$sede'";
}

// Construcción de la consulta analítica consolidada por agente
$sql = "SELECT DISTINCT s.*, e.descrip AS Sede, fun_nombre_agente(s.agnomb) AS nombre 
FROM (
    SELECT e.n_sede, e.agnomb, COUNT(*) AS TURNOS, IFNULL(SUM(IF(e.`factur`!='',1,0)),0) AS TURNOS_monto 
    , ROUND(SUM(IFNULL(IF(e.factur!='',e.`monto`,0),0))-SUM(IFNULL(n.`monto`,0)), 2) AS Monto
    , ROUND(SUM(e.segund)/60/COUNT(*), 2) AS prom_minutos
    FROM `ticket_enc` e 
    LEFT JOIN notas n ON n.factura = e.factur AND n.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'
    WHERE e.fechac BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
    AND e.destin = 'v' 
    $filtro_sede
    GROUP BY e.agnomb
) AS s 
LEFT JOIN sedes e ON e.sede = s.n_sede
ORDER BY Monto DESC;";

// Ejecución de la consulta en la base de datos
$rs = ejecutarConsulta($sql);

if (!$rs) {
    echo json_encode([
        "status" => "error", 
        "message" => "Ocurrió un error al ejecutar la consulta en la base de datos."
    ]);
    exit();
}

// Recorrido de los registros para estructuración en array
$data = [];
while ($reg = $rs->fetch_assoc()) {
    $data[] = $reg;
}

// Retorno de respuesta en formato JSON
echo json_encode([
    "status" => "success", 
    "registros" => count($data),
    "data" => $data
]);
?>
