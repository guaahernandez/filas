<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Departamentos (a_departamento.php)
 * ============================================================================
 * Este controlador procesa las peticiones CRUD asíncronas para el catálogo
 * de departamentos / áreas de la empresa.
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'guardaryeditar'    : Inserta un nuevo departamento o actualiza uno existente.
 * - 'desactivar'        : Cambia el estado del departamento a inactivo.
 * - 'activar'           : Cambia el estado del departamento a activo.
 * - 'mostrar'           : Retorna los datos de un departamento específico en formato JSON.
 * - 'listar'            : Retorna los registros estructurados para DataTables (jQuery).
 * - 'selectDepartamento': Genera las opciones HTML (<option>) para elementos <select>.
 * ============================================================================
 */

// Inclusión del modelo Departamento
require_once "../models/Departamento.php";

// Inicialización de la sesión PHP si no está activa
if (strlen(session_id()) < 1) {
	session_start();
}

// Instanciación del modelo
$departamento = new Departamento();

// Sanitización de parámetros recibidos por POST
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$idusuario = isset($_SESSION["idusuario"]) ? $_SESSION["idusuario"] : "";

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Inserta o actualiza un registro según si existe o no el iddepartamento
	case 'guardaryeditar':
        if (empty($iddepartamento)) {
            $rspta = $departamento->insertar($nombre, $descripcion, $idusuario);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $departamento->editar($iddepartamento, $nombre, $descripcion, $idusuario);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
		break;

    // Desactiva el departamento
	case 'desactivar':
		$rspta = $departamento->desactivar($iddepartamento);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;

    // Activa el departamento
	case 'activar':
		$rspta = $departamento->activar($iddepartamento);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
    // Obtiene los datos de una fila para edición
	case 'mostrar':
		$rspta = $departamento->mostrar($iddepartamento);
		echo json_encode($rspta);
		break;

    // Lista los registros formateados para DataTables con botones de acción (editar/activar/desactivar)
    case 'listar':
		$rspta = $departamento->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
			$data[] = array(
                // Botones de acción dinámicos según el estado del registro
                "0" => ($reg->estado)
                    ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->iddepartamento . ')"><i class="fa fa-pencil"></i></button> ' .
                      '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->iddepartamento . ')"><i class="fa fa-close"></i></button>'
                    : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->iddepartamento . ')"><i class="fa fa-pencil"></i></button> ' .
                      '<button class="btn btn-danger btn-xs" onclick="activar(' . $reg->iddepartamento . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => $reg->fechacreada
			);
		}
		$results = array(
            "sEcho" => 1, // Información de control para DataTables
            "iTotalRecords" => count($data), // Total de registros
            "iTotalDisplayRecords" => count($data), // Total de registros a visualizar
            "aaData" => $data // Datos del cuerpo de la tabla
        ); 
		echo json_encode($results);   
		break;

    // Genera opciones para listas desplegables
	case 'selectDepartamento':
		$rspta = $departamento->select();
		echo '<option value="">seleccione...</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . $reg->iddepartamento . '>' . $reg->nombre . '</option>';
		}
		break;
}
?>