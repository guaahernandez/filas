<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Usuarios (a_usuario.php)
 * ============================================================================
 * Este controlador administra la seguridad, autenticación y operaciones CRUD
 * para las cuentas de usuario con acceso al panel administrativo y módulos del sistema.
 * 
 * Operaciones disponibles (vía $_GET['op']):
 * - 'guardaryeditar': Registra un nuevo usuario con hash SHA256 o actualiza sus datos y foto.
 * - 'desactivar'    : Bloquea el acceso al usuario.
 * - 'activar'       : Desbloquea el acceso al usuario.
 * - 'mostrar'       : Retorna la información del usuario en JSON.
 * - 'editar_clave'  : Actualiza exclusivamente la contraseña (con cifrado SHA256).
 * - 'mostrar_clave' : Retorna datos básicos para el modal de cambio de clave.
 * - 'listar'        : Entrega la lista de usuarios con badges de estado y foto para DataTables.
 * - 'verificar'     : Valida credenciales de login, crea variables de sesión e inicia la iteración activa.
 * - 'salir'         : Marca al usuario como desconectado, destruye la sesión y redirige al login.
 * ============================================================================
 */

session_start();
require_once "../models/m_usuario.php";

// Instanciación del modelo
$usuario = new Usuario();

// Sanitización de parámetros recibidos por POST
$idusuarioc = isset($_POST["idusuarioc"]) ? limpiarCadena($_POST["idusuarioc"]) : "";
$clavec = isset($_POST["clavec"]) ? limpiarCadena($_POST["clavec"]) : "";
$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$apellidos = isset($_POST["apellidos"]) ? limpiarCadena($_POST["apellidos"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";
$idtipousuario = isset($_POST["idtipousuario"]) ? limpiarCadena($_POST["idtipousuario"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$password = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";
$usuariocreado = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$idmensaje = isset($_POST["idmensaje"]) ? limpiarCadena($_POST["idmensaje"]) : "";
$n_sede = isset($_POST["fsede"]) ? limpiarCadena($_POST["fsede"]) : "";

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    // Procesa el alta o modificación de un usuario incluyendo la subida de foto de perfil
	case 'guardaryeditar':

		// Validación y procesamiento de archivo de imagen
		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
			$imagen = $_POST["imagenactual"];
		} else {
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
			   $imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../assets/usuarios/" . $imagen);
		 	}
		}
		
		// Generación de hash criptográfico SHA256 para la contraseña
		$clavehash = hash("SHA256", $password);

		if (empty($idusuario)) {
			// Inserción de nuevo usuario
			$rspta = $usuario->insertar($nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $clavehash, $imagen, $usuariocreado, $codigo_persona, $n_sede);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
		} else {
			// Actualización de usuario existente
			$rspta = $usuario->editar($idusuario, $nombre, $apellidos, $login, $iddepartamento, $idtipousuario, $email, $imagen, $usuariocreado, $codigo_persona, $n_sede);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
	break;

    // Desactiva el usuario (bloqueo)
	case 'desactivar':
		$rspta = $usuario->desactivar($idusuario);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

    // Activa el usuario (desbloqueo)
	case 'activar':
		$rspta = $usuario->activar($idusuario);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
    // Obtiene datos de un usuario para cargar en el formulario
	case 'mostrar':
		$rspta = $usuario->mostrar($idusuario);
		echo json_encode($rspta);
	break;

    // Modifica exclusivamente la contraseña de acceso
	case 'editar_clave':
		$clavehash = hash("SHA256", $clavec);
		$rspta = $usuario->editar_clave($idusuarioc, $clavehash);
		echo $rspta ? "Password actualizado correctamente" : "No se pudo actualizar el password";
	break;

    // Muestra información para el modal de reseteo de clave
	case 'mostrar_clave':
		$rspta = $usuario->mostrar_clave($idusuario);
		echo json_encode($rspta);
	break;
	
    // Lista todos los usuarios con sus imágenes y estados para DataTables
	case 'listar':
		$rspta = $usuario->listar();
		$data = array();
		$i = 0;
		while ($reg = $rspta->fetch_object()) {
			
			$data[$i][0] = ($reg->estado)
                ? '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-edit"></i></button> ' .
                  '<button class="btn btn-info btn-xs" onclick="mostrar_clave(' . $reg->idusuario . ')"><i class="fa fa-key"></i></button> ' .
                  '<button class="btn btn-danger btn-xs" onclick="desactivar(' . $reg->idusuario . ')"><i class="fa fa-lock"></i></button>'
                : '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-edit"></i></button> ' .
                  '<button class="btn btn-info btn-xs" onclick="mostrar_clave(' . $reg->idusuario . ')"><i class="fa fa-key"></i></button> ' .
                  '<button class="btn btn-success btn-xs" onclick="activar(' . $reg->idusuario . ')"><i class="fa fa-unlock"></i></button>';
			$data[$i][1] = $reg->nombre;
			$data[$i][2] = $reg->apellidos;
			$data[$i][3] = $reg->login;
			$data[$i][4] = $reg->email;
			$data[$i][5] = ($reg->imagen != '')
                ? "<img src='../assets/usuarios/" . $reg->imagen . "' height='50px' width='50px'>"
                : "<img src='../assets/usuarios/user.png' height='50px' width='50px'>";
			$data[$i][6] = $reg->fechacreado;
			$data[$i][7] = ($reg->estado)
                ? '<span class="badge bg-success"><strong>Activo</strong></span>'
                : '<span class="badge bg-danger"><strong>Inactivo</strong></span>';
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

    // Validación de inicio de sesión (Login)
	case 'verificar':
		$logina = $_POST['logina'];
		$clavea = $_POST['clavea'];

		// Encriptación SHA256
		$clavehash = hash("SHA256", $clavea);
	
		$rspta = $usuario->verificar($logina, $clavehash);
		$fetch = $rspta->fetch_object();

		if (isset($fetch)) {
			// Declaración de variables de sesión del usuario autenticado
			$_SESSION['cftidusuario'] = $fetch->idusuario;
			$id = $fetch->idusuario;
			$_SESSION['cftnombre'] = $fetch->nombre;
			$_SESSION['cftcodigo_persona'] = $fetch->codigo_persona;
			($fetch->imagen != '') ? $_SESSION['cftimagen'] = $fetch->imagen : $_SESSION["cftimagen"] = 'user.png';
			$_SESSION['cftlogin'] = $fetch->login;
			$_SESSION['cfttipousuario'] = $fetch->tipousuario;
			$_SESSION['cftidtipousuario'] = $fetch->idtipousuario;
			$_SESSION['cftdepartamento'] = $fetch->iddepartamento;
			$_SESSION['cftdepartamento_name'] = $fetch->departamento;
			$_SESSION['n_sede'] = $fetch->n_sede;
			
			require "../config/Conexion.php";

			// Marca la iteración activa en la base de datos
			$sql = "UPDATE usuarios SET iteracion='1' WHERE idusuario='$id'";
	 		ejecutarConsulta($sql);	 		
		}

		echo json_encode($fetch);
	break;

    // Cierre de sesión (Logout)
	case 'salir':
		$id = isset($_SESSION['cftidusuario']) ? $_SESSION['cftidusuario'] : '';
        if (!empty($id)) {
            $sql = "UPDATE usuarios SET iteracion='0' WHERE idusuario='$id'";
            ejecutarConsulta($sql);	 	
        }
		
		// Limpieza y destrucción de la sesión
        session_unset();
        session_destroy();
        
        // Redirección a la pantalla de login
        header("Location: ../html");
	break;
}
?>