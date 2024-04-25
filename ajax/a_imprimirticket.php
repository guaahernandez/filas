<?php
session_start();
$tipoco = (isset($_POST["tipoco"])) ? $_POST["tipoco"] : '';

switch ($tipoco) {
    case 'V':
        $tipoco = 'Ventas';
        break;
    case 'E':
        $tipoco = 'Entregas';
        break;
    case 'G':
        $tipoco = 'Guaca Club';
        break;
    case 'T':
        $tipoco = 'Tienda en Línea';
        break;
    case 'C':
        $tipoco = 'Cajas';
        break;
    case 'D':
        $tipoco = 'Devoluciones';
        break;
    default:
        $tipoco = $tipoco.' Indefinido';
        break;
}

require __DIR__ . '/autoload.php'; 
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/*
    La impresora debe estar instalada como genérica y debe estar
    compartida
*/

/*
    Conectamos con la impresora
*/

//$nombre_impresora = "EPSON TM-T88V Receipt"; 
$nombre_impresora = "smb://".$_SESSION["im"];


$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);

/*
    Imprimimos un mensaje. Podemos usar
    el salto de línea o llamar muchas
    veces a $printer->text()
*/
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(6,6);
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text($_SESSION["impticket"]);

$printer->feed();
$printer->feed();
$printer->setTextSize(2,2);
$printer->text($tipoco);
$printer->feed();

$printer->feed();
$tux = EscposImage::load("../assets/images/guaca2.png", false);
$printer -> graphics($tux);

/*
    Hacemos que el papel salga. Es como 
    dejar muchos saltos de línea sin escribir nada
*/
$printer->feed(4);

/*
    Cortamos el papel. Si nuestra impresora
    no tiene soporte para ello, no generará
    ningún error
*/
$printer->cut();

/*
    Por medio de la impresora mandamos un pulso.
    Esto es útil cuando la tenemos conectada
    por ejemplo a un cajón
*/
//$printer->pulse();

/*
    Para imprimir realmente, tenemos que "cerrar"
    la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();
?>