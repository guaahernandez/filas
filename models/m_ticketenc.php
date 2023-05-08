<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class TicketEnc{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($agenci, $ticket, $vended, $prefer, $destin, $tipoco){
		date_default_timezone_set('America/Costa_Rica');
	
	$sql="INSERT INTO ticket_enc (agenci, ticket, vended, prefer, destin, fechac) VALUES ('$agenci','$ticket','$vended', '$prefer', '$tipoco', NOW())";
	return ejecutarConsulta($sql);
}

public function editar($agenci, $codigo, $direcc, $usuari, $estado, $descri){
	$sql="UPDATE ticket_enc SET agenci='$agenci',direcc='$direcc',usuari='$usuari', estado=$estado, descri='$descri'  
	WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function desactivar($codigo){
	$sql="UPDATE ticket_enc SET estado=0 WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}
public function activar($codigo){
	$sql="UPDATE ticket_enc SET estado=1 WHERE codigo='$codigo'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($codigo){
	$sql="SELECT * FROM ticket_enc WHERE codigo='$codigo'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar_det_pant(){
	// $sql="SELECT * FROM ticket_det where agenci='$agenci' and ubicac='$ubicac' and estado='1' ORDER BY consec desc limit 5";
	$sql = "SELECT t.*, h.agenci as 'agencia' FROM ticket_det t
	INNER JOIN mhosts h ON h.`agenci`=t.`agenci` AND h.`ubicac`=t.`ubicac`
	WHERE h.`dir_ip`='".$_SESSION["ip"]."' AND t.estado='1' ORDER BY t.consec DESC LIMIT 5;";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM ticket_enc where estado = 1";
	return ejecutarConsulta($sql);
}

public function getconsec($agenci, $tipoco){
    $conse = "";
	$sql="SELECT consec FROM consecutivos where agenci='$agenci' and tipoco='$tipoco'";		
	$conse = ejecutarConsultaConsec($sql);
    
    if($conse==''){
        $sql = "insert into consecutivos(agenci, tipoco, consec) values ('$agenci','$tipoco',1)";
        ejecutarConsulta($sql);
        $conse = 1;
    }else{
        $conse++;
        $sql = "update consecutivos set consec = $conse where agenci = '$agenci' and tipoco='$tipoco'";
        ejecutarConsulta($sql);
    }
    return $conse;
}

public function getdirecc2($agenci, $codigo){
	$sql="SELECT direcc, codigo FROM ticket_enc WHERE agenci in ('00','$agenci') and codigo>$codigo and estado=1 order by codigo limit 1";
	return ejecutarConsultaSimpleFila($sql);
}


}

 ?>
