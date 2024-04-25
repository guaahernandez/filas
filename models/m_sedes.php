<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Sede{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function replace($id, $sede, $descrip, $direcci, $grupo){
		date_default_timezone_set('America/Costa_Rica');
	//$fechacreada=date('Y-m-d H:i:s');
	$sql="REPLACE INTO sedes (id, sede, descrip, direcci, grupo, filas) VALUES ('$id','$sede','$descrip','$direcci','$grupo',1)";
	return ejecutarConsulta($sql);
}

// public function editar($id,$sede, $descrip, $direcci, $grupo){
// 	$sql="UPDATE sedes SET sede='$sede',descrip='$descrip',direcci='$direcci', grupo='$grupo'  
// 	WHERE id='$id'";
// 	return ejecutarConsulta($sql);
// }
public function desactivar($id){
	$sql="UPDATE sedes SET estado='0' WHERE id='$id' and filas=1";
	return ejecutarConsulta($sql);
}
public function activar($id){
	$sql="UPDATE sedes SET estado='1' WHERE id='$id' and filas=1";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($id){
	$sql="SELECT * FROM sedes WHERE id='$id' and filas=1";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM sedes where filas=1";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	//$sql="SELECT * FROM sedes where estado = 1 and filas=1";
	$sql="SELECT sede000 codigo, IFNULL(s.`descrip`,nomco00)nombre FROM tienda.pcia p 
	LEFT JOIN xama.`sedes` s ON s.`sede`=p.`sede000`
	WHERE p.sede000 != '' and s.filas=1 GROUP BY sede000;";
	return ejecutarConsulta($sql);
}

public function regresaRolSedes($id){
	$sql="SELECT nombre FROM sedes where id='$id' and filas=1";		
	return ejecutarConsulta($sql);
}



}

 ?>
