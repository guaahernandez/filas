<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Textos Informativos (a_textos.php)
 * ============================================================================
 * Este controlador administra los textos y avisos que se despliegan en la
 * marquesina / cinta inferior de las pantallas de sala (pantalla.php).
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'guardaryeditar': Inserta un nuevo mensaje o actualiza el texto existente por agencia.
 * - 'desactivar'    : Desactiva un mensaje.
 * - 'activar'       : Activa un mensaje para su visualización.
 * - 'mostrar'       : Retorna la información de un texto específico en JSON.
 * - 'listar'        : Lista los mensajes formateados para DataTables, cruzando con el catálogo de agencias.
 * - 'gettexto'      : Consulta el texto activo vigente para la marquesina de la pantalla en tiempo real.
 * - 'getcias'       : Consulta las agencias/compañías desde el Web Service para llenar selectores.
 * ============================================================================
 */

// Inclusión de modelos requeridos
require_once "../models/m_textos.php";
require_once "../models/ws.php";

// Inicialización de la sesión PHP si no existe una activa
if (strlen(session_id()) < 1) {
	session_start();
}

// Instanciación del modelo
$model = new Textos();

// Sanitización de parámetros recibidos por POST
$agenci = isset($_POST["agenci"]) ? limpiarCadena($_POST["agenci"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$texto = isset($_POST["texto"]) ? limpiarCadena($_POST["texto"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";

$usuari = ""; // $_SESSION["usuari"];
$cias = array();

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Inserta o actualiza un mensaje según la existencia del código
	case 'guardaryeditar':
        if (empty($codigo)) {
            $rspta = $model->insertar($agenci, $texto, $usuari, $estado);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $model->editar($agenci, $codigo, $texto, $usuari, $estado);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
		break;

    // Desactiva el mensaje de la marquesina
	case 'desactivar':
		$rspta = $model->desactivar($codigo);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;

    // Activa el mensaje de la marquesina
	case 'activar':
		$rspta = $model->activar($codigo);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
    // Obtiene los datos de un mensaje para el formulario de edición
	case 'mostrar':
		$rspta = $model->mostrar($codigo);
		echo json_encode($rspta);
		break;

    // Lista los mensajes configurados para la tabla de administración
    case 'listar':
        $cias = isset($_SESSION["cias"]) ? $_SESSION["cias"] : array();
		$rspta = $model->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
            $nombreAgencia = isset($cias[$reg->agenci]) ? $cias[$reg->agenci] : $reg->agenci;
			$data[] = array(
                "0" => ($reg->estado)
                    ? '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->codigo . ')"><i class="fa fa-edit"></i></button> ' .
                      '<button class="btn btn-danger btn-sm" onclick="desactivar(' . $reg->codigo . ')"><i class="fa fa-lock"></i></button>'
                    : '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->codigo . ')"><i class="fa fa-edit"></i></button> ' .
                      '<button class="btn btn-success btn-sm" onclick="activar(' . $reg->codigo . ')"><i class="fa fa-unlock"></i></button>',
                "1" => $reg->codigo,
                "2" => $nombreAgencia,
                "3" => $reg->texto,            
                "4" => $reg->fechac
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
        
    // Obtiene el texto vigente para proyectar en la marquesina de pantalla.php / visor
    case 'gettexto':
        $rspta = $model->gettexto();
        echo isset($rspta["texto"]) ? $rspta["texto"] : "";
        break;

    // Consulta el catálogo de agencias vía Web Service y construye el selector HTML
    case 'getcias':
        $cias["00"] = " - GLOBAL -";
        $response = getcias();
        $data = json_decode($response, true);
        
        echo '<option value="">seleccione...</option>';
        echo '<option value="00"> - GLOBAL - </option>';
        
        if (is_array($data)) {
            foreach ($data as $dat) {
                echo '<option value=' . $dat["codigo"] . '>' . $dat["nombre"] . '</option>';
                $cias[$dat["codigo"]] = $dat["nombre"];
            }
        }
        $_SESSION["cias"] = $cias;
        break;
}
?>