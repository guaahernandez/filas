<?php 
/**
 * Pantalla Principal de Turnos (Turnero / Sala de Espera)
 * 
 * Este archivo representa la vista frontal de la pantalla informativa que se despliega
 * en las salas de espera / áreas de atención al cliente.
 * 
 * Funcionalidades principales:
 * - Configuración de parámetros de sesión según la URL (IP de pantalla, código de agencia/sucursal, destinos de cola).
 * - Reproductor de video publicitario / institucional en bucle.
 * - Pizarra dinámica (#pizarra) donde se listan los turnos llamados en tiempo real.
 * - Barra inferior con marquesina de texto desplazable (#txtmarquee) para anuncios.
 * - Integración con el script pantalla.js para actualización vía AJAX y síntesis de voz.
 */

// Inclusión de configuraciones globales y constantes del sistema
include '../config/global_dat.php';

// Inicialización de la sesión PHP para almacenar los parámetros de la pantalla
session_start();

/**
 * Identificador o IP del equipo / pantalla (usado para asignación de configuración y videos)
 * Por defecto: 'VENTA2_ALA'
 */
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "VENTA2_SED";

/**
 * Código de la agencia o sede donde está ubicada la pantalla
 * Por defecto: 'S-02'
 */
$_SESSION["agenci"] = (isset($_GET["ag"])) ? $_GET["ag"] : "S-01";

/**
 * Destinos o categorías de colas que se mostrarán en esta pantalla (separados por coma)
 * Por defecto: 'V,CC' (Ventas, Cajas/Crédito, etc.)
 */
$_SESSION["destin"] = (isset($_GET["destin"])) ? $_GET["destin"] : "V,CC";

/**
 * Índice y contenido para los mensajes informativos en marquesina
 */
$_SESSION["ntexto"] = 0;
$_SESSION["texto"] = "";

/**
 * Formato de visualización de video asignado a la pantalla ('horizontal' o 'vertical')
 */
$_SESSION["formatovideo"] = "horizontal";
//echo $_SESSION["ip"];
?>


<!DOCTYPE html>
<html dir="ltr" lang="es">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Configuración responsiva para ajuste a la resolución del monitor/pantalla -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta
    name="keywords"
    content="control filas la guaca"
  />
  <meta
    name="description"
    content=<?=PRO_DESCRIP ?>
  />
  <meta name="robots" content="noindex,nofollow" />
  <title>CMS | <?=PRO_NOMBRE?></title>
  <!-- Icono de pestaña / Favicon -->
  <link
    rel="icon"
    type="image/png"
    sizes="16x16"
    href="../assets/images/favicon.png"
  />
  
  <!-- Hojas de estilo base y utilitarios -->
  <link href="../dist/css/style.min.css" rel="stylesheet" />
  
</head>

<body style="background-color: #000000;">
  <!-- ============================================================== -->
  <!-- Preloader de carga inicial -->
  <!-- ============================================================== -->
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>

  <!-- ============================================================== -->
  <!-- Contenedor Principal -->
  <!-- ============================================================== -->
  <div
    id="main-wrapper"
    data-layout="vertical"
    data-navbarbg="skin5"
    data-sidebartype="full"
    data-sidebar-position="absolute"
    data-header-position="absolute"
    data-boxed-layout="full"
  >
    
  <!-- Estilos personalizados para la tipografía, tarjetas de turnos y pie de página -->
  <style>
    /* Definición de la fuente corporativa de alta legibilidad a distancia */
    @font-face {
        font-family: 'Futura';
        src: url('../assets/fuentes/Futura_Extra_Black_Italic.otf');
    }

    /* Estilo de cada celda de turno o número de puesto en la pizarra */
    .num{
        height:11.2vh; 
        background-color: #ffffff;
        color: #000000;
        padding: 0;
        padding-top: 20px;
        margin: 0;
        font-size: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-style: italic;
        font-family: 'Futura', Arial, Helvetica, sans-serif;
        line-height: normal;
        letter-spacing: normal;
        font-stretch: normal;
        border-top: solid 2px #c0c0c0;
    }

    /* Clase de realce / parpadeo para el último turno llamado */
    .fondorojo{
      background-color: #ed1b2f;
      color: #ffffff;
    }

    /* Encabezados de columnas (TURNO / PUESTO) */
    .titulo{
        height:7.5vh; 
        background-color: #1b468c;
        color: #ffe800;        
        padding: 0;
        margin: 0;
        font-size: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-style: italic;
        font-family: 'Futura', Arial, Helvetica, sans-serif;
        line-height: normal;
        letter-spacing: normal;
        font-stretch: normal;
        border-bottom: solid 3px #ffe800;
        border-top: solid 10px #ffe800;
    }

    /* Barra inferior para el logo secundario y marquesina de texto */
    .pieizq{
        height:9vh; 
        background-color: #1b468c;
        color: #ffffff;
        padding-top: 8px;
        margin-right: 0;
        font-size: 36px;
        display: flex;
        align-items: center;
        justify-content: left;
        font-weight: 900;
        font-style: italic;
        font-family: 'Futura', Arial, Helvetica, sans-serif;
        line-height: normal;
        letter-spacing: normal;
        font-stretch: normal;
        border-bottom: solid 3px #ffe800;
        border-top: solid 3px #ffe800;
    }
  
  </style>
      
  <!-- ============================================================== -->
  <!-- Contenedor de la pantalla informativa -->
  <!-- ============================================================== -->
  <div class="container" style="background-color: #ffffff;">
    <!-- ============================================================== -->
    <!-- Sección Superior: Logotipo y Reproductor de Video -->
    <!-- ============================================================== -->
    <div class="row" id="cargavideos" style="text-align: center;">
        <!-- Encabezado con logotipo principal -->
        <div class="col-11" style="height: 9vh;background-color: #1b468c;border-bottom: solid 10px #ed1b2f;">
          <?php //echo $_SESSION["ip"]; ?> 
            <img src="../assets/images/guaca.png" style="margin-top: 10px; width: 400px;" alt="Logo Corporativo">            
        </div>
        <div class="col-1" style="height: 9vh;background-color: #ed1b2f;border-bottom: solid 10px #ed1b2f;">          
           
        </div>
        <!-- Reproductor de video publicitario HTML5 -->
        <div class="col-12" style="height: 18.2vh ; padding: 0px; padding-bottom: 0px;">
            <!--<video controls autoplay name="media" muted width="100%" loop >-->
            <video id="vid" preload="auto" autoplay muted height="100%" width="100%">
                <source src="" type="video/mp4">
            </video>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Sección Central: Pizarra Dinámica de Turnos Llamados -->
    <!-- (Este contenedor se llena asíncronamente mediante AJAX en pantalla.js) -->
    <!-- ============================================================== -->
    <div class="row" id="pizarra" style="text-align: center;">
    </div>

    <!-- ============================================================== -->
    <!-- Sección Inferior: Logotipo y Marquesina de Mensajes -->
    <!-- ============================================================== -->
    <div class="row">
      <!-- Icono / Mascota de la empresa -->
      <div class="col-2 pieizq" style="margin: 0; padding: 0;">
        <img src="../assets/images/Guacamaya.png" height="100%" alt="Logo Guacamaya">
      </div>
      <!-- Texto informativo en movimiento continuo -->
      <div id="pietexto" class="col-10 pieizq">
        <marquee id="txtmarquee" loop="-1">GRACIAS POR SU VISITA</marquee>          
      </div>
    </div>
    
  </div>
  <!-- ============================================================== -->
  <!-- Fin Container fluid -->
  <!-- ============================================================== -->
  </div>
  
  <!-- ============================================================== -->
  <!-- Scripts y Librerías JavaScript Base -->
  <!-- ============================================================== -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- slimscrollbar scrollbar JavaScript -->
  <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  
  <!-- Custom JavaScript de la plantilla -->
  <script src="../dist/js/custom.min.js"></script>
  
</body>
</html>
<!-- Lógica específica de la pantalla de turnos: sondeo AJAX, audio y video -->
<script src="scripts/pantalla.js"></script>