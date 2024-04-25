<?php 
session_start();
require_once "../models/m_vendedor.php";

$model=new Vendedor();

$agent=isset($_POST["agent"])? limpiarCadena($_POST["agent"]):"";
$nombl=isset($_POST["nombl"])? limpiarCadena($_POST["nombl"]):"";
$nombc=isset($_POST["nombc"])? limpiarCadena($_POST["nombc"]):"";
$apart=isset($_POST["apart"])? limpiarCadena($_POST["apart"]):"";
$direc=isset($_POST["direc"])? str_replace('-',' ',$_POST["direc"]):"";
$local=isset($_POST["local"])? $_POST["local"]:"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$activo=isset($_POST["activo"])? $_POST["activo"]:"";
$gc=isset($_POST["gc"])? $_POST["gc"]:"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name'])|| !is_uploaded_file($_FILES['imagen']['tmp_name']))  
		{
			$imagen=$_POST["imagenactual"];
		}else
		{

			$ext=explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			 {

			   $imagen = round(microtime(true)).'.'. end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/vendedores/" . $imagen);
		 	}
		}

		if(!existe("select nombc03 from cias_vendedores where nombc03='$nombc'")){
		// if (empty($nombc)) {
			echo "select nombc03 from cias_vendedores where nombc03='$nombc'";
			// $rspta=$model->insertar($agent, $nombl, $nombc, $apart, $direc, $imagen, $gc);
			// echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		}
		else {
			$rspta=$model->editar($agent, $nombl, $nombc, $apart, $direc, $local, $imagen, $activo, $gc);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
	break;	

	case 'desactivar':
		$rspta=$model->desactivar($nombc);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
		$rspta=$model->activar($nombc);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
		$rspta=$model->mostrar($nombc);
		echo json_encode($rspta);
	break;

	case 'mostrarVend':
		$rspta=$model->mostrarVend($nombc);
		echo json_encode($rspta);
	break;
	
	case 'listar':
		$rspta=$model->listar();
		//declaramos un array
		$data=Array();
		$i = 0;
		while ($reg=$rspta->fetch_object()) {
			
			$data[$i][0]=($reg->activo)
			?'<button class="btn btn-warning btn-xs" onclick="mostrar(\''.$reg->nombc03.'\')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar(\''.$reg->nombc03.'\')"><i class="fa fa-lock"></i></button>'
			:'<button class="btn btn-warning btn-xs" onclick="mostrar(\''.$reg->nombc03.'\')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-success btn-xs" onclick="activar(\''.$reg->nombc03.'\')"><i class="fa fa-unlock"></i></button>';
			$data[$i][1]=$reg->agent03;
			$data[$i][2]=$reg->nombl03;
			$data[$i][3]=$reg->nombc03;
			$data[$i][4]=($reg->imagen!='')
			?"<img src='../assets/vendedores/".$reg->imagen."' height='50px' width='50px'>"
			:"<img src='../assets/vendedores/guaca.jpg' height='50px' width='50px'>";
            $data[$i][5]=$reg->apart03;
			$data[$i][6]=$reg->direc03;
			$data[$i][7]=($reg->activo)?'<span class="badge bg-success"><strong>Activo</strong></span>':'<span class="badge bg-danger"><strong>Inactivo</strong></span>';
			$data[$i][8]=($reg->gc)?'<span class="badge bg-success"><strong>Si</strong></span>':'<span class="badge bg-danger"><strong>No</strong></span>';
			$i++;
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'listarVend':
		$rspta=$model->listarVend();
		//declaramos un array
		$data=Array();
		$i = 0;
		while ($reg=$rspta->fetch_object()) {
			
			$data[$i][0]='<button class="btn btn-warning btn-xs" data-bs-dismiss="modal" onclick="mostrarVend(\''.$reg->nombc03.'\')"><i class="fa fa-plus"></i></button>';
			$data[$i][1]=$reg->agent03;
			$data[$i][2]=$reg->nombl03;
			$data[$i][3]=$reg->nombc03;
            $data[$i][4]=$reg->apart03;
			$data[$i][5]=$reg->direc03;
			$i++;
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'selectagencia':
		$rspta=$model->agencias();
		echo '<option value="">seleccione...</option>';
		while ($reg=$rspta->fetch_object()) {
			echo '<option value=' . str_replace(' ','-', $reg->agencia).'>'.$reg->agencia.'</option>';
		}
		break;

}
?>