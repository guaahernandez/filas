<?php
/**
 * ============================================================================
 * Sistema de Control de Filas - Controlador AJAX de Menús y Quiosco (a_menus.php)
 * ============================================================================
 * Este controlador procesa todas las solicitudes dinámicas del quiosco de autoservicio:
 * 1. Generación de cuadrículas de botones y tarjetas (trámites, atención preferencial, vendedores).
 * 2. Emisión y registro de tiquetes/turnos (creación de encabezado, detalle y correlativo diario).
 * 3. Pantallas especializadas: escaneo de código de barras (menubr), QR (menuqr), confirmación
 *    de retiro de tiquete (retirartic) y pase a entregas (pasaentreg).
 * 4. Integración con servicios de impresión térmica (obtener_imprimir) y validación de usuarios activos.
 * ============================================================================
 */

use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\AssignOp\Div;

// Inclusión de modelos de base de datos requeridos
require_once "../models/m_menus.php";
require_once "../models/m_videos.php";
require_once "../models/m_ticketenc.php";

// Inicialización de la sesión PHP si aún no está activa
if (strlen(session_id()) < 1) {
	session_start();
}

// Instanciación de modelos
$model = new Menus();
$videos = new Videos();
$ticket = new TicketEnc;

// Sanitización y recepción de variables enviadas por POST
$agenci = isset($_POST["agenci"]) ? limpiarCadena($_POST["agenci"]) : $_SESSION["agenci"];
$ubmenu = isset($_POST["ubmenu"]) ? limpiarCadena($_POST["ubmenu"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$descri = isset($_POST["descri"]) ? limpiarCadena($_POST["descri"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$ordenm = isset($_POST["ordenm"]) ? limpiarCadena($_POST["ordenm"]) : "";
$estado = isset($_POST["estado"]) ? limpiarCadena($_POST["estado"]) : "";
$tipoco = isset($_POST["tipoco"]) ? limpiarCadena($_POST["tipoco"]) : "";
$vended = isset($_POST["vended"]) ? limpiarCadena($_POST["vended"]) : "";
$numfac = isset($_POST["numfac"]) ? limpiarCadena($_POST["numfac"]) : "";

// Parámetros de emisión y configuración de turnos
$prefer = isset($_POST["prefer"]) ? $_POST["prefer"] : "0";
$destin = isset($_POST["destin"]) ? $_POST["destin"] : "";
$factur = isset($_POST["factur"]) ? $_POST["factur"] : "";
$montof = isset($_POST["montof"]) ? $_POST["montof"] : 0;
$tidoc = isset($_POST["tidoc"]) ? $_POST["tidoc"] : "";
$estacion = isset($_POST["estacion"]) ? $_POST["estacion"] : "";

$segund = isset($_POST["prefer"]) ? $_POST["prefer"] : "0";
$ubicac = isset($_POST["ubicac"]) ? $_POST["ubicac"] : "";
$codigt = isset($_POST["codigt"]) ? $_POST["codigt"] : "0";
$prefac = isset($_POST["prefac"]) ? $_POST["prefac"] : "";

// Configuración de entorno Android y turno
$android = (isset($_SESSION["android"])) ? $_SESSION["android"] : "0";
$turno = (isset($_POST["turno"])) ? $_POST["turno"] : "";

$cias = array();

// Enrutador de operaciones solicitadas mediante el parámetro GET 'op'
switch ($_GET["op"]) {
    
    /**
     * ------------------------------------------------------------------------
     * Caso: cargamenu
     * ------------------------------------------------------------------------
     * Genera la lista de tarjetas interactivas (mCard) para seleccionar trámites.
     */
    case 'cargamenu':
        $rspta = $model->listar($ubmenu);
        $prefer = ($ubmenu == 'prefer' || $ubmenu == 'vendedores') ? 1 : 0;
        $_SESSION['prefer'] = ($ubmenu == 'prefer') ? 1 : 0;
        ?>
        <div class="row" style="padding: 0; margin: 0;">      
        
            <div class="col-8 titulo">
                Seleccione el trámite por realizar:
            </div>
            <div class="col-4">
            <?php if ($prefer == 1) { ?>
            <!-- Botón para regresar al menú principal -->
            <div onclick="cargamenu('principal');"                
                style="border: solid 1px #1b468c;                  
                border-radius: 10px;                 
                font-weight: 900;
                font-size: 60px;
                background-color: #f6f7fb;
                color: #0f3e7f;">
                <i class="mdi mdi-arrow-left"></i>
            </div>
            <?php } ?>
            </div>
        <?php
        
        // Renderiza cada opción disponible del menú
        while ($reg = $rspta->fetch_object()) {
            if ($reg->codigo == 'A') { // Si la opción seleccionada es atención por vendedor específico
                $accion = "cargamenu_v('" . $agenci . "');";
            } else {
                $accion = "creaticket('" . $agenci . "','" . $reg->codigo . "', " . $prefer . ");";
            }
            ?>
            <div class="col-md-3" style="margin: 0; padding: 0;">
                <div onclick="<?=$accion;?>" class="mCard">
                    <div class="mCardlogo"> 
                        <i class="mdi mdi-<?=$reg->logome;?>"></i>                       
                    </div>
                    <div class="texto"><?=$reg->descri?></div>
                    <div style="font-size: 35px; color: #0f3e7f;"><i class="mdi mdi-arrow-right"></i></div>
                </div>
            </div>
            <?php
        }
        
        echo '</div>';
        break;
    
    /**
     * ------------------------------------------------------------------------
     * Caso: cargamenu_v
     * ------------------------------------------------------------------------
     * Genera la lista de tarjetas de vendedores disponibles para la agencia activa.
     */
    case 'cargamenu_v':
        $rspta = $model->listar_v($agenci);
        $pref = 1;
        ?>
        <div class="row" style="padding: 0; margin: 0;">
        <div class="col-8 titulo">
            Seleccione el vendedor:
        </div>
        <div class="col-4">        
            <!-- Botón de regreso -->
            <div onclick="cargamenu('principal');"                
                style="border: solid 1px #1b468c;                  
                border-radius: 10px;                 
                font-weight: 900;
                font-size: 60px;
                background-color: #f6f7fb;
                color: #0f3e7f;">
                <i class="mdi mdi-arrow-left"></i>
            </div>        
        </div>
        <?php
        while ($reg = $rspta->fetch_object()) {
            $nombre = $reg->nombc03;
            $accion = "creaticket('" . $agenci . "','V', '" . $_SESSION["prefer"] . "', '" . $nombre . "');";
            
            // Valida si el vendedor tiene sesión iniciada
            if (ValidaLogin($nombre)) {
            ?>
            <!-- Tarjeta de vendedor -->
            <div class="col-md-3" style="margin: 0; padding: 0;">
                <div onclick="<?=$accion;?>" class="mCard">
                    <div class="mCardlogo"> 
                        <i class="mdi mdi-account"></i>                       
                    </div>
                    <div class="texto" style="margin-top: 15px; height: 60px;"><?=$reg->nombl03?></div>
                    <div style="font-size: 35px; color: #0f3e7f;"><i class="mdi mdi-arrow-right"></i></div>
                </div>
            </div>
            <?php
            }
        }
        
        echo '</div>';
        break;
    
    /**
     * ------------------------------------------------------------------------
     * Caso: cargavideos
     * ------------------------------------------------------------------------
     * Obtiene el siguiente video de la lista de reproducción multimedia en formato JSON.
     */
    case 'cargavideos':     
        $rspta = $videos->getdirecc2($codigo);        
        if ($rspta) {
            echo json_encode($rspta);
        } else {
            // Si termina la lista, reinicia desde el video 0
            $rspta = $videos->getdirecc2(0);
            echo json_encode($rspta);
        }    
        break;
    
    /**
     * ------------------------------------------------------------------------
     * Caso: getconsec
     * ------------------------------------------------------------------------
     * Consulta el siguiente consecutivo numérico disponible para un tipo de trámite.
     */
    case 'getconsec':     
        $rspta = $ticket->getconsec($agenci, $tipoco);        
        if ($rspta) {
            echo $rspta;
        } else {
            echo 'Fallo consec';
        }    
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: getpromo
     * ------------------------------------------------------------------------
     * Consulta el enlace de la promoción publicitaria activa.
     */
    case 'getpromo':     
        $rspta = $model->getpromo();        
        if ($rspta) {
            echo $rspta["link"];
        } else {
            echo 'Fallo promo';
        }    
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: creaticket
     * ------------------------------------------------------------------------
     * Crea un nuevo tiquete/turno en base de datos y retorna: ID_GENERADO|NUMERO_TIQUETE
     */
    case 'creaticket':     
        $rspta = $ticket->getconsec($agenci, $tipoco);        
        if ($rspta) {
            echo $ticket->insertar($agenci, $tipoco . '-' . $rspta, $vended, $prefer, $destin, $tipoco, $factur, $tidoc, $montof) . '|' . $tipoco . '-' . $rspta;
            $_SESSION["impticket"] = $tipoco . '-' . trim($rspta);
            $_SESSION["impdestino"] = $destin;
        }
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: creaticket_det
     * ------------------------------------------------------------------------
     * Inserta un registro de trazabilidad o detalle para el tiquete activo.
     */
    case 'creaticket_det':   
        echo $ticket->insertardet($agenci, $_SESSION["impticket"], $segund, $ubicac, $destin, $codigt, $prefac, $factur, $montof, $estacion);
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: guarda_fact_ticket
     * ------------------------------------------------------------------------
     * Asocia una factura de entrega con su respectivo tiquete de despacho.
     */
    case 'guarda_fact_ticket':    
        echo $ticket->guarda_fact_ticket($codigt, $agenci, $turno, $factur);
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: obtener_imprimir
     * ------------------------------------------------------------------------
     * Devuelve en JSON la información detallada requerida para la impresora térmica.
     */
    case 'obtener_imprimir':            
        $rspta = $ticket->obtener_imprimir($codigo);
        echo json_encode($rspta);
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: opmenu_enc
     * ------------------------------------------------------------------------
     * Renderizador principal del quiosco: construye el encabezado, los botones
     * gráficos (grandes o pequeños) y la barra inferior de navegación.
     */
    case 'opmenu_enc':

        // Configuración de bandera preferencial según el menú consultado
        if ($ubmenu == 'principal') {
            $_SESSION['prefer'] = '0';
        }
        if ($ubmenu == 'preferencial') {
            $ubmenu = 'principal';
            $_SESSION['prefer'] = '1';
        }

        // Vistas especializadas
        if ($ubmenu == 'codigoqr') {
            menuqr();
            break;
        }

        if ($ubmenu == 'codigobr') {
            menubr($agenci, 'E', $_SESSION['prefer'], $vended, $android);
            break;
        }

        if ($ubmenu == 'retirartic') {
            retirartic();
            break;
        }

        if ($ubmenu == 'pasaentreg') {
            pasaentreg();
            break;
        }
        
        // Obtiene datos de configuración del menú (título, subtítulo, tipo de botón)
        $rowenc = $model->opmenu_enc($ubmenu);       
        //print_r($rowenc); 
        ?>
        
        <div align="left" class="col-12" style="padding: 0;">
            <!-- Título y subtítulo institucional del menú -->
            <div class="titulo"> <?=$rowenc["titulo"]?> </div>
            <div class="subtitulo"> <?=$rowenc["subtitulo"]?> </div>

            <?php if ($rowenc["tipobtn"] == 0) { // --- Menú de Botones Grandes (Trámites principales) --- ?>
            <div class="row" style="height: 43vh; overflow-x: hidden; overflow-y: scroll;">
                <?php
                $cont = 0;
                $rspta = $model->opmenu_det($ubmenu);
                
                // Itera para crear las tarjetas interactivas
                while ($rowdet = $rspta->fetch_object()) { 
                    $cont++; 
                    if ($rowdet->submen != '') { // Si tiene submenú asignado
                        if ($android == 1 && $ubmenu == 'entregas') {
                            $accion = "opmenu_enc('principal')";
                            $acc_a = "-T";
                        } else {
                            $accion = "opmenu_enc('" . $rowdet->submen . "');";
                            $acc_a = "#";
                        }
                    } else {
                        // Construye el enlace de protocolo o llamada JavaScript
                        $acc_a = "-";
                        $acc_a .= ($_SESSION['prefer'] == '1') ? 'P' : '';
                        $acc_a .= $rowdet->codigo;

                        if ($android == "1") {
                            // En Android crea el tiquete asíncronamente con JavaScript
                            $accion = "creaticket('" . $agenci . "','" . $rowdet->codigo . "', '" . $_SESSION["prefer"] . "','','',1);";
                            $acc_a = "#";
                        } else {
                            // En Windows la aplicación externa procesa la impresión y se muestra 'retirartic'
                            $accion = "opmenu_enc('retirartic');";
                        }                       
                    }
                ?>
                
                <!-- Tarjeta de botón grande -->
                <div class="col-4"> 
                    <a href="<?=$acc_a?>" onclick="<?=$accion?>">
                    <div class="Mask" style="padding: 0">
                        <div class="Oval">
                            <img src="../assets/images/logos/<?=$rowdet->logome?>" class="imgOval">
                        </div>
                        <div class="tituloMask"><?=$rowdet->descri?></div>
                        <div class="select">SELECCIONAR</div>
                    </div> 
                    </a>                   
                </div>
                <?php } 
                
                // Rellena espacios vacíos con tarjetas invisibles para mantener la simetría
                for ($i = 0; $i < 4 - $cont; $i++) {
                    echo '<div class="col-4"><div class="Masknull" style="padding: 0"></div></div>';
                } 
                ?>
            </div>
            
            <?php } ?>


            <?php if ($rowenc["tipobtn"] == 1) { // --- Menú de Botones Pequeños (Vendedores / Catálogo) --- ?>
                <div class="row" style="max-height: 54vh; overflow-x: hidden; overflow-y: scroll;">
                <?php   
                $cont = 0;
                $rspta = $model->listar_v($agenci, $rowenc["nombre"]);
                
                while ($rowdet = $rspta->fetch_object()) { 
                    $cont++; 
                    $nombre = $rowdet->nombc03;

                    if ($android == "1") {
                        // Llamada JS para plataforma Android
                        $accion = "creaticket('" . $agenci . "', '" . $rowenc["codigo"] . "', '" . $_SESSION["prefer"] . "', '" . $nombre . "','',1);";
                        $acc_a = "#";
                    } else {
                        // Llamada para quioscos Windows
                        $accion = "creaticket('" . $agenci . "','" . $rowenc["codigo"] . "', '" . $_SESSION["prefer"] . "', '" . $nombre . "','',0);";
                        $acc_a = "-" . $rowenc["codigo"] . "=" . $nombre;                        
                    }

                    if (true) {
                    ?>                
                    <!-- Tarjeta de vendedor con foto circular -->
                    <div class="col-4">    
                        <a href="<?=$acc_a?>" onclick="<?=$accion?>">                
                            <div class="MaskP" style="padding: 0">
                                <div class="OvalP">
                                    <div class="circular--landscapeP">
                                        <img src="../assets/vendedores/<?=$rowdet->imagen?>">
                                    </div>
                                </div>
                                <div class="tituloMaskP"><?=$rowdet->nombl03?></div>
                                <div class="selectP">SELECCIONAR</div>
                            </div>                    
                        </a>
                    </div>
                    <?php 
                    }
                }

                // Relleno de espacios vacíos
                for ($i = 0; $i < 6 - $cont; $i++) {
                    echo '<div class="col-3"><div class="MasknullP" style="padding: 0"></div></div>';
                } 
                ?>
            </div>
            
            <?php } ?>

            <!-- Barra inferior con indicador de menú preferencial y botón de regreso / ayuda -->
            <div class="row anchoa">
                <?php if ($_SESSION["prefer"] == '1') { ?>
                    <div class="col-8"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-3 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p class="titbtnfoot">Regresar</p>
                    </div>
                <?php } else { ?>
                    <div class="col-8"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-3 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        break;

}   

/**
 * ============================================================================
 * Funciones Auxiliares de Vistas Especializadas
 * ============================================================================
 */

/**
 * Renderiza la pantalla de instrucciones para escaneo de código QR
 */
function menuqr() {
    $model = new Menus();
    $rowenc = $model->opmenu_enc('codigoqr'); 
    $rspta = $model->opmenu_det('codigoqr');

    while ($rowdet = $rspta->fetch_object()) { ?>
    <div align="center">        
        <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
        <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
        <img class="arrowqr" src="../assets/images/logos/arrow.png">        
    </div>
    <?php } ?>
    
    <!-- Barra inferior -->
    <div class="row">
        <?php if ($_SESSION["prefer"] == '1') { ?>
            <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/regresar.png">
                <p class="titbtnfoot">Regresar</p>
            </div>
        <?php } else { ?>
            <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
            </div>
        <?php } ?>
    </div>
    <?php 
    return false;
}

/**
 * Renderiza la pantalla para escaneo de código de barras con input autofocalizado
 * 
 * @param string $agenci  - Código de la sucursal
 * @param string $tipoco  - Tipo de código
 * @param string $prefer  - Bandera preferencial
 * @param string $vended  - Vendedor
 * @param string $android - Entorno Android
 */
function menubr($agenci, $tipoco, $prefer, $vended, $android = "0") {
    $model = new Menus();
    $rowenc = $model->opmenu_enc('codigobr'); 
    $rspta = $model->opmenu_det('codigobr');

    while ($rowdet = $rspta->fetch_object()) { ?>
    <div align="center">        
        <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
        <!-- Campo de entrada para captura directa del escáner láser de código de barras -->
        <input class="numfac" type="text" autocomplete="off" autofocus id="numfac" name="numfac" onkeyup="onKeyUp(event, '<?=$prefer?>','<?=$android?>','<?=$agenci?>')" />
        <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
        <img class="arrowbr" src="../assets/images/logos/arrow.png">        
    </div>
    <?php } ?>

    <!-- Barra inferior -->
    <div class="row">
        <?php if ($_SESSION["prefer"] == '1') { ?>
            <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/regresar.png">
                <p class="titbtnfoot">Regresar</p>
            </div>
        <?php } else { ?>
            <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
            </div>
        <?php } ?>
    </div>
    <?php 
    return false;
}

/**
 * Renderiza la pantalla que indica al cliente retirar su tiquete impreso
 */
function retirartic() {
    $model = new Menus();
    $rowenc = $model->opmenu_enc('retirartic');
    $rspta = $model->opmenu_det('retirartic');

    while ($rowdet = $rspta->fetch_object()) { ?>
    <div align="center">        
        <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
        <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
        <img class="arrowqr" src="../assets/images/logos/arrow.png">        
    </div>
    <?php } ?>

    <!-- Barra inferior -->
    <div class="row">
        <?php if ($_SESSION["prefer"] == '1') { ?>
            <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/regresar.png">
                <p class="titbtnfoot">Regresar</p>
            </div>
        <?php } else { ?>
            <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
            </div>
        <?php } ?>
    </div>
    <?php 
    return false;
}

/**
 * Renderiza la pantalla de confirmación "Pase a Entregas" tras registrar una factura
 */
function pasaentreg() {
    $model = new Menus();
    $rowenc = $model->opmenu_enc('pasaentreg');
    $rspta = $model->opmenu_det('pasaentreg');

    while ($rowdet = $rspta->fetch_object()) { ?>
    <div align="center">        
        <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
        <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
        <div class="arrowqr"></div>        
    </div>
    <?php } ?>

    <!-- Barra inferior -->
    <div class="row">
        <?php if ($_SESSION["prefer"] == '1') { ?>
            <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/regresar.png">
                <p class="titbtnfoot">Regresar</p>
            </div>
        <?php } else { ?>
            <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
            <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
            </div>
        <?php } ?>
    </div>
    <?php 
    return false;
}

/**
 * Valida mediante un servicio web externo (cURL) si un usuario/vendedor tiene sesión activa
 * 
 * @param string $nombre - Nombre de usuario del vendedor
 * @param int    $local  - Bandera de validación local (1 para omitir cURL)
 * @return bool
 */
function ValidaLogin($nombre, $local = 0) {
    if ($local == 1) {
        return true;
    }
    $ch = curl_init("http://10.0.1.151:84/ws/usractivo.prg?usr=" . $nombre);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($ch);
    curl_close($ch);
    return intval($json) > 0;
}  

?>
