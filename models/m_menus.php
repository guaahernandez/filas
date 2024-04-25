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

public function opmenu_enc($nombre){
	$sql="SELECT * FROM opmenu_enc WHERE agenci in ('00','".$_SESSION["agenci"]."') and nombre='$nombre' and estado=1";
	//echo $sql;
	return ejecutarConsultaSimpleFila($sql);
}

public function opmenu_det($ubmenu){
	$sql="SELECT * FROM opmenu_det WHERE agenci in ('00','".$_SESSION["agenci"]."') and ubmenu='$ubmenu' and estado=1 order by ordenm";
	//echo $sql;
	return ejecutarConsulta($sql);
}

//listar vendedores
public function listar_v($agenci, $nombre){
	$sql="SELECT b.`nombc03`, IFNULL(i.`nombl03`,b.`nombl03`)nombl03, b.`agent03`, IF(IFNULL(i.imagen,'')='','guaca.jpg',i.imagen)imagen, IFNULL(i.`activo`,1)activ, IFNULL(i.gc,0)gc, b.local FROM 
	xama.`cias_vendedores` b
	inner JOIN xama.`cias_vendedores_img` i ON i.`nombc03`=b.`nombc03`
	WHERE i.sucur03='$agenci' AND i.logue03=1";
	$sql .= " HAVING activ=1";
	$sql .= ($nombre=='guacaclub') ? " and gc=1": " /*and gc!=1*/ and b.local=0";	
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

public function getpromo(){
	$sql="SELECT link FROM app_guacaclub.applinks where descripcion='Promociones'";		
	return ejecutarConsultaSimpleFila($sql);
}



}

 ?>
