<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Sedes / Sucursales (a_sedes.php)
 * ============================================================================
 * Este controlador administra las operaciones asíncronas para el catálogo de
 * sedes / sucursales de la empresa.
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'guardaryeditar': Registra o reemplaza los datos de una sede (ID, código, descripción, dirección, grupo).
 * - 'desactivar'    : Bloquea / desactiva la sede.
 * - 'activar'       : Desbloquea / activa la sede.
 * - 'mostrar'       : Retorna la información de una sede en JSON.
 * - 'listar'        : Entrega el listado formateado para DataTables.
 * - 'selectSede'    : Genera las opciones HTML para elementos <select>, filtrando según los permisos de sesión.
 * ============================================================================
 */

// Inclusión del modelo Sede
require_once "../models/m_sedes.php";

// Inicialización de la sesión PHP si no existe una activa
if (strlen(session_id()) < 1) {
	session_start();
}

// Instanciación del modelo
$msede = new Sede();

// Sanitización de parámetros recibidos por POST
$id = isset($_POST["id"]) ? limpiarCadena($_POST["id"]) : "";
$sede = isset($_POST["sede"]) ? limpiarCadena($_POST["sede"]) : "";
$descrip = isset($_POST["descrip"]) ? limpiarCadena($_POST["descrip"]) : "";
$direcci = isset($_POST["direcci"]) ? limpiarCadena($_POST["direcci"]) : "";
$grupo = isset($_POST["grupo"]) ? limpiarCadena($_POST["grupo"]) : "";

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Inserta o reemplaza una sede mediante la función replace del modelo
	case 'guardaryeditar':
		$rspta = $msede->replace($id, $sede, $descrip, $direcci, $grupo);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		break;

    // Desactiva la sede (bloqueo)
	case 'desactivar':
		$rspta = $msede->desactivar($id);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;

    // Activa la sede (desbloqueo)
	case 'activar':
		$rspta = $msede->activar($id);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
    // Obtiene los datos de una sede específica para edición
	case 'mostrar':
		$rspta = $msede->mostrar($id);
		echo json_encode($rspta);
		break;

    // Retorna la lista de sedes con botones de acción para DataTables
    case 'listar':
		$rspta = $msede->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
                "0" => ($reg->estado)
                    ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-edit"></i></button> ' .
                      '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->id . ')"><i class="fa fa-lock"></i></button>'
                    : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id . ')"><i class="fa fa-edit"></i></button> ' .
                      '<button class="btn btn-info btn-xs" onclick="activar(' . $reg->id . ')"><i class="fa fa-unlock"></i></button>',
                "1" => $reg->id,
                "2" => $reg->sede,
                "3" => $reg->descrip,
                "4" => $reg->direcci,
                "5" => $reg->grupo,
                "6" => $reg->fechac
			);
		}
		$results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ); 
		echo json_encode($results);   
		break;

    // Genera el listado para selector HTML considerando permisos por sede del usuario en sesión
	case 'selectSede':
		$rspta = $msede->select();
		if ($_SESSION["n_sede"] == '0') {
            echo '<option value="0">Todas...</option>';
        }
		while ($reg = $rspta->fetch_object()) {
			if ($_SESSION["n_sede"] != '0') {
				if ($_SESSION["n_sede"] == $reg->codigo) {
                    echo '<option selected value=' . $reg->codigo . '>' . $reg->codigo . ' - ' . $reg->nombre . '</option>';
                }
			} else {
				echo '<option value=' . $reg->codigo . '>' . $reg->codigo . ' - ' . $reg->nombre . '</option>';
			}				
		}
		break;
}
?>