<?php 
require_once "../models/m_videos.php";
require_once "../models/ws.php";
if (strlen(session_id())<1) 
	session_start();

$model=new Videos();

$agenci=isset($_POST["agenci"])? limpiarCadena($_POST["agenci"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$direcc=isset($_POST["direcc"])? limpiarCadena($_POST["direcc"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";
$descri=isset($_POST["descri"])? limpiarCadena($_POST["descri"]):"";
$usuari="";//$_SESSION["usuari"];
$cias=Array();
switch ($_GET["op"]) {
	case 'guardaryeditar':

        if (!file_exists($_FILES['direcc']['tmp_name'])|| !is_uploaded_file($_FILES['direcc']['tmp_name']))  
		{
			$video=$_POST["videoa"];
		}else
		{

			$ext=explode(".", $_FILES["direcc"]["name"]);
			if ($_FILES['direcc']['type'] == "video/mp4" || $_FILES['direcc']['type'] == "image/jpeg")
			 {

			   $video = round(microtime(true)).'.'. end($ext);
				move_uploaded_file($_FILES["direcc"]["tmp_name"], "../assets/videos/" . $video);
		 	}
		}

        if (empty($codigo)) {
            $rspta=$model->insertar($agenci,$video,$usuari,$descri);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        }else{
            $rspta=$model->editar($agenci, $codigo, $video, $usuari, $estado,$descri);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
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
			?'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->codigo.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-danger btn-sm" onclick="desactivar('.$reg->codigo.')"><i class="fa fa-lock"></i></button>'
			:'<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->codigo.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-success btn-sm" onclick="activar('.$reg->codigo.')"><i class="fa fa-unlock"></i></button>',
			"1"=>$reg->codigo,
            "2"=>$cias[$reg->agenci],
			"3"=>$reg->direcc,
            "4"=>$reg->descri,			
            "5"=>'<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#videomodal" onclick="getdirecc('.$reg->codigo.')"><i class="mdi mdi-eye"></i></button>',
            "6"=>$reg->fechac
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