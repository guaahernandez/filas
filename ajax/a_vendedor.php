<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Vendedores (a_vendedor.php)
 * ============================================================================
 * Este controlador gestiona las operaciones CRUD y catálogos de agentes / vendedores
 * de la empresa, incluyendo fotos de perfil, estado de atención, pertenencia a 
 * Guaca Club (gc) y Call Center (cc).
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'guardaryeditar': Inserta un nuevo vendedor o actualiza sus datos, foto y permisos.
 * - 'desactivar'    : Desactiva un vendedor para la selección en quioscos.
 * - 'activar'       : Activa un vendedor.
 * - 'mostrar'       : Retorna la información de un vendedor en JSON.
 * - 'mostrarVend'   : Retorna la información base del vendedor desde el maestro de ventas.
 * - 'listar'        : Lista los vendedores con fotos y badges para DataTables.
 * - 'listarVend'    : Lista los vendedores disponibles para asociar desde modal.
 * - 'selectagencia' : Genera opciones para listas desplegables de agencias.
 * ============================================================================
 */

session_start();
require_once "../models/m_vendedor.php";

// Instanciación del modelo
$model = new Vendedor();

// Sanitización de parámetros recibidos por POST
$agent = isset($_POST["agent"]) ? limpiarCadena($_POST["agent"]) : "";
$nombl = isset($_POST["nombl"]) ? limpiarCadena($_POST["nombl"]) : "";
$nombc = isset($_POST["nombc"]) ? limpiarCadena($_POST["nombc"]) : "";
$apart = isset($_POST["apart"]) ? limpiarCadena($_POST["apart"]) : "";
$direc = isset($_POST["direc"]) ? str_replace('-', ' ', $_POST["direc"]) : "";
$local = isset($_POST["local"]) ? $_POST["local"] : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$activo = isset($_POST["activo"]) ? $_POST["activo"] : "";
$gc = isset($_POST["gc"]) ? $_POST["gc"] : "";
$cc = isset($_POST["cc"]) ? $_POST["cc"] : "";

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Procesa el registro o actualización de un vendedor y la subida de su fotografía
	case 'guardaryeditar':

		// Validación y procesamiento de la imagen del vendedor
		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$imagen = $_POST["imagenactual"];
		} else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
			   $imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/vendedores/" . $imagen);
		 	}
		}

		// Valida si el vendedor ya existe en la base de datos para decidir inserción o edición
		if (!existe("SELECT nombc03 FROM cias_vendedores WHERE nombc03='$nombc'")) {
			$rspta = $model->insertar($agent, $nombl, $nombc, $apart, $direc, $imagen, $gc, $cc);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		} else {
			$rspta = $model->editar($agent, $nombl, $nombc, $apart, $direc, $local, $imagen, $activo, $gc, $cc);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
	break;	

    // Desactiva el vendedor
	case 'desactivar':
		$rspta = $model->desactivar($nombc);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

    // Activa el vendedor
	case 'activar':
		$rspta = $model->activar($nombc);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
    // Obtiene los datos de configuración del vendedor
	case 'mostrar':
		$rspta = $model->mostrar($nombc);
		echo json_encode($rspta);
	break;

    // Obtiene los datos del vendedor desde el maestro
	case 'mostrarVend':
		$rspta = $model->mostrarVend($nombc);
		echo json_encode($rspta);
	break;
	
    // Lista los vendedores con su fotografía y estado para DataTables
	case 'listar':
		$rspta = $model->listar();
		$data = array();
		$i = 0;
		while ($reg = $rspta->fetch_object()) {
			
			$data[$i][0] = ($reg->activo)
                ? '<button class="btn btn-warning btn-xs" onclick="mostrar(\'' . $reg->nombc03 . '\')"><i class="fa fa-edit"></i></button> ' .
                  '<button class="btn btn-danger btn-xs" onclick="desactivar(\'' . $reg->nombc03 . '\')"><i class="fa fa-lock"></i></button>'
                : '<button class="btn btn-warning btn-xs" onclick="mostrar(\'' . $reg->nombc03 . '\')"><i class="fa fa-edit"></i></button> ' .
                  '<button class="btn btn-success btn-xs" onclick="activar(\'' . $reg->nombc03 . '\')"><i class="fa fa-unlock"></i></button>';
			$data[$i][1] = $reg->agent03;
			$data[$i][2] = $reg->nombl03;
			$data[$i][3] = $reg->nombc03;
			$data[$i][4] = ($reg->imagen != '')
                ? "<img src='../assets/vendedores/" . $reg->imagen . "' height='50px' width='50px'>"
                : "<img src='../assets/vendedores/guaca.jpg' height='50px' width='50px'>";
            $data[$i][5] = $reg->apart03;
			$data[$i][6] = $reg->direc03;
			$data[$i][7] = ($reg->activo) ? '<span class="badge bg-success"><strong>Activo</strong></span>' : '<span class="badge bg-danger"><strong>Inactivo</strong></span>';
			$data[$i][8] = ($reg->gc) ? '<span class="badge bg-success"><strong>Si</strong></span>' : '<span class="badge bg-danger"><strong>No</strong></span>';
			$data[$i][9] = ($reg->cc) ? '<span class="badge bg-success"><strong>Si</strong></span>' : '<span class="badge bg-danger"><strong>No</strong></span>';
			$i++;
		}

		$results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ); 
		echo json_encode($results);
	break;

    // Lista vendedores del maestro para selección en modales de asignación
	case 'listarVend':
		$rspta = $model->listarVend();
		$data = array();
		$i = 0;
		while ($reg = $rspta->fetch_object()) {
			$data[$i][0] = '<button class="btn btn-warning btn-xs" data-bs-dismiss="modal" onclick="mostrarVend(\'' . $reg->nombc03 . '\')"><i class="fa fa-plus"></i></button>';
			$data[$i][1] = $reg->agent03;
			$data[$i][2] = $reg->nombl03;
			$data[$i][3] = $reg->nombc03;
            $data[$i][4] = $reg->apart03;
			$data[$i][5] = $reg->direc03;
			$i++;
		}

		$results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ); 
		echo json_encode($results);
	break;

    // Genera el selector de agencias
	case 'selectagencia':
		$rspta = $model->agencias();
		echo '<option value="">seleccione...</option>';
		while ($reg = $rspta->fetch_object()) {
			echo '<option value=' . str_replace(' ', '-', $reg->agencia) . '>' . $reg->agencia . '</option>';
		}
		break;
}
?>