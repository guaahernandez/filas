<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Vendedor{


	//implementamos nuestro constructor
public function __construct(){}

//metodo insertar regiustro
public function insertar($agent, $nombl, $nombc, $apart, $direc, $imagen, $gc){
	$sql="INSERT INTO cias_vendedores (agent03, nombl03, nombc03, apart03, direc03, local) VALUES ('$agent', '$nombl', '$nombc', '$apart', '$direc', 1)";
	//echo $sql;
	$insert = ejecutarConsulta($sql);
	if(existe("select nombc03 from cias_vendedores_img where nombc03='$nombc'")){
		$sql="UPDATE cias_vendedores_img SET activo='1', imagen='$imagen', gc='$gc' WHERE nombc03='$nombc'";
		return ejecutarConsulta($sql);
	}else{
		$sql="INSERT INTO cias_vendedores_img (nombc03, imagen, activo, gc) VALUES ('$nombc', '$imagen', 1, $gc)";
		ejecutarConsulta($sql);
	}
	return $insert;
}

public function editar($agent, $nombl, $nombc, $apart, $direc, $local, $imagen, $activo, $gc){
	// $sql="UPDATE cias_vendedores SET agent03='$agent', nombl03='$nombl', apart03='$apart', direc03='$direc'    
	// WHERE nombc03='$nombc'";
	// $update = ejecutarConsulta($sql);
	if(existe("select nombc03 from cias_vendedores_img where nombc03='$nombc'")){
		$sql="UPDATE cias_vendedores_img SET nombl03='$nombl', apart03='$apart', imagen='$imagen', activo=$activo, gc=$gc WHERE nombc03='$nombc'";
		$res = ejecutarConsulta($sql);
	}else{
		$sql="INSERT INTO cias_vendedores_img (nombl03, nombc03, apart03, imagen, activo, gc) VALUES ('$nombl', '$nombc', '$apart', '$imagen', $activo, $gc)";
		$res = ejecutarConsulta($sql);
	}
	//echo $sql;
	return $res;
}

public function desactivar($nombc){
	if(existe("select nombc03 from cias_vendedores_img where nombc03='$nombc'")){
		$sql="UPDATE cias_vendedores_img SET activo='0' WHERE nombc03='$nombc'";
	}else{
		$sql="insert into cias_vendedores_img(nombc03, activo) values('$nombc', 0)";
	}
	return ejecutarConsulta($sql);
}
public function activar($nombc){
	if(existe("select nombc03 from cias_vendedores_img where nombc03='$nombc'")){
		$sql="UPDATE cias_vendedores_img SET activo='1' WHERE nombc03='$nombc'";
	}else{
		$sql="insert into cias_vendedores_img(nombc03, activo) values('$nombc', 1)";
	}
	return ejecutarConsulta($sql);
}

//metodo para mostrar vendedores agregados
public function mostrar($nombc){
	$sql="SELECT a.agent03, ifnull(b.nombl03,a.nombl03)nombl03, a.nombc03, b.apart03, a.direc03, a.local, IFNULL(b.imagen,'guaca.jpg')imagen, IFNULL(b.`activo`,1) activo, IFNULL(b.`gc`,0) gc FROM `cias_vendedores` a
	left JOIN `cias_vendedores_img` b ON b.`nombc03`=a.`nombc03` WHERE a.nombc03='$nombc'";
	return ejecutarConsultaSimpleFila($sql);
}

//metodo para mostrar vendedores sin agregar
public function mostrarVend($nombc){
	$sql="SELECT a.agent03, a.nombl03, a.nombc03, a.apart03, a.direc03 FROM `cias_vendedores` a
	WHERE a.nombc03='$nombc'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT a.agent03, IFNULL(b.nombl03,a.`nombl03`)nombl03, a.nombc03, b.apart03, a.direc03, a.local, IFNULL(b.imagen,'guaca.jpg')imagen, IFNULL(b.`activo`,1) activo, IFNULL(b.`gc`,0) gc FROM `cias_vendedores` a
	left JOIN `cias_vendedores_img` b ON b.`nombc03`=a.`nombc03`;";
	return ejecutarConsulta($sql);
}

public function listarVend(){
	$sql="SELECT a.agent03, a.nombl03, a.nombc03, a.apart03, a.direc03 FROM `cias_vendedores` a
	left JOIN `cias_vendedores_img` b ON b.`nombc03`=a.`nombc03`
	where b.nombc03 is null;";
	return ejecutarConsulta($sql);
}

public function agencias(){
	// $sql="SELECT sede000 codigo, IFNULL(s.`descrip`,nomco00)nombre FROM tienda.pcia p 
	// LEFT JOIN xama.`sedes` s ON s.`sede`=p.`sede000`
	// WHERE sede000 != '' GROUP BY sede000;";
	$sql="SELECT DISTINCT(direc03)agencia FROM `cias_vendedores` ORDER BY direc03";
	return ejecutarConsulta($sql);
}


}

 ?>
