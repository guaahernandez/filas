<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Pantallas (a_pantalla.php)
 * ============================================================================
 * Este controlador procesa las peticiones asíncronas (AJAX) enviadas desde las
 * interfaces de visualización en sala (pantalla.php, pantentregas.php, etc.).
 * 
 * Funcionalidades principales:
 * - 'listar_det_pant' : Genera el HTML de los turnos y puestos llamados para la pizarra principal.
 * - 'l_pant_entreg'   : Genera el HTML de facturas y estados para la pantalla de entregas/despacho.
 * - 'cargavideos'     : Retorna en formato JSON los datos del video a reproducir en bucle.
 * - 'creaticket'      : Obtiene el consecutivo e inserta un nuevo tiquete/turno en el sistema.
 * ============================================================================
 */

// Inclusión de los modelos necesarios para interactuar con la base de datos
require_once "../models/m_videos.php";
require_once "../models/m_ticketenc.php";

// Inicialización o reanudación de la sesión PHP si no existe una activa
if (strlen(session_id()) < 1) {
    session_start();
}

// Instanciación de las clases de modelo
$videos = new Videos();
$ticket = new TicketEnc();

// Sanitización de parámetros recibidos por POST para evitar inyección SQL o XSS
$agenci = isset($_POST["agenci"]) ? limpiarCadena($_POST["agenci"]) : "";
$ubicac = isset($_POST["ubicac"]) ? limpiarCadena($_POST["ubicac"]) : "";

$cias = array();
$txtllamar = "";

// Evaluación de la operación solicitada mediante el parámetro GET 'op'
switch ($_GET["op"]) {
    
    /**
     * ------------------------------------------------------------------------
     * Caso: listar_det_pant
     * ------------------------------------------------------------------------
     * Consulta los últimos turnos llamados en la sucursal y genera las filas HTML
     * que se inyectan en el elemento #pizarra de la pantalla principal.
     */
    case 'listar_det_pant':
        ?>
            <!-- Encabezados de columnas de la pizarra -->
            <div class="col-5 titulo">TURNO</div>
            <div class="col-7 titulo">PUESTO</div>
        <?php
        // Clase CSS para resaltar el turno más reciente en color rojo
        $fondo = "fondorojo";
        $cont = 5; // Número máximo de turnos a mostrar en la pizarra
        $rspta = $ticket->listar_det_pant();

        // Itera sobre los registros de turnos llamados obtenidos de la base de datos
        while ($reg = $rspta->fetch_object()) {
            // Asigna la agencia del primer registro a la sesión si no estaba definida
            if ($cont == 5) {
                $_SESSION["agenci"] = $reg->agencia;
            }
            $cont--;
            ?>
            <!-- Número de Turno / Tiquete -->
            <div class="col-5 num <?=$fondo;?>">
                <!-- <?=str_replace('-', '', $reg->ticket);?> -->
                <?=$reg->ticket;?>        
            </div>
            <!-- Destino y Módulo / Estación de Atención -->
            <div class="col-7 num <?=$fondo;?>">
                <?php  
                    // Si el destino tiene número de estación visible, se concatena
                    $estacion = ($reg->ver_numero > 0) ? $reg->estacion : '';
                    echo $reg->destin . ' ' . $estacion;
                ?>        
            </div>

            <?php
            // Quita el resaltado rojo después de procesar el primer turno (el más reciente)
            $fondo = "";
        }
        
        // Rellena los espacios vacíos restantes para mantener la altura y estética fija de la tabla
        for ($i = 0; $i < $cont; $i++) { ?>
            <div class="col-12 num"></div>
        <?php } ?>
            
        <?php
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: l_pant_entreg
     * ------------------------------------------------------------------------
     * Consulta el estado de preparación y entrega de facturas para la pantalla
     * de despacho / entregas de pedidos.
     */
    case 'l_pant_entreg':
        ?>
            <!-- Encabezados de columnas para pantalla de entregas -->
            <div class="col-6 titulo">FACTURA</div>
            <div class="col-6 titulo">ESTADO</div>
            <!-- <div class="col-4 titulo">ESTAD0</div> -->
        <?php
        $fondo = "fondorojo";
        $cont = 8; // Capacidad máxima de filas para la pantalla de entregas
        $rspta = $ticket->l_pant_entreg();

        // Itera sobre el listado de facturas en proceso de entrega
        while ($reg = $rspta->fetch_object()) {
            if ($cont == 8) {
                $_SESSION["agenci"] = $reg->agencia;
            }
            $cont--;

            // Lógica de sintetizador de voz / llamado de audio (deshabilitada)
            // if($reg->llamar==1){
            //     //$txtllamar = ', v, 10, pasar a ventas 1'; // ejemplo
            //     $txtllamar = ', '.str_replace('-',',',$reg->ticket).', pasar a '.$reg->destin;
            //     saveSound($txtllamar, $reg->agencia);
            //     $ticket->desactivarllamar($reg->consec);
            //     //echo $txtllamar;
            // }

            ?>
            <!-- Número de Factura (formateado con ceros a la izquierda y recortado a los últimos dígitos) -->
            <div class="col-5 num <?=$fondo;?>">
                <?=substr(str_pad($reg->factura, 10, ' ', STR_PAD_LEFT), 6);?>        
            </div>
            <!-- Estado actual de la factura (ej: En preparación, Listo, etc.) -->
            <div class="col-7 num <?=$fondo;?>">
                <?=$reg->estadof;?>        
            </div>

            <?php
            $fondo = "";
        }
        
        // Espacios en blanco para mantener la cuadrícula simétrica
        for ($i = 0; $i < $cont; $i++) { ?>
            <div class="col-12 num"></div>
        <?php } ?>
            
        <?php
        break;
    
    /**
     * ------------------------------------------------------------------------
     * Caso: cargavideos
     * ------------------------------------------------------------------------
     * Consulta y devuelve la información del video correspondiente para la lista
     * de reproducción. Si llega al final de la lista, reinicia desde el primer video (código 0).
     */
    case 'cargavideos':     
        $rspta = $videos->getdirecc2($agenci, $codigo);        
        if ($rspta) {
            echo json_encode($rspta);
        } else {
            // Reinicio de playlist: consulta el primer video
            $rspta = $videos->getdirecc2($agenci, 0);
            echo json_encode($rspta);
        }    
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: creaticket
     * ------------------------------------------------------------------------
     * Genera el siguiente número correlativo para el tipo de trámite solicitado
     * e inserta el nuevo tiquete/turno en la tabla de turnos activos.
     */
    case 'creaticket':     
        $rspta = $ticket->getconsec($agenci, $tipoco);        
        if ($rspta) {
            $ticket->insertar($agenci, $tipoco . $rspta, $vended, $prefer, $destin, $tipoco);
            //echo $rspta;
        }
        break;

}       
?>