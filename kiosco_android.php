<?php
    require 'config/Conexion.php';
    $sql="SELECT * FROM mhost WHERE dir_ip='".$_GET["ip"]."'";
	$row = ejecutarConsultaSimpleFila($sql);
?>