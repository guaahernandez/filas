<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Videos{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($agenci, $direcc, $descri, $format){
		date_default_timezone_set('America/Costa_Rica');
	$fechacreada=date('Y-m-d H:i:s');
	$sql="INSERT INTO videos (agenci, direcc, descri, format) VALUES ('$agenci','$direcc', '$descri', '$format')";
	return ejecutarConsulta($sql);
}

public function editar($agenci, $codigo, $direcc, $descri, $format){
	$sql="UPDATE videos SET agenci='$agenci',direcc='$direcc', descri='$descri', format='$format'  
	WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function desactivar($codigo){
	$sql="UPDATE videos SET estado=0 WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function activar($codigo){
	$sql="UPDATE videos SET estado=1 WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}

public function eliminar($codigo){
	$sql="UPDATE videos SET estado=2 WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($codigo){
	$sql="SELECT * FROM videos WHERE codigo='$codigo'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM videos where estado<2";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM videos where estado = 1";
	return ejecutarConsulta($sql);
}

public function getdirecc($codigo){
	$sql="SELECT direcc FROM videos where codigo='$codigo'";		
	return ejecutarConsultaSimpleFila($sql);
}

public function getdirecc2($codigo){

	$sql="SELECT direcc, codigo FROM videos WHERE agenci in ('00','".$_SESSION["agenci"]."') and codigo>$codigo and estado=1 and format='".$_SESSION["formatovideo"]."' order by codigo limit 1";
	//echo $sql;
	return ejecutarConsultaSimpleFila($sql);
}


}

 ?>
