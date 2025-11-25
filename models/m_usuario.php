<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$clavehash,$imagen,$usuariocreado,$codigo_persona, $n_sede){
	date_default_timezone_set('America/Costa_Rica');
	$fechacreado=date('Y-m-d H:i:s');
	$sql="INSERT INTO usuarios (nombre,apellidos,login,iddepartamento,idtipousuario,email,password,imagen,estado,fechacreado,usuariocreado,codigo_persona,n_sede) VALUES ('$nombre','$apellidos','$login','$iddepartamento','$idtipousuario','$email','$clavehash','$imagen','1','$fechacreado','$usuariocreado','$codigo_persona','$n_sede')";
	return ejecutarConsulta($sql);

}

public function editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$imagen,$usuariocreado,$codigo_persona, $n_sede){
	$sql="UPDATE usuarios SET nombre='$nombre',apellidos='$apellidos',login='$login',iddepartamento='$iddepartamento',idtipousuario='$idtipousuario',email='$email',imagen='$imagen' ,usuariocreado='$usuariocreado',codigo_persona='$codigo_persona', n_sede='$n_sede'    
	WHERE idusuario='$idusuario'";
	 return ejecutarConsulta($sql);

}
public function editar_clave($idusuario,$clavehash){
	$sql="UPDATE usuarios SET password='$clavehash' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function mostrar_clave($idusuario){
	$sql="SELECT idusuario, password FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}
public function desactivar($idusuario){
	$sql="UPDATE usuarios SET estado='0' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function activar($idusuario){
	$sql="UPDATE usuarios SET estado='1' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idusuario){
	$sql="SELECT * FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM usuarios";
	return ejecutarConsulta($sql);
}

public function cantidad_usuario(){
	$sql="SELECT count(*) nombre FROM usuarios where estado=1";
	return ejecutarConsulta($sql);
}

//Función para verificar el acceso al sistema
	public function verificar($login,$clave)
    {
    	$sql="SELECT u.codigo_persona,u.idusuario,u.nombre,u.apellidos,u.login,u.idtipousuario";
		$sql.=", u.iddepartamento,u.email,u.imagen,u.login, tu.nombre as tipousuario, d.descripcion AS departamento, u.n_sede";
		$sql.=" FROM usuarios u INNER JOIN tipousuario tu ON u.idtipousuario=tu.idtipousuario";
		$sql.=" LEFT JOIN cias d ON d.iddepartamento=u.`iddepartamento`";
		$sql.=" WHERE login='$login' AND password='$clave' AND u.estado='1'"; 
		echo $sql;
    	return ejecutarConsulta($sql);  
    }
}

 ?>
