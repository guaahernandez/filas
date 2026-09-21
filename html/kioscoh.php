<?php 
/**
 * ============================================================================
 * Sistema de Control de Filas - Módulo de Quiosco Horizontal (kioscoh.php)
 * ============================================================================
 * Este archivo representa la interfaz del quiosco de autoservicio en formato
 * horizontal (pantalla ancha / landscape) para la emisión de turnos y tiquetes.
 * 
 * Funcionalidad principal:
 * - Configuración de la sesión del quiosco (identificador IP, agencia, formato).
 * - Estructura visual dividida en dos columnas:
 *     1. Reproductor de video promocional / informativo en bucle continuo.
 *     2. Panel interactivo para la selección de trámites, atención preferencial,
 *        vendedores o escaneo de facturas (cargado dinámicamente vía AJAX).
 * - Carga de estilos personalizados y scripts controladores (kiosco.js, menus.js).
 * ============================================================================
 */

// Inclusión de parámetros globales del sistema (constantes de conexión, nombres, etc.)
include '../config/global_dat.php'; 

// Inicio o reanudación de la sesión PHP para almacenar los parámetros del quiosco
session_start();

// Asignación de la dirección IP o identificador del quiosco (por GET o valor por defecto)
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "QUIOSCO01";

// Impresora asignada (opcional / configurable según la estación de trabajo)
//$_SESSION["im"] = (isset($_GET["im"])) ? $_GET["im"] : "EPSON TM-T88V Receipt";

// Código de la sucursal o agencia donde opera el quiosco (por GET o valor por defecto)
$_SESSION["agenci"] = (isset($_GET["ag"])) ? $_GET["ag"] : "S-01"; // Determina la agencia activa

// Formato o tipo de video a consultar para la reproducción promocional
$_SESSION["formatovideo"] = "vertical";

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Configuración para diseño responsivo en navegadores de quiosco -->
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
    
    <!-- Ícono de la pestaña / Favicon -->
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="../assets/images/favicon.png"
    />

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
       Estructura del Encabezado Superior (Header)
       ======================================================================== */
    /* Barra principal azul del encabezado con línea inferior roja */
    .ex1 {
        width: 990px;
        height: 85px;
        margin: 0;
        padding: 0;
        background-color: #0f3e7f; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: solid 5px #fe2312;       
    }

    /* Franja roja decorativa lateral del encabezado */
    .ex2 {
        width: 34px;
        height: 85px;
        margin: 0;
        padding: 0;
        background-color: #fe2312;        
    }

    /* Dimensiones fijas y relativas para las tablas del quiosco */
    .ancho{
        width: 1024px;
    }  
    .anchoa{
        width: 99%;
    }   

    /* ========================================================================
       Distribución de Columnas (Video Promocional vs Panel de Menús)
       ======================================================================== */
    /* Columna izquierda: Contenedor del video promocional */
    .slide {
        width: 384px;
        height: 678px;
        background-color: #f6f7fb;
        display: inline-block;
        margin: 0;
        padding: 0;
    }

    /* Columna derecha: Panel interactivo donde se renderizan los botones */
    .panel {
        background-color: #f6f7fb;
        width: 640px; 
    }

    /* ========================================================================
       Tipografía y Títulos de Menús
       ======================================================================== */
    /* Título principal del menú de selección */
    .titulo {
      margin-left: 0px;
      margin-top: 35px;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 22px;
      font-weight: 900;
      font-stretch: normal;
      line-height: 1.27;
      letter-spacing: normal;
      color: #0f3e7f;
    }

    /* Subtítulo informativo / guía de usuario */
    .subtitulo {
        margin-left: 0px;
        margin-bottom: 25px;
        font-family: 'Futura', Arial, Helvetica, sans-serif;
        font-size: 12px;
        font-weight: 500;
        font-stretch: normal;
        font-style: italic;
        line-height: 2.54;
        letter-spacing: normal;
        color: #3d3d3d;
    }

    /* ========================================================================
       Tarjetas de Botones de Menú Estándar (Tamaño Regular)
       ======================================================================== */
    /* Botón/Tarjeta principal interactiva */
    .Mask {
      width: 160px;
      height: 180px;
      margin-left: 0px;
      margin-bottom: 45px;
      border-radius: 20px;
      border: solid 1px #0f3e7f;
      color: #0f3e7f;
      background-color: #fff;
    }

    /* Espaciador invisible para mantener la cuadrícula cuando no hay botón */
    .Masknull {
      width: 160px;
      height: 180px;
      margin-left: 0px;
      margin-bottom: 25px;
    }

    /* Efecto hover al pasar cursor o tocar botón estándar */
    .Mask:hover{
      background-color: #0f3e7f;
      color: #fff;
    }

    /* Contenedor circular para el icono dentro de la tarjeta */
    .Oval {
      width: 80px;
      height: 80px;
      margin: 10px 40px 0px 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: solid 3px #fe2312;
      background-color: #f6f7fb;
      border-radius: 64.5px;
    }

    /* Imagen o icono dentro del contenedor circular */
    .imgOval{
      width: 45px;
    }

    /* Texto descriptivo del servicio/trámite en el botón */
    .tituloMask {
      /* margin-top: 17px; */
      display: flex;
      align-items: center;
      justify-content: center;
      height: 63px;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 16px;
      font-weight: 900;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.2;
      letter-spacing: normal;
      text-align: center;
      /* color: #0f3e7f; */
    }

    /* Estilo del botón / opción de atención preferencial */
    .menupref {
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 30px;
      font-weight: 900;
      letter-spacing: 2px;
      color: #c0c0c0;
    }

    /* Etiqueta / Franja roja inferior en el botón */
    .select {
      width: 160px;
      height: 25px;
      background-color: #fe2312;
      font-family: Futurabold;
      font-size: 13px;
      font-style: normal;
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
       Pie de Página / Botón de Ayuda o Regreso
       ======================================================================== */
    .titulofoot {
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 8px;
      font-weight: 900;
      font-stretch: normal;
      font-style: italic;
      line-height: 2.67;
      letter-spacing: normal;
      color: #0f3e7f;
    }

    .imgfoot{
      width: 30px;
    }

    .titulofoot:hover{
        background-color: #ffe8e8;
    }

    /* ========================================================================
       Variantes de Botones Pequeños (MaskP) para menús con mayor densidad
       ======================================================================== */
    .MaskP {
      width: 160px;
      height: 180px;
      margin-left: 0px;
      margin-bottom: 35px;
      border-radius: 20px;
      border: solid 1px #0f3e7f;
      color: #0f3e7f;
      background-color: #fff;
    }

    .MaskP:hover{
      background-color: #0f3e7f;
      color: #fff;
    }
    
    .MasknullP {
      width: 160px;
      height: 180px;
      margin-left: 0px;
      margin-bottom: 30px;
    }

    .OvalP {
      width: 80px;
      height: 80px;
      margin: 10px 40px 0px 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: solid 3px #fe2312;
      background-color: #f6f7fb;
      border-radius: 50%;
    }

    .tituloMaskP {
      /* margin-top: 17px; */
      display: flex;
      align-items: center;
      justify-content: center;
      height: 63px;
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 16px;
      font-weight: 900;
      font-stretch: normal;
      font-style: italic;
      line-height: 1.2;
      letter-spacing: normal;
      text-align: center;
      /* padding-right: 5px; */
      /* padding-left: 5px; */
      /* color: #0f3e7f; */
    }

    .selectP {
      width: 160px;
      height: 25px;
      /* margin: 0px 30px 30px 70px; */
      /* padding: 12.6px 44px 8px 60px; */
      background-color: #fe2312;
      font-family: 'Futurabold', Arial, Helvetica, sans-serif;
      font-size: 11px;
      font-style: normal;
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
       Contenedores Circulares para Fotos / Avatares (Vendedores / Categorías)
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
      width: 80px;
      height: 75px;
      overflow: hidden;
      border-radius: 50%;
    }

    .circular--landscapeP img {
      width: auto;
      height: 100%;
      margin-left: 0px;
    }

    /* ========================================================================
       Elementos para el Flujo de Escaneo de Facturas y Códigos QR / Barras
       ======================================================================== */
    /* Contenedor del logo/icono de código QR */
    .logoqr{
      height: 260px;
      padding-top: 90px;
      margin-left: -70px;
    }
    
    /* Campo de entrada para el número de factura */
    .numfac{
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 25px;
      width: 400px;
      text-align: center;
      border: 0;
      border-bottom: 1px solid;
      background-color: transparent;
      /* padding-top: 50px;
      padding-bottom: 60px; */
      height: 50px;
      margin-left: -70px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    /* Mensaje o título de instrucción para escanear QR / Factura */
    .tituloqr {
      font-family: 'Futura', Arial, Helvetica, sans-serif;
      font-size: 25px;
      color: #0f3e7f;
      width: 400px;
      /* padding-top: 50px;
      padding-bottom: 60px; */
      height: 200px;
      margin-left: -70px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Flecha o animación indicadora de posición de escaneo QR */
    .arrowqr{
      width: 40px;
      padding-bottom: 80px;
      margin-left: -70px;
    }

    /* Flecha o animación indicadora de código de barras */
    .arrowbr{
      width: 40px;
      padding-bottom: 30px;
      margin-left: -70px;
    }

</style>
    
    <!-- Hojas de estilo generales del tema / Dashboard -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    
  </head>

  <body>

  <!-- Contenedor centralizado para la pantalla del quiosco -->
  <div align="center">
    
    <!-- Encabezado superior institucional (Logo de La Guacamaya) -->
    <table class="ancho">
        <tr>
            <td class="ex1">
              <img src="../assets/images/guaca.png" width="15%">
            </td>
            <td class="ex2">
              
            </td>
        </tr>
    </table>

    <!-- Estructura principal de 2 columnas: Video Promocional a la izquierda y Botones a la derecha -->
    <table class="ancho">
        <tr>
            <!-- Columna Izquierda: Reproductor multimedia para videos informativos en bucle -->
            <td class="slide">
                <video id="vid" preload="auto" autoplay muted height="100%" width="100%" style="margin: 0; padding: 0;">
                    <source src=""  type="video/mp4">
                </video>
            </td>

            <!-- Columna Derecha: Contenedor dinámico donde se inyectan los menús y botones vía AJAX -->
            <td class="panel" style="padding-left: 70px;">
                <div id="botones">        
                </div>
            </td>
        </tr>       
    </table>
    
    <!-- ======================================================================
         Librerías y Dependencias JavaScript
         ====================================================================== -->
    <!-- jQuery: Manejo del DOM y peticiones AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap: Componentes y utilidades de interfaz -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Perfect Scrollbar y Sparkline: Estilizado de scroll y componentes gráficos -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>

    <!-- Custom JavaScript del tema -->
    <script src="../dist/js/custom.min.js"></script>
  </body>
</html>

<!-- ==========================================================================
     Scripts del Negocio para el Quiosco
     ========================================================================== -->
<!-- kiosco.js: Inicializa la pantalla, carga el menú principal y gestiona la lista de videos -->
<script src="scripts/kiosco.js"></script>
<!-- menus.js: Controla la navegación entre niveles de menú, emisión de turnos e inputs de facturas -->
<script src="scripts/menus.js"></script>
