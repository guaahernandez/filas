<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Tipos de Usuario (a_tipousuario.php)
 * ============================================================================
 * Este controlador procesa las peticiones CRUD asíncronas para la administración
 * de roles y tipos de usuario en el sistema.
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'guardaryeditar'   : Registra un nuevo rol o actualiza el nombre y descripción de uno existente.
 * - 'desactivar'       : Desactiva un rol / tipo de usuario.
 * - 'activar'          : Activa un rol / tipo de usuario.
 * - 'mostrar'          : Retorna la información de un tipo de usuario en JSON.
 * - 'listar'           : Entrega el listado formateado para DataTables.
 * - 'selectTipousuario': Genera las opciones HTML para elementos <select>.
 * ============================================================================
 */

// Inclusión del modelo Tipousuario
require_once "../models/Tipousuario.php";

// Inicialización de la sesión PHP si no existe una activa
if (strlen(session_id()) < 1) {
	session_start();
}

// Instanciación del modelo
$tipousuario = new Tipousuario();

// Sanitización de parámetros recibidos por POST
$idtipousuario = isset($_POST["idtipousuario"]) ? limpiarCadena($_POST["idtipousuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$idusuario = isset($_SESSION["idusuario"]) ? $_SESSION["idusuario"] : "";

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Inserta o actualiza según la existencia de $idtipousuario
	case 'guardaryeditar':
        if (empty($idtipousuario)) {
            $rspta = $tipousuario->insertar($nombre, $descripcion, $idusuario);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $tipousuario->editar($idtipousuario, $nombre, $descripcion, $idusuario);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
		break;

    // Desactiva el tipo de usuario
	case 'desactivar':
		$rspta = $tipousuario->desactivar($idtipousuario);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;

    // Activa el tipo de usuario
	case 'activar':
		$rspta = $tipousuario->activar($idtipousuario);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
    // Retorna datos de una fila específica para edición
	case 'mostrar':
		$rspta = $tipousuario->mostrar($idtipousuario);
		echo json_encode($rspta);
		break;

    // Lista los roles/tipos de usuario estructurados para DataTables
    case 'listar':
		$rspta = $tipousuario->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idtipousuario . ')"><i class="fa fa-pencil"></i></button> ' .
                       '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idtipousuario . ')"><i class="fa fa-close"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => $reg->fechacreada
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

    // Construye las opciones para listas desplegables de tipos de usuario
	case 'selectTipousuario':
		$rspta = $tipousuario->select();
		echo '<option value="">seleccione...</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->idtipousuario . '>' . $reg->nombre . '</option>';
		}
		break;
}
?>