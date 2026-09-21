<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Videos Multimedia (a_videos.php)
 * ============================================================================
 * Este controlador gestiona el catálogo de videos institucionales y publicitarios
 * reproducidos en las pantallas de sala de espera y quioscos interactivos.
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'guardaryeditar'    : Sube archivos de video MP4, renombra con microtime y guarda/edita registros.
 * - 'desactivar'        : Desactiva un video de la playlist.
 * - 'activar'           : Activa un video en la playlist.
 * - 'eliminar'          : Elimina permanentemente el registro del video.
 * - 'mostrar'           : Retorna la información de un video en JSON.
 * - 'listar'            : Entrega el listado de videos con botones de acción y vista previa para DataTables.
 * - 'getdirecc'         : Obtiene la ruta física del archivo multimedia para el modal de vista previa.
 * - 'getcias'           : Consulta el listado de agencias vía Web Service para el selector.
 * ============================================================================
 */

require_once "../models/m_videos.php";
require_once "../models/ws.php";

// Inicialización de la sesión PHP si no existe una activa
if (strlen(session_id()) < 1) {
	session_start();
}

// Instanciación del modelo
$model = new Videos();

// Sanitización de parámetros recibidos por POST
$agenci = isset($_POST["agenci"]) ? limpiarCadena($_POST["agenci"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$direcc = isset($_POST["direcc"]) ? limpiarCadena($_POST["direcc"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";
$descri = isset($_POST["descri"]) ? limpiarCadena($_POST["descri"]) : "";
$format = isset($_POST["format"]) ? limpiarCadena($_POST["format"]) : "";
$usuari = ""; // $_SESSION["usuari"];
$cias = array();

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Procesa el alta o edición de un video multimedia con subida de archivo .mp4
	case 'guardaryeditar':
		if (isset($_POST["videoa"]) && $_POST["videoa"] != '') {
            // Mantiene el video actual si no se seleccionó un nuevo archivo
			$video = $_POST["videoa"];
		} else {
			try {
				$ext = explode(".", $_FILES["direcc"]["name"]);
				if ($_FILES['direcc']['type'] == "video/mp4") {
					$video = round(microtime(true)) . '.' . end($ext);
					if (move_uploaded_file($_FILES["direcc"]["tmp_name"], "../assets/videos/" . $video)) {
						// Archivo subido exitosamente
					} else {
						echo "<P>Error al mover el archivo de video subido</P>";
      					print_r(error_get_last());
					}
				}
			} catch (Exception $e) {
				echo 'Excepción capturada: ', $e->getMessage(), "\n";
			}
		}

        if (empty($codigo)) {
            $rspta = $model->insertar($agenci, $video, $descri, $format);
        } else {
            $rspta = $model->editar($agenci, $codigo, $video, $descri, $format);
        }
		break;

    // Desactiva el video
	case 'desactivar':
		$rspta = $model->desactivar($codigo);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;

    // Activa el video
	case 'activar':
		$rspta = $model->activar($codigo);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;

    // Elimina el registro del video
	case 'eliminar':
		$rspta = $model->eliminar($codigo);
		echo $rspta ? "Dato eliminado correctamente" : "No se pudo eliminar el dato";
		break;
	
    // Obtiene los datos del video para el formulario
	case 'mostrar':
		$rspta = $model->mostrar($codigo);
		echo json_encode($rspta);
		break;

    // Lista los videos configurados con botón para previsualizar en modal
    case 'listar':
        $cias = isset($_SESSION["cias"]) ? $_SESSION["cias"] : array();
		$rspta = $model->listar();
		$data = array();

		while ($reg = $rspta->fetch_object()) {
            $nombreAgencia = isset($cias[$reg->agenci]) ? $cias[$reg->agenci] : $reg->agenci;
			$data[] = array(
                "0" => ($reg->estado)
                    ? '<button class="btn btn-info btn-sm" onclick="mostrar(' . $reg->codigo . ')"><i class="fa fa-edit"></i></button> ' .
                      '<button class="btn btn-warning btn-sm" onclick="desactivar(' . $reg->codigo . ')"><i class="fa fa-lock"></i></button> ' .
                      '<button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->codigo . ')"><i class="fa fa-trash"></i></button>'
                    : '<button class="btn btn-warning btn-sm" onclick="mostrar(' . $reg->codigo . ')"><i class="fa fa-edit"></i></button> ' .
                      '<button class="btn btn-success btn-sm" onclick="activar(' . $reg->codigo . ')"><i class="fa fa-unlock"></i></button> ' .
                      '<button class="btn btn-danger btn-sm" onclick="eliminar(' . $reg->codigo . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->codigo,
                "2" => $nombreAgencia,
                "3" => $reg->direcc,
                "4" => $reg->descri,			
                "5" => '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#videomodal" onclick="getdirecc(' . $reg->codigo . ')"><i class="mdi mdi-eye"></i></button>',
                "6" => $reg->fechac,
                "7" => $reg->format
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

    // Obtiene la ruta del video para reproducirlo en el modal de previsualización
    case 'getdirecc':
        $rspta = $model->getdirecc($codigo);
        echo isset($rspta["direcc"]) ? $rspta["direcc"] : "";
        break;

    // Consulta el listado de agencias para el selector
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