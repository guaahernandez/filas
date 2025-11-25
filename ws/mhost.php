<?php
try{
    require_once "../config/global.php";
    
    $dir_ip = (isset($_GET['ip'])) ? $_GET['ip'] : '';
    /**CONEXION A BD */
    $dbcon = connect($db);
	/* sacamos los datos de bd */
	$sql = $dbcon->prepare("SELECT * FROM mhosts where dir_ip = '$dir_ip'");
	
	$sql->execute();

	header('Content-type: application/json');

	if($sql){
		header('HTTP/1.1 200 OK');
		echo json_encode( $sql->fetchALL(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
	}else{
		header('HTTP/1.1 404 NO');
		echo json_encode(array('Null'=>'404'));
	}
	$sql->closeCursor();
	
}catch(PDOException $ex){
	die($ex->getMessage());
}
?>