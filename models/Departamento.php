<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Departamento{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$descripcion,$idusuario){
		date_default_timezone_set('America/Costa_Rica');
	$fechacreada=date('Y-m-d H:i:s');
	$sql="INSERT INTO cias (nombre,descripcion,fechacreada,idusuario) VALUES ('$nombre','$descripcion','$fechacreada','$idusuario')";
	return ejecutarConsulta($sql);
}

public function editar($iddepartamento,$nombre,$descripcion,$idusuario){
	$sql="UPDATE cias SET nombre='$nombre',descripcion='$descripcion',idusuario='$idusuario', estado=1  
	WHERE iddepartamento='$iddepartamento'";
	return ejecutarConsulta($sql);
}
public function desactivar($iddepartamento){
	$sql="UPDATE cias SET estado='0' WHERE iddepartamento='$iddepartamento'";
	return ejecutarConsulta($sql);
}
public function activar($iddepartamento){
	$sql="UPDATE cias SET estado='1' WHERE iddepartamento='$iddepartamento'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($iddepartamento){
	$sql="SELECT * FROM cias WHERE iddepartamento='$iddepartamento'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM cias";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM cias where estado = 1";
	return ejecutarConsulta($sql);
}

public function regresaRolDepartamento($departamento){
	$sql="SELECT nombre FROM cias where iddepartamento='$departamento'";		
	return ejecutarConsulta($sql);
}



}

 ?>
