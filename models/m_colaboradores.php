<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Colaborador{


	//implementamos nuestro constructor
public function __construct(){}

//metodo insertar regiustro
public function insertar($agenci, $usuari, $nombre, $imagen, $depart){
	date_default_timezone_set('America/Costa_Rica');
	$sql="INSERT INTO colaboradores (agenci, usuari, nombre, imagen, depart, fechac, estado) VALUES ('$agenci', '$usuari', '$nombre', '$imagen', '$depart', NOW(), 1)";
	return ejecutarConsulta($sql);

}

public function editar($agenci, $usuari, $codigo, $nombre, $imagen, $depart, $estado){
	$sql="UPDATE colaboradores SET agenci='$agenci', usuari='$usuari', nombre='$nombre', imagen='$imagen', depart='$depart', fechac=NOW(), estado=$estado    
	WHERE codigo='$codigo'";
	 return ejecutarConsulta($sql);
}

public function desactivar($codigo){
	$sql="UPDATE colaboradores SET estado='0' WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function activar($codigo){
	$sql="UPDATE colaboradores SET estado='1' WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($codigo){
	$sql="SELECT * FROM colaboradores WHERE codigo='$codigo'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM colaboradores";
	return ejecutarConsulta($sql);
}

public function cantidad_usuario(){
	$sql="SELECT count(*) nombre FROM colaboradores where estado=1";
	return ejecutarConsulta($sql);
}

}

 ?>
