<?php 
//ip de la pc servidor base de datos
define("DB_HOST", "ws.laguacamaya.cr:13568");

// nombre de la base de datos
define("DB_NAME", "xama");


//nombre de usuario de base de datos
define("DB_USERNAME", "webservice");
//define("DB_USERNAME", "u222417_admin");

//conraseña del usuario de base de datos
define("DB_PASSWORD", "argcmy@10!");
//define("DB_PASSWORD", "Enero2020Admin");

//codificacion de caracteres
define("DB_ENCODE", "utf8mb4");

date_default_timezone_set('America/Costa_Rica');

$db = [
    'host' => DB_HOST,
    'username' => DB_USERNAME,
    'password' => DB_PASSWORD,
    'db' => DB_NAME
];
function connect($db)
  {
      try {
          $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']};charset=utf8mb4", $db['username'], $db['password']);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }
 ?>