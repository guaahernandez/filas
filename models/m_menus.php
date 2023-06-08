<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Menus{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($agenci, $ubmenu, $codigo, $descri, $nombre, $orden){
		date_default_timezone_set('America/Costa_Rica');
	$sql="INSERT INTO opcmenu (agenci, ubmenu, codigo, descri, nombre, orden) VALUES ('$agenci','$ubmenu','$codigo', '$descri', '$nombre', $orden)";
	return ejecutarConsulta($sql);
}

public function editar($agenci, $ubmenu, $codigo, $descri, $nombre, $orden){
	$sql="UPDATE opcmenu SET agenci='$agenci', ubmenu='$ubmenu', codigo='$codigo', descri='$descri', nombre='$nombre', orden=$orden  
	WHERE agenci='$agenci' and ubmenu=$ubmenu and codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function desactivar($agenci, $ubmenu, $codigo){
	$sql="UPDATE opcmenu SET estado=0 WHERE agenci='$agenci' and ubmenu='$ubmenu' and codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function activar($agenci, $ubmenu, $codigo){
	$sql="UPDATE opcmenu SET estado=1 WHERE agenci='$agenci' and ubmenu='$ubmenu' and codigo='$codigo'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($agenci, $ubmenu, $codigo){
	$sql="SELECT * FROM opcmenu WHERE WHERE agenci='$agenci' and ubmenu='$ubmenu' and codigo='$codigo'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar($ubmenu){
	$sql="SELECT * FROM opcmenu WHERE agenci in ('00','".$_SESSION["agenci"]."') and ubmenu='$ubmenu' and estado=1 order by ordenm";
	//echo $sql;
	return ejecutarConsulta($sql);
}

//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM opcmenu where estado = 1";
	return ejecutarConsulta($sql);
}

public function getdirecc($codigo){
	$sql="SELECT direcc FROM opcmenu where codigo='$codigo'";		
	return ejecutarConsultaSimpleFila($sql);
}



}

 ?>
