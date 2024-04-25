<?php 
session_start();
require_once "../models/m_usuario.php";

$usuario=new Usuario();

$idusuarioc=isset($_POST["idusuarioc"])? limpiarCadena($_POST["idusuarioc"]):"";
$clavec=isset($_POST["clavec"])? limpiarCadena($_POST["clavec"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$apellidos=isset($_POST["apellidos"])? limpiarCadena($_POST["apellidos"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$iddepartamento=isset($_POST["iddepartamento"])? limpiarCadena($_POST["iddepartamento"]):"";
$idtipousuario=isset($_POST["idtipousuario"])? limpiarCadena($_POST["idtipousuario"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$codigo_persona=isset($_POST["codigo_persona"])? limpiarCadena($_POST["codigo_persona"]):"";
$password=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$usuariocreado=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$idmensaje=isset($_POST["idmensaje"])? limpiarCadena($_POST["idmensaje"]):"";


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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/usuarios/" . $imagen);
		 	}
		}
		
		//Hash SHA256 para la contraseña
		$clavehash=hash("SHA256", $password);

		if (empty($idusuario)) {
			$idusuario=$_SESSION["idusuario"];
			$rspta=$usuario->insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$clavehash,$imagen,$usuariocreado,$codigo_persona);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		}
		else {
			$rspta=$usuario->editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$imagen,$usuariocreado,$codigo_persona);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
	break;
	

	case 'desactivar':
		$rspta=$usuario->desactivar($idusuario);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
		$rspta=$usuario->activar($idusuario);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
		$rspta=$usuario->mostrar($idusuario);
		echo json_encode($rspta);
	break;

	case 'editar_clave':
		$clavehash=hash("SHA256", $clavec);

		$rspta=$usuario->editar_clave($idusuarioc,$clavehash);
		echo $rspta ? "Password actualizado correctamente" : "No se pudo actualizar el password";
	break;

	case 'mostrar_clave':
		$rspta=$usuario->mostrar_clave($idusuario);
		echo json_encode($rspta);
	break;
	
	case 'listar':
		$rspta=$usuario->listar();
		//declaramos un array
		$data=Array();
		$i = 0;
		while ($reg=$rspta->fetch_object()) {
			
			$data[$i][0]=($reg->estado)
			?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-info btn-xs" onclick="mostrar_clave('.$reg->idusuario.')"><i class="fa fa-key"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-lock"></i></button>'
			:'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-info btn-xs" onclick="mostrar_clave('.$reg->idusuario.')"><i class="fa fa-key"></i></button>'.' '.'<button class="btn btn-success btn-xs" onclick="activar('.$reg->idusuario.')"><i class="fa fa-unlock"></i></button>';
			$data[$i][1]=$reg->nombre;
			$data[$i][2]=$reg->apellidos;
			$data[$i][3]=$reg->login;
			$data[$i][4]=$reg->email;
			$data[$i][5]=($reg->imagen!='')
			?"<img src='../assets/usuarios/".$reg->imagen."' height='50px' width='50px'>"
			:"<img src='../assets/usuarios/user.png' height='50px' width='50px'>";
			$data[$i][6]=$reg->fechacreado;
			$data[$i][7]=($reg->estado)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>';
			$i++;
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;


	case 'verificar':
		//validar si el usuario tiene acceso al sistema
		$logina=$_POST['logina'];
		$clavea=$_POST['clavea'];

		//Hash SHA256 en la contraseña
		$clavehash=hash("SHA256", $clavea);
	
		$rspta=$usuario->verificar($logina, $clavehash);

		$fetch=$rspta->fetch_object();

		if (isset($fetch)) 
		{
			# Declaramos la variables de sesion
			$_SESSION['cftidusuario']=$fetch->idusuario;
			$id=$fetch->idusuario;
			$_SESSION['cftnombre']=$fetch->nombre;
			$_SESSION['cftcodigo_persona']=$fetch->codigo_persona;
			($fetch->imagen!='') ? $_SESSION['cftimagen']=$fetch->imagen : $_SESSION["cftimagen"]='user.png' ;
			$_SESSION['cftlogin']=$fetch->login;
			$_SESSION['cfttipousuario']=$fetch->tipousuario;
			$_SESSION['cftidtipousuario']=$fetch->idtipousuario;
			$_SESSION['cftdepartamento']=$fetch->iddepartamento;
			$_SESSION['cftdepartamento_name']=$fetch->departamento;

			require "../config/Conexion.php";

			$sql="UPDATE usuarios SET iteracion='1' WHERE idusuario='$id'";
			//echo $sql; 
	 		ejecutarConsulta($sql);	 		

		}

		echo json_encode($fetch);

	break;

	case 'salir':
			
		$id=$_SESSION['cftidusuario'];
		$sql="UPDATE usuarios SET iteracion='0' WHERE idusuario='$id'";
		echo $sql; 
		ejecutarConsulta($sql);	 	
		
		//Limpiamos las variables de sesión   
        session_unset();
        //Destruìmos la sesión
        session_destroy();
        //Redireccionamos al login
        header("Location: ../html");

	break;

}
?>