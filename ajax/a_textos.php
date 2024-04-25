<?php 
require_once "../models/m_textos.php";
require_once "../models/ws.php";
if (strlen(session_id())<1) 
	session_start();

$model=new Textos();

$agenci=isset($_POST["agenci"])? limpiarCadena($_POST["agenci"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$texto=isset($_POST["texto"])? limpiarCadena($_POST["texto"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";

$usuari="";//$_SESSION["usuari"];
$cias=Array();
switch ($_GET["op"]) {
	case 'guardaryeditar':
        if (empty($codigo)) {
            $rspta=$model->insertar($agenci,$texto,$usuari, $estado);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        }else{
            $rspta=$model->editar($agenci, $codigo, $texto, $usuari, $estado);
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
			"3"=>$reg->texto,            
            "4"=>$reg->fechac
			);
		}       
        
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);   
		break;
        
        case 'gettexto':
			$rspta=$model->gettexto();
			echo $rspta["texto"];
            // $rspta=$model->gettexto($_SESSION["ntexto"]);
			// if($rspta){
			// 	echo $rspta["texto"];
			// 	$_SESSION["ntexto"] = $rspta["codigo"];
			// }else{
			// 	$_SESSION["ntexto"] = 0;
			// 	$rspta=$model->gettexto($_SESSION["texto"]);
			// 	echo $rspta["texto"];
			// 	$_SESSION["ntexto"] = $rspta["codigo"];
			// }
		    
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