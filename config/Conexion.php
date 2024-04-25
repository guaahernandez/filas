<?php 
require_once "global.php";

$conexion=new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');

//muestra posible error en la conexion
if (mysqli_connect_errno()) {
	printf("Ups parece que falló en la conexion con la base de datos: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('ejecutarConsulta')) {
	
	Function ejecutarConsulta($sql){ 
		global $conexion;
		$query=$conexion->query($sql);
		return $query;
	} 

	function ejecutarConsultaSimpleFila($sql){
		global $conexion;
		$query=$conexion->query($sql);
		$row=$query->fetch_assoc();
		return $row;
	}

	function existe($sql){
		global $conexion;
		$query=$conexion->query($sql);
		$row=$query->fetch_assoc();
		return ($row) ? 1:0;
	}

	function ejecutarConsultaConsec($sql){
		global $conexion;
		$query=$conexion->query($sql);
		
		$row=$query->fetch_assoc();
		//print_r($row["consec"]);
		return (isset($row["consec"])) ? $row["consec"] : '';
		
	}

	function ejecutarConsulta_retornarID($sql){
		global $conexion;
		$query=$conexion->query($sql);
		return $conexion->insert_id;
	}

	function limpiarCadena($str){
		global $conexion;
		$str=mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}
}
/*
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}
*/
 ?>