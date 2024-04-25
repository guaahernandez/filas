<?php 
require_once "../models/m_videos.php";
require_once "../models/ws.php";
if (strlen(session_id())<1) 
	session_start();

	// ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
	
$model=new Videos();

$agenci=isset($_POST["agenci"])? limpiarCadena($_POST["agenci"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$direcc=isset($_POST["direcc"])? limpiarCadena($_POST["direcc"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";
$descri=isset($_POST["descri"])? limpiarCadena($_POST["descri"]):"";
$format=isset($_POST["format"])? limpiarCadena($_POST["format"]):"";
$usuari="";//$_SESSION["usuari"];
$cias=Array();
switch ($_GET["op"]) {
	case 'guardaryeditar':

        //if (!file_exists($_FILES['direcc']['tmp_name'])|| !is_uploaded_file($_FILES['direcc']['tmp_name']))
		//echo 'Video actual:'. $_POST["videoa"];
		//echo '<br>Video nuevo:'. $_FILES["direcc"]["name"];
		if($_POST["videoa"]!='')  
		{
			$video=$_POST["videoa"];
		}else
		{
			try {
				$ext=explode(".", $_FILES["direcc"]["name"]);
				//echo '<br>Video Tipo:'. $_FILES['direcc']['type'];
				if ($_FILES['direcc']['type'] == "video/mp4")
				{
					$video = round(microtime(true)).'.'. end($ext);
					//echo '<br>'.$video;
					if(move_uploaded_file($_FILES["direcc"]["tmp_name"], "../assets/videos/" . $video)){
						//echo "<P>FILE UPLOADED TO: ../assets/videos/ $video</P>";
					}else{
						echo "<P>MOVE UPLOADED FILE FAILED!</P>";
      					print_r(error_get_last());
					}

					// $uploads_dir = "../assets/videos/" . $video;
					// echo '<br>subir a:'.$uploads_dir.'\n';
					// foreach ($_FILES["direcc"]["error"] as $key => $error) {
					// 	if ($error == UPLOAD_ERR_OK) {
					// 		$tmp_name = $_FILES["direcc"]["tmp_name"][$key];
					// 		//$name = $_FILES["direcc"]["name"][$key];
					// 		move_uploaded_file($tmp_name, "$uploads_dir");
					// 	}else{
					// 		echo $error;
					// 	}
					// }
				}
			} catch (Exception $e) {
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			}
			
		}

        if (empty($codigo)) {
            $rspta=$model->insertar($agenci, $video, $descri, $format);
            //echo $rspta ? "Datos registrad correctamente" : "No se pudo registrar los datos";
        }else{
            $rspta=$model->editar($agenci, $codigo, $video, $descri, $format);
            //echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
		break;
	

	case 'desactivar':
		$rspta=$model->desactivar($codigo);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$model->activar($codigo);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;

	case 'eliminar':
		$rspta=$model->eliminar($codigo);
		echo $rspta ? "Dato eliminado correctamente" : "No se pudo eliminar el dato";
		break;
	
	case 'mostrar':
		$rspta=$model->mostrar($codigo);
		echo json_encode($rspta);
		break;

    case 'listar':
        $cias=$_SESSION["cias"];
		$rspta=$model->listar();
		$data=Array();
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
			"0"=>($reg->estado)
			?'<button class="btn btn-info btn-sm" onclick="mostrar('.$reg->codigo.')"><i class="fa fa-edit"></i></button>'.' 
			'.'<button class="btn btn-warning btn-sm" onclick="desactivar('.$reg->codigo.')"><i class="fa fa-lock"></i></button>'.'
			'.'<button class="btn btn-danger btn-sm" onclick="eliminar('.$reg->codigo.')"><i class="fa fa-trash"></i></button>'
			:'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->codigo.')"><i class="fa fa-edit"></i></button>'.' 
			'.'<button class="btn btn-success btn-sm" onclick="activar('.$reg->codigo.')"><i class="fa fa-unlock"></i></button>'.'
			'.'<button class="btn btn-danger btn-sm" onclick="eliminar('.$reg->codigo.')"><i class="fa fa-trash"></i></button>',
			"1"=>$reg->codigo,
            "2"=>$cias[$reg->agenci],
			"3"=>$reg->direcc,
            "4"=>$reg->descri,			
            "5"=>'<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#videomodal" onclick="getdirecc('.$reg->codigo.')"><i class="mdi mdi-eye"></i></button>',
            "6"=>$reg->fechac,
			"7"=>$reg->format
			);
		}       
        
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);   
		break;

		case 'selectDepartamento':
			$rspta=$model->select();
			echo '<option value="">seleccione...</option>';
			while ($reg=$rspta->fetch_object()) {
				echo '<option value=' . $reg->codigo.'>'.$reg->nombre.'</option>';
			}
			break;

        case 'getdirecc':
            $rspta=$model->getdirecc($codigo);
		    echo $rspta["direcc"];
            break;

        case 'getcias':
            
            $cias["00"] = " - GLOBAL -";
            $response = getcias();
		    $data = json_decode($response, true);
            //print_r($data[0]);
		    echo '<option value="">seleccione...</option>';
            echo '<option value="00"> - GLOBAL - </option>';
			foreach($data as $dat) {
                echo '<option value=' .$dat["codigo"].'>'.$dat["nombre"].'</option>';
                $cias[$dat["codigo"]]=$dat["nombre"];
            }
			$_SESSION["cias"] = $cias;
            break;
}
 ?>