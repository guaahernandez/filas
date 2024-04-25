<?php 
include '../config/global_dat.php'; 
session_start();
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "192.168.56.1";
//$_SESSION["im"] = (isset($_GET["im"])) ? $_GET["im"] : "EPSON TM-T88V Receipt";
$_SESSION["agenci"] = (isset($_GET["ag"])) ? $_GET["ag"] : "1";//ver si cuando se carga en aplic envia la agencia
$_SESSION["formatovideo"] = "horizontal";
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
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
    <!-- Favicon icon -->
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="../assets/images/favicon.png"
    />

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
        width: 1080px;
        height: 200px;
        margin: 0;
        padding: 0;
        background-color: #0f3e7f; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: solid 5px #fe2312;       
    }

    .ex2 {
        width: 74px;
        height: 200px;
        margin: 0;
        padding: 0;
        background-color: #fe2312;
        
    }

    .ancho{
        width: 1080px;
    }

    .anchoa{
        width: 1080px;
    }

    .img {
        width: 383.9px;
        height: 104px;
        object-fit: contain;
    }

    .slide {
        height: 348.2px;
        background-color: #f6f7fb;
        }
    .panel {
        height: 1375px;
        background-color: #f6f7fb;
        }

.titulo {
  margin-left: 50px;
  margin-top: 50px;
  height: 61px;
  font-family: 'Futura', Arial, Helvetica, sans-serif;
  font-size: 48px;
  color: #0f3e7f;
}

.subtitulo {
  margin-left: 50px;
  margin-bottom: 56px;
  height: 61px;
  font-family: 'Futura', Arial, Helvetica, sans-serif;
  font-size: 24px;
  color: #3d3d3d;
}

.Mask {
  width: 259.2px;
  height: 298px;
  margin: 0px 99.8px 35px 51px;
  padding: 45.1px 0.2px 0 0px;
  border-radius: 20px;
  border: solid 1px #0f3e7f;
  color: #0f3e7f;
  background-color: #fff;
}

.Masknull {
  width: 259.2px;
  height: 298px;
  margin: 0px 99.8px 35px 51px;
  padding: 45.1px 0.2px 0 0px;
}

.Mask:hover{
  background-color: #0f3e7f;
  color: #fff;
}

.Oval {
  width: 129px;
  height: 129px;
  margin: 23px 65px 0px 65px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: solid 7px #fe2312;
  background-color: #f6f7fb;
  border-radius: 64.5px;
}

.imgOval{
  width: 55px;
}

.tituloMask {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 99px;
  font-family: 'Futura', Arial, Helvetica, sans-serif;
  font-size: 26px;
  font-weight: 900;
  font-stretch: normal;
  font-style: italic;
  line-height: 1.2;
  letter-spacing: normal;
  text-align: center;
  padding-right: 5px;
  padding-left: 5px;
}

.menupref {
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Futura', Arial, Helvetica, sans-serif;
  font-size: 40px;
  font-weight: 900;
  letter-spacing: 2px;
  color: #c0c0c0;
  text-align: center;
}

.select {
  width: 259.2px;
  height: 45px;
  /* margin-top: 44.4px; */
  /* padding: 12.6px 44px 8px 60px; */
  background-color: #fe2312;
  font-family: 'Futurabold', Arial, Helvetica, sans-serif;
  font-size: 21px;
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
  font-size: 18px;
  font-weight: 900;
  font-stretch: normal;
  font-style: italic;
  line-height: 2.67;
  letter-spacing: normal;
  color: #0f3e7f;
}

.imgfoot{
  width: 56px;
}
.titulofoot:hover{
    background-color: #ffe8e8;
}

/**botones pequeños */
.MaskP {
  width: 221px;
  height: 298px;
  margin: 10px 20px 20px 20px;
  padding: 25.3px 0 0;
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
  width: 221px;
  height: 298px;
  margin: 10px 20px 20px 20px;
  padding: 25.3px 0 0;
}

.OvalP {
  width: 144px;
  height: 144px;
  margin: 30px 38px 0px 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: solid 7px #fe2312;
  background-color: #f6f7fb;
  border-radius: 72px;
}

.tituloMaskP {
  /* margin-top: 17px; */
  display: flex;
  align-items: center;
  justify-content: center;
  height: 84px;
  font-family: 'Futura', Arial, Helvetica, sans-serif;
  font-size: 24px;
  font-weight: 900;
  font-stretch: normal;
  font-style: italic;
  line-height: 1.17;
  letter-spacing: normal;
  text-align: center;
  padding-right: 5px;
  padding-left: 5px;
  /* color: #0f3e7f; */
}

.selectP {
  width: 219px;
  height: 38px;
  /* margin-top: 44.4px; */
  /* padding: 12.6px 44px 8px 60px; */
  background-color: #fe2312;
  font-family: 'Futurabold', Arial, Helvetica, sans-serif;
  font-size: 20px;
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
  width: 130px;
  height: 130px;
  overflow: hidden;
  border-radius: 50%;
}

.circular--landscapeP img {
  width: auto;
  height: 100%;
  margin-left: 0px;
}

.logoqr{
  height: 439px;
  margin-top: 220px;
  margin-left: 0px;
}

.tituloqr {
  font-family: 'Futura', Arial, Helvetica, sans-serif;
  font-size: 50px;
  color: #0f3e7f;
  width: 800px;
  /* padding-top: 50px;
  padding-bottom: 60px; */
  height: 360px;
  margin-left: 0px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.arrowqr{
  width: 80px;
  padding-bottom: 140px;
  margin-left: 0px;
}
</style>
    
<!-- Custom CSS -->
<link href="../dist/css/style.min.css" rel="stylesheet" />
    
  </head>

  <body>

  <div id="contenedor" align="center">
    <div class="row ancho">
        <div class="ex1 col-11">
            <img src="../assets/images/guaca.png">
        </div>
        <div class="ex2 col-1"></div>
    </div>
    <div class="row ancho">
        <div class="col-12 slide" style="padding: 0;">
          <video id="vid" preload="auto" autoplay muted height="100%" width="100%">
            <source src=""  type="video/mp4">
          </video>
        </div>
    </div>
    <div id="botones" class="row ancho panel">
        
    </div>

  </div>

    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>

    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
  </body>
</html>
<script src="scripts/kiosco.js"></script>
<script src="scripts/menus.js"></script>

