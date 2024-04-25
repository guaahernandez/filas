<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Textos{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($agenci, $texto, $usuari, $estado){
		date_default_timezone_set('America/Costa_Rica');
	$fechacreada=date('Y-m-d H:i:s');
	$sql="INSERT INTO textos (agenci, texto, usuari, estado) VALUES ('$agenci','$texto','$usuari','$estado')";
	return ejecutarConsulta($sql);
}

public function editar($agenci, $codigo, $texto, $usuari, $estado){
	$sql="UPDATE textos SET agenci='$agenci',texto='$texto',usuari='$usuari', estado=$estado  
	WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function desactivar($codigo){
	$sql="UPDATE textos SET estado=0 WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function activar($codigo){
	$sql="UPDATE textos SET estado=1 WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($codigo){
	$sql="SELECT * FROM textos WHERE codigo='$codigo'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM textos";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM textos where estado = 1";
	return ejecutarConsulta($sql);
}

// public function gettexto($codigo){
public function gettexto(){
	$sql="SELECT GROUP_CONCAT(texto SEPARATOR ' - - - ')texto FROM textos where agenci in ('00','".$_SESSION["agenci"]."') and estado=1 order by codigo limit 1";		
	// $sql="SELECT concat('- ',texto,' -')texto, codigo FROM textos where agenci in ('00','".$_SESSION["agenci"]."') and codigo>'$codigo' and estado=1 order by codigo limit 1";		
	return ejecutarConsultaSimpleFila($sql);
}

public function gettexto2($codigo){
	$sql="SELECT texto, codigo FROM textos WHERE agenci in ('00','".$_SESSION["agenci"]."') and codigo>'$codigo' and estado=1 order by codigo limit 1";
	return ejecutarConsultaSimpleFila($sql);
}


}

 ?>
