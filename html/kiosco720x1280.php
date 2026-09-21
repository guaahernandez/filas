<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Quiosco Vertical 720x1280 (kiosco720x1280.php)
 * ============================================================================
 * Este archivo representa la interfaz de autoservicio optimizada específicamente
 * para tótems, pantallas verticales o dispositivos Android con resolución 720x1280.
 * 
 * Funcionalidad y Estructura:
 * 1. Encabezado superior institucional (Logo La Guacamaya).
 * 2. Banner superior multimedia con reproductor de video promocional en bucle.
 * 3. Panel inferior interactivo (#botones) con botones y menús cargados vía AJAX.
 * 4. Integración con el motor de emisión de tiquetes y drivers de impresión (Android/QuickPrinter).
 * ============================================================================
 */

// Inclusión de parámetros globales del sistema
include '../config/global_dat.php'; 

// Limpia la caché de estado de archivos del servidor
clearstatcache();

// Inicio o reanudación de la sesión PHP para almacenar los parámetros del quiosco
session_start();

// Asignación de la dirección IP o identificador del quiosco (por GET o valor por defecto)
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "QUIOSCO_SED";

// Impresora asignada (opcional / configurable según la estación de trabajo)
//$_SESSION["im"] = (isset($_GET["im"])) ? $_GET["im"] : "EPSON TM-T88V Receipt";

// Código de la sucursal o agencia donde opera el quiosco (por GET o valor por defecto)
$_SESSION["agenci"] = (isset($_GET["ag"])) ? $_GET["ag"] : "S-01";

// En resolución 720x1280 el video se ubica como banner superior horizontal
$_SESSION["formatovideo"] = "horizontal";

// Bandera para indicar ejecución en entorno Android (habilita flujos de tiquete móviles)
$_SESSION["android"] = (isset($_GET["android"])) ? $_GET["android"] : "1";
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Configuración para diseño responsivo en navegadores de quiosco -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

<style>
    /* ========================================================================
       Fuentes Personalizadas
       ======================================================================== */
    @font-face {
        font-family: 'Futura';
        src: url('../assets/fuentes/Futura_Extra_Black_Italic.otf');
    }
    @font-face {
        font-family: 'Futurabold';
        src: url('../assets/fuentes/Futura_Bold_font.ttf');
    }

    /* ========================================================================
       Encabezado Superior (Header)
       ======================================================================== */
    /* Barra principal azul con borde inferior rojo */
    .ex1 {
        height: 128px;
        margin: 0;
        padding: 0;
        background-color: #0f3e7f; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: solid 5px #fe2312;  
        vertical-align: middle;     
    }

    /* Tamaño del logo institucional dentro del encabezado */
    .logo{
      height: 76.8px;
    }

    /* Franja roja decorativa lateral */
    .ex2 {        
        height: 128px;
        margin: 0;
        padding: 0;
        background-color: #fe2312;        
    }

    .img {
        width: 383.9px;
        height: 104px;
        object-fit: contain;
    }

    /* ========================================================================
       Contenedor de Video y Panel de Menús
       ======================================================================== */
    /* Contenedor del reproductor de video en la parte superior */
    .cont_video {
        background-color: #f6f7fb;
        height: 232.96px;
    }

    /* Panel interactivo inferior donde se inyectan los botones */
    .panel {
        background-color: #f6f7fb;  
    }

    /* ========================================================================
       Tipografía y Títulos de Menú
       ======================================================================== */
    .titulo {
      margin-left: 28.8px;
      margin-top: 38.4px;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 32px;
      height: 48.64px;
      color: #0f3e7f;
    }

    .subtitulo {
      margin-left: 28.8px;
      margin-bottom: 10.8px;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 17.92px;
      height: 25.6px;
      color: #3d3d3d;
    }

    /* ========================================================================
       Tarjetas de Botones Grandes (Menú Principal de Trámites)
       ======================================================================== */
    .Mask {
      width: 172px;
      height: 226px;  
      margin: 19.2PX 28.8px 0 28.8px;
      border-radius: 20px;
      border: solid 1px #0f3e7f;
      color: #0f3e7f;
      background-color: #fff;
    }

    /* Tarjeta invisible para mantener la cuadrícula alineada */
    .Masknull {
        width: 172.8px;
        height: 256px;  
        margin: 19.2PX 28.8px 0 28.8px;
    }

    .Mask:hover{
      background-color: #0f3e7f;
      color: #fff;
    }

    /* Contenedor circular del icono */
    .Oval {
      width: 100px;
      height: 100px;
      margin-top: 20px;
      margin-left: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: solid 2px #fe2312;
      background-color: #f6f7fb;
      border-radius: 50px;
    }

    .imgOval{
      width: 50px;
    }

    .tituloMask {
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 19.2px;
      height: 64px;
      font-weight: 900;
      font-stretch: normal;
      font-style: italic;
      line-height: 1.2;
      letter-spacing: normal;
      text-align: center;
    }

    /* Texto para menú de atención preferencial */
    .menupref {
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 35px;
      font-weight: 900;
      letter-spacing: 2px;
      color: #c0c0c0;
      text-align: center;
    }

    .titbtnfoot{
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 15px;
      font-weight: 900;
      letter-spacing: 1px;
      color: #0f3e7f;
    }

    .select {
      width: 172px;
      height: 40px;
      background-color: #fe2312;
      font-family: 'Futurabold', Arial, Helvetica, sans-serif;
      font-size: 1.5vh;
      letter-spacing: 1px;
      color: #fff;
      border-bottom-left-radius: 19px;
      border-bottom-right-radius: 19px;
      margin-left: -1px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ========================================================================
       Pie de Página y Acciones de Retorno
       ======================================================================== */
    .titulofoot {
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 15px;
      font-weight: 900;
      font-stretch: normal;
      font-style: italic;
      line-height: 2.67;
      letter-spacing: normal;
      color: #0f3e7f;
    }

    .imgfoot{
      margin-top: 96px;
      width: 45px;
    }

    .titulofoot:hover{
        background-color: #ffe8e8;
    }

    .contenedorbotones{
      height: 44vh;
    }

    /* Campo de entrada para escaneo de facturas */
    .numfac{
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 2.5vh;
      width: 60vw;
      text-align: center;
      border: 0;
      border-bottom: 1px solid;
      background-color: transparent;
      padding-top: 2vh;
      margin-bottom: 1vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* ========================================================================
       Variantes de Botones Pequeños con Unidades Relativas (vh / vw)
       ======================================================================== */
    .MaskP {
      width: 24vw;
      height: 20vh;  
      margin: 1.5vh 4vw 0 4vw;
      border-radius: 20px;
      border: solid 1px #0f3e7f;
      background-color: #fff;
      color: #0f3e7f;
    }

    .MaskP:hover{
      background-color: #0f3e7f;
      color: #fff;
    }

    .MasknullP {
      width: 24vw;
      height: 20vh;  
      margin: 0px 4vw;
    }

    .OvalP {
      width: 14vw;
      height: 8vh;
      margin-top: 2vh;
      margin-left: 5vw;
      display: flex;
      align-items: center;
      justify-content: center;
      border: solid 7px #fe2312;
      background-color: #f6f7fb;
      border-radius: 7vw;
    }

    .tituloMaskP {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 7vh;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 1.5vh;
      font-weight: 900;
      font-stretch: normal;
      font-style: italic;
      line-height: 1.17;
      letter-spacing: normal;
      text-align: center;
    }

    .selectP {
      width: 24vw;
      height: 2.85vh;
      background-color: #fe2312;
      font-family: 'Futurabold', Arial, Helvetica, sans-serif;
      font-size: 1.5vh;
      font-weight: bold;
      font-stretch: normal;
      font-style: normal;
      line-height: normal;
      letter-spacing: 1px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      border-bottom-left-radius: 19px;
      border-bottom-right-radius: 19px;
    }

    /* ========================================================================
       Avatares Circulares para Vendedores y Categorías
       ======================================================================== */
    .circular--landscape {
      display: inline-block;
      position: relative;
      width: 128px;
      height: 128px;
      overflow: hidden;
      border-radius: 50%;
    }

    .circular--landscape img {
      width: auto;
      height: 100%;
      margin-left: 0px;
    }

    .circular--landscapeP {
      display: inline-block;
      position: relative;
      width: 14vw;
      height: 7vh;
      overflow: hidden;
      border-radius: 50%;
    }

    .circular--landscapeP img {
      width: auto;
      height: 100%;
      margin-left: 0px;
    }

    /* ========================================================================
       Elementos para el Escaneo de Códigos QR y Códigos de Barras
       ======================================================================== */
    .logoqr{
      height: 16vh;
      margin-top: 5.8vh;
      margin-left: 0px;
    }

    .tituloqr {
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 2vh;
      color: #0f3e7f;
      width: 80vw;
      height: 10vh;
      margin-bottom: 3vh;
      margin-left: 0px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .arrowqr{
      height: 6vh;
      width: 10vw;
      margin-bottom: 9vh;
      margin-left: 0px;
    }

    .arrowbr{
      height: 10vh;
      padding-bottom: 5vh;
    }
</style>

<script>
  /**
   * Solicita el modo de pantalla completa en el navegador del quiosco
   */
  function fullScreen() {
    var el = document.documentElement;
    var rfs = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullscreen;
    if (rfs) {
      $('#pantalla').hide();
      rfs.call(el);
    }
  }
</script>
    
<!-- Hojas de estilo generales del tema -->
<link href="../dist/css/style.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.3/JsBarcode.all.min.js"></script>

<script>
/**
 * Envía el trabajo de impresión a la app de impresión rápida
 */
async function sendToQuickPrinter(){
  await Imprimir();
}
</script>

  </head>
  <body>
  
  <!-- Contenedor Principal Centralizado -->
  <div id="contenedor" align="center">
    
    <!-- 1. Encabezado Institucional (Logo y Barra de Identidad) -->
    <div class="row" style="margin: 0; padding: 0;">
        <div class="ex1 col-11">
            <img class="logo" src="../assets/images/guaca.png">
        </div>
        <div class="ex2 col-1"></div>
    </div>
    
    <!-- 2. Banner Superior: Reproductor de Video Promocional en Bucle -->
    <div class="row" style="margin: 0; padding: 0;">
        <div class="col-12 cont_video" style="padding: 0; margin: 0;">
          <video style="height: 100%;" id="vid" preload="auto" autoplay muted width="100%">
            <source src=""  type="video/mp4">
          </video>
        </div>
    </div>
    
    <!-- 3. Panel Inferior: Contenedor Dinámico para Botones y Menús -->
    <div id="botones" class="row panel" style="margin: 0; padding: 0;">
        
    </div>

  </div>

    <!-- ======================================================================
         Librerías JavaScript
         ====================================================================== -->
    <!-- jQuery: Manipulación del DOM y peticiones asíncronas -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- Bootstrap Tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Librerías de Scroll y Gráficos -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    
    <!-- Custom JavaScript del tema -->
    <script src="../dist/js/custom.min.js"></script>
  </body>
</html>

<!-- ==========================================================================
     Scripts del Negocio para el Quiosco
     ========================================================================== -->
<!-- kiosco.js: Inicialización de la vista, carga del menú base y gestor de videos -->
<script src="scripts/kiosco.js"></script>
<!-- menus.js: Navegación de opciones, emisión de tiquetes y captura de código de barras -->
<script src="scripts/menus.js"></script>
