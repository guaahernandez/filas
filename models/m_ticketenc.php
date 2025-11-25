<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class TicketEnc{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($agenci, $ticket, $vended, $prefer, $destin, $tipoco, $factur="", $tidoc="", $monto=""){
		date_default_timezone_set('America/Costa_Rica');
	if($tipoco=="PV") $tipoco="V"; //venta
	if($tipoco=="PE") $tipoco="E"; //entrega
	if($tipoco=="PC") $tipoco="C"; //cajas
	if($tipoco=="PGC") $tipoco="GC"; //guaca club

	$sql="INSERT INTO ticket_enc (n_sede, ticket, vended, prefer, destin, fechac, horenv, factur, monto, tidoc) VALUES (
		'$agenci', '$ticket', '$vended', $prefer, '$tipoco', NOW(), NOW(), '$factur', $monto, '$tidoc')";
		//echo $sql;
	return ejecutarConsulta_retornarID($sql);
}

public function insertardet($agenci, $ticket, $segund, $ubicac, $destin, $codigt, $prefac, $factur, $montof, $estacion){
	$sql="INSERT INTO ticket_det (n_sede, ticket, fechac, segund, ubicac, destin, codigt, prefac, factur, montof, estacion) VALUES (
	'$agenci','$ticket', NOW(),'$segund', '$ubicac', '$destin', $codigt, '$prefac', '$factur', $montof, $estacion)";
	//echo $sql;
	return ejecutarConsulta($sql);
}

public function guarda_fact_ticket($codigt, $agenci, $ticket, $factur){
	$sql = "INSERT INTO `factura_entrega`(`codtrans`, `sucursal`, `destino`, `estado`, `tiquete`, `factura`, `estadot`)";
	$sql .= " VALUES ($codigt, '$agenci', 'E', '3', '".trim($ticket)."', '$factur', 'PE');";
	echo $sql;
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

public function desactivarllamar($consec){
	$sql="UPDATE ticket_det SET llamar=0 WHERE consec='$consec'";
	return ejecutarConsulta($sql);
}
public function activarllamar($consec){
	$sql="UPDATE ticket_det SET llamar=1 WHERE consec='$consec'";
	return ejecutarConsulta($sql);
}
//metodo para mostrar registros
public function mostrar($codigo){
	$sql="SELECT * FROM ticket_enc WHERE codigo='$codigo'";
	return ejecutarConsultaSimpleFila($sql);
}

public function obtener_imprimir($codigo){
	$sql="SELECT DISTINCT e.`id`, e.`ticket`, IFNULL(v2.`nombl03`, v.`nombl03`) vended, c.`descrip` agencia, e.`fechac`, d.`vengoa`, td.factur
	FROM `ticket_enc` e
	LEFT JOIN ticket_det td on td.n_sede=e.n_sede and td.codigt=e.id
	LEFT JOIN `sedes` c ON c.`sede`=e.`n_sede`
	LEFT JOIN `opmenu_det` d ON d.`codigo`=e.`destin`
	LEFT JOIN `cias_vendedores` v ON v.`nombc03`=e.`vended`
	LEFT JOIN `cias_vendedores_img` v2 ON v2.`nombc03`=e.`vended`
	WHERE e.`id`='$codigo'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar_det_pant(){
	$destin = str_replace(",","','",$_SESSION["destin"]);

	// $sql="SELECT * FROM ticket_det where agenci='$agenci' and ubicac='$ubicac' and estado='1' ORDER BY consec desc limit 5";
	$sql = "SELECT t.*, h.n_sede as 'agencia', IFNULL(u.`ver_numero`,1)ver_numero FROM ticket_det t
	INNER JOIN mhosts h ON h.`n_sede`=t.`n_sede` AND h.`ubicac`=t.`ubicac`";
	//lista por destino, V=ventas, E=entrega, GC=Guaca Club
	$sql .= " inner join ticket_enc e on e.n_sede=t.n_sede and e.id=t.codigt and e.destin IN ('".$destin."')";
	$sql .= " LEFT JOIN estacion u ON u.`n_sede`=t.`n_sede` AND t.`estacion`=u.`estacion`";
	$sql .= " WHERE h.`dir_ip`='".$_SESSION["ip"]."' AND t.estado='1' and t.fechac>date(NOW()) ORDER BY t.consec DESC LIMIT 5;";
	//echo $sql;
	return ejecutarConsulta($sql);
}

//listar registros
public function l_pant_entreg(){
	$sql = "SELECT t.*, h.n_sede AS 'agencia'/*,(SELECT MAX(FACTUR) FROM ticket_det WHERE codigt=t.`codigt`)factura*/, e.factur factura
	, IF(IFNULL((SELECT 1 FROM ticket_det WHERE codigt=t.`codigt` AND ubicac=9 limit 1),'')='','PROCESO','TERMINADO')estadof 
	FROM ticket_det t
	INNER JOIN mhosts h ON h.`n_sede`=t.`n_sede` AND h.`ubicac`=t.`ubicac`
	LEFT JOIN `ticket_enc` e ON e.`id`=t.`codigt`
	WHERE h.`dir_ip`='".$_SESSION["ip"]."' AND t.estado='1' and t.fechac>date(NOW()) ORDER BY t.consec DESC LIMIT 8;";
	//echo $sql;
	return ejecutarConsulta($sql);
}

//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM ticket_enc where estado = 1";
	return ejecutarConsulta($sql);
}

public function getconsec($agenci, $tipoco){
    $conse = "";
	$sql="SELECT IF(fechac<DATE(NOW()),0,consec)consec FROM consecutivos where agenci='$agenci' and tipoco='$tipoco'";		
	$conse = ejecutarConsultaConsec($sql);
    
    if($conse==''){
        $sql = "insert into consecutivos(agenci, tipoco, consec, fechac) values ('$agenci','$tipoco',1, NOW())";
        ejecutarConsulta($sql);
        $conse = 1;
    }else{
        $conse++;
        $sql = "update consecutivos set consec = $conse, fechac=NOW() where agenci = '$agenci' and tipoco='$tipoco'";
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
