<?php 
require_once "../models/m_sedes.php";
if (strlen(session_id())<1) 
	session_start();

$msede=new Sede();

$id=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
$sede=isset($_POST["sede"])? limpiarCadena($_POST["sede"]):"";
$descrip=isset($_POST["descrip"])? limpiarCadena($_POST["descrip"]):"";
$direcci=isset($_POST["direcci"])? limpiarCadena($_POST["direcci"]):"";
$grupo=isset($_POST["grupo"])? limpiarCadena($_POST["grupo"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	// if (empty($iddepartamento)) {
		$rspta=$msede->replace($id, $sede, $descrip, $direcci, $grupo);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	// }else{
    //      $rspta=$departamento->editar($iddepartamento,$nombre,$descripcion,$idusuario);
	// 	echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	// }
		break;
	

	case 'desactivar':
		$rspta=$msede->desactivar($id);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$msede->activar($id);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$msede->mostrar($id);
		echo json_encode($rspta);
		break;

    case 'listar':
		$rspta=$msede->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
			"0"=>($reg->estado)
			?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->id.')"><i class="fa fa-lock"></i></button>'
			:'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->id.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-info btn-xs" onclick="activar('.$reg->id.')"><i class="fa fa-unlock"></i></button>',
			"1"=>$reg->id,
			"2"=>$reg->sede,
			"3"=>$reg->descrip,
            "4"=>$reg->direcci,
            "5"=>$reg->grupo,
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

		case 'selectSede':
			$rspta=$msede->select();
			if($_SESSION["n_sede"]=='0') echo '<option value="0">Todas...</option>';
			while ($reg=$rspta->fetch_object()) {
				if($_SESSION["n_sede"]!='0') {
					if($_SESSION["n_sede"] == $reg->codigo) echo '<option selected value=' . $reg->codigo.'>'.$reg->codigo.' - '.$reg->nombre.'</option>';
				}else{
					echo '<option value=' . $reg->codigo.'>'.$reg->codigo.' - '.$reg->nombre.'</option>';
				}				
			}
			break;
}
 ?>