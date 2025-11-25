<?php 
include '../config/global_dat.php'; 

clearstatcache();

session_start();
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "QUIOSCO_SRM";
//$_SESSION["im"] = (isset($_GET["im"])) ? $_GET["im"] : "EPSON TM-T88V Receipt";
$_SESSION["agenci"] = (isset($_GET["ag"])) ? $_GET["ag"] : "S-15";//ver si cuando se carga en aplic envia la agencia
$_SESSION["formatovideo"] = "horizontal";
$_SESSION["android"] = (isset($_GET["android"])) ? $_GET["android"] : "1"; //cuando es android 
//echo "agenci: " . $_SESSION["agenci"];
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

<style>
  


    @font-face {
        font-family: 'Futura';
        src: url('../assets/fuentes/Futura_Extra_Black_Italic.otf');
    }
    @font-face {
        font-family: 'Futurabold';
        src: url('../assets/fuentes/Futura_Bold_font.ttf');
    }
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

    .logo{
      height: 76.8px;
    }

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

    .cont_video {
        background-color: #f6f7fb;
        height: 232.96px;
        }
    .panel {
        /*height: 68vh;*/
        /* max-height: 48vh; */
        background-color: #f6f7fb;  
        }

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

.Mask {
  width: 172px;
  height: 226px;  
  margin: 19.2PX 28.8px 0 28.8px;
  border-radius: 20px;
  border: solid 1px #0f3e7f;
  color: #0f3e7f;
  background-color: #fff;
}

.Masknull {
    width: 172.8px;
    height: 256px;  
    margin: 19.2PX 28.8px 0 28.8px;
}

.Mask:hover{
  background-color: #0f3e7f;
  color: #fff;
}

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
  /* margin-top: 44.4px; */
  /* padding: 12.6px 44px 8px 60px; */
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
  
  /* margin-left: 30vw; */
  display: flex;
  align-items: center;
  justify-content: center;
}

/**botones pequeños */
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
  /* margin-top: 44.4px; */
  /* padding: 12.6px 44px 8px 60px; */
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

  function fullScreen() {
    //alert('Full Screen');
    // Supports most browsers and their versions
    var el = document.documentElement;
    var rfs = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullscreen;
    if (rfs) {
      $('#pantalla').hide();
      // Call the fullScreen function on the element
      rfs.call(el);
    }
  //document.documentElement.requestFullscreen();
  //document.documentElement.webkitRequestFullscreen();
  //document.documentElement.mozRequestFullscreen();
  }
</script>
    
<!-- Custom CSS -->
<link href="../dist/css/style.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.3/JsBarcode.all.min.js"></script>

<script>

async function sendToQuickPrinter(){
  await Imprimir();
  //await fullScreen();
}

async function Imprimir() {
   /* var text = "";//<center><IMAGE>http://ws.laguacamaya.cr:13565/filas/assets/images/Guaca2.png<br><br>";
      text += "<center><big><bold>Repuestos La Guaca<br><br>";
      text += "<center><big>BIENVENIDO(A)<br><br><center><MEDIUM1>SEDE CENTRAL<br><br><center><MEDIUM2>Su turno es<br>";
      text+= "<dline0><br><center><big><bold>V-1<br><dline0><br>";
      text+= "<center><MEDIUM3>Vengo a Ventas<br><br>";
      text+= "<center>Emision:<br>";
      text+= "<center><QR>123456789<br>";
      text+= "<cut>";
      var textEncoded = encodeURI(text);
      window.location.href="quickprinter://"+textEncoded;
  }*/
//if you are using latest version of chrome browser I recommend to use:
// function sendToQuickPrinterChrome(){
    //  var text = "test printer<br><big>Big title<br><cut>";
    //  var textEncoded = encodeURI(text);
    //  window.location.href="intent://"+textEncoded+"#Intent;scheme=quickprinter;package=pe.diegoveloper.printerserverapp;end;";
 }
</script>



  </head>
  <!-- <a onclick="sendToQuickPrinter();">Print Button</a>
  <br>
  <div id="pantalla" style="text-align: center; margin: 10px; border-radius: 3px;">
    <a class="btn btn-info" onclick="fullScreen();">PANTALLA COMPLETA</a>
  </div> -->
  <body>
  <!-- <p>
    Pantalla: 
    <script>     
      document.write (screen.availWidth + 'x' + screen.availHeight) 
    </script>
  </p> -->
  <div id="contenedor" align="center">
    <div class="row" style="margin: 0; padding: 0;">
        <div class="ex1 col-11">
            <img class="logo" src="../assets/images/guaca.png">
        </div>
        <div class="ex2 col-1"></div>
    </div>
    <div class="row" style="margin: 0; padding: 0;">
        <div class="col-12 cont_video" style="padding: 0; margin: 0;">
          <video style="height: 100%;" id="vid" preload="auto" autoplay muted width="100%">
            <source src=""  type="video/mp4">
          </video>
        </div>
    </div>
    <div id="botones" class="row panel" style="margin: 0; padding: 0;">
        
    </div>

  </div>

    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
  </body>
</html>
<script src="scripts/kiosco.js"></script>
<script src="scripts/menus.js"></script>

