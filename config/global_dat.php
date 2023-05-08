<?php 
//nombre del proyecto
define("PRO_NOMBRE", "Control de Filas");

//Alias del proyecto
define("PRO_ALIAS", "CFT");

//Descripcion del proyecto
define("PRO_DESCRIP", "Control de Filas y Trazabilidad");

//Descripcion del proyecto
define("PRO_VERSION", "1.0");

function obtenerDireccionIP()
{
    if (!empty($_SERVER ['HTTP_CLIENT_IP'] ))
      $ip=$_SERVER ['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER ['HTTP_X_FORWARDED_FOR'] ))
      $ip=$_SERVER ['HTTP_X_FORWARDED_FOR'];
    else
      $ip=$_SERVER ['REMOTE_ADDR'];
 
    $ip=getenv('REMOTE_ADDR');

    return $ip;
}

 ?>