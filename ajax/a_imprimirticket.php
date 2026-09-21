<?php
/**
 * ============================================================================
 * Sistema de Control de Filas - Impresión Térmica de Tiquetes (a_imprimirticket.php)
 * ============================================================================
 * Este script procesa y envía el trabajo de impresión directa a impresoras
 * térmicas POS (protocolo ESC/POS) utilizando la librería 'Mike42\Escpos'.
 * 
 * Funcionalidades principales:
 * - Conversión y homologación del código de trámite al nombre completo del servicio.
 * - Conexión al recurso compartido de la impresora térmica en Windows/SMB.
 * - Formateo gráfico del tiquete:
 *     1. Número de turno en tamaño gigante y centrado.
 *     2. Descripción del departamento/servicio.
 *     3. Gráfico institucional (Logo La Guacamaya).
 *     4. Avance de papel y corte automático.
 * ============================================================================
 */

// Inicialización de la sesión para acceder a las variables de tiquete e impresora
session_start();

// Captura del código de trámite recibido por POST
$tipoco = (isset($_POST["tipoco"])) ? $_POST["tipoco"] : '';

// Homologación de códigos cortos a nombres descriptivos del servicio
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
        $tipoco = $tipoco . ' Indefinido';
        break;
}

// Carga del autoloader para las clases de la librería ESC/POS de Mike42
require __DIR__ . '/autoload.php'; 

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/*
 * ============================================================================
 * Configuración y Conexión con la Impresora Térmica
 * ============================================================================
 * Requisitos:
 * - El controlador de la impresora debe estar instalado en Windows.
 * - La impresora debe estar configurada como recurso compartido en red (SMB).
 */

// Construcción de la ruta de red SMB a la impresora configurada en la sesión
// Ejemplo: "smb://EQUIPO_QUIOSCO/EPSON_TM_T88V"
$nombre_impresora = "smb://" . $_SESSION["im"];

// Creación del conector de impresión para Windows y del objeto controlador ESC/POS
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);

/*
 * ============================================================================
 * Construcción y Formateo del Recibo Térmico
 * ============================================================================
 */

// 1. Número de turno/tiquete (Grande y centrado)
$printer->setFont(Printer::FONT_A);
$printer->setTextSize(6, 6); // Tamaño máximo de fuente para visibilidad clara
$printer->setJustification(Printer::JUSTIFY_CENTER);
$printer->text($_SESSION["impticket"]);

// Espaciado entre el turno y el servicio
$printer->feed(2);

// 2. Nombre del trámite o destino (Tamaño mediano)
$printer->setTextSize(2, 2);
$printer->text($tipoco);
$printer->feed(2);

// 3. Logotipo institucional de la empresa
$tux = EscposImage::load("../assets/images/guaca2.png", false);
$printer->graphics($tux);

/*
 * ============================================================================
 * Finalización del Trabajo de Impresión
 * ============================================================================
 */

// Avance de papel para asegurar que el corte no corte el contenido
$printer->feed(4);

// Envío del comando de corte automático de papel
$printer->cut();

// Apertura de cajón de dinero mediante pulso eléctrico (opcional / comentado)
// $printer->pulse();

// Cierre de la conexión: vacía el búfer de impresión y envía los datos al dispositivo
$printer->close();
?>