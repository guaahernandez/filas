<?php 
include '../config/global_dat.php'; 
session_start();
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "192.168.56.1";
$_SESSION["im"] = (isset($_GET["im"])) ? $_GET["im"] : "EPSON TM-T88V Receipt";
$_SESSION["agenci"] = (isset($_GET["ag"])) ? $_GET["ag"] : "S-07";//ver si cuando se carga en aplic envia la agencia
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
      .titulo{
        text-align: left; 
        padding: 30px 5px 50px;
        font-size: 24px;
        /* display: flex;
        align-items: center;
        justify-content: center; */        
        font-weight: 900;
        font-stretch: normal;
        font-style: italic;
        line-height: 1.39;
        letter-spacing: normal;        
        color: #3d3d3d;
        font-family: 'Futura',Arial, Helvetica, sans-serif;
      }

      .texto{
        text-align: center; 
        font-size: 18px;
        font-weight: 900;
        font-stretch: normal;
        font-style: italic;
        line-height: normal;
        letter-spacing: normal;        
        color: #0f3e7f;
        margin-top: 35px;
        height: 40px;
        /* background-color: #3d3d3d; */
        font-family: 'Futura',Arial, Helvetica, sans-serif;
      }

      .mCard{
        border: solid 1px #0f3e7f; 
        height: 30vh; 
        margin-left: 10px; 
        margin-right: 10px;
        margin-bottom: 25px;
        border-radius: 10px;
        background-color: #f6f7fb;
      }

      .mCardlogo{
        border: solid 3px #fe2312; 
        margin-top: 35px; 
        width: 90px; 
        height: 90px;
        border-radius: 45px;
        background-color: #ffffff;
        font-size: 55px;
        color: #0f3e7f;
      }
    </style>
    
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body style="background-color: #000000;">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div align="center"
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      
        
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div style="background-color: #ffffff; width: 1380px; padding: 0; margin: 0;">
          <!-- ============================================================== -->
          <!-- Start Page Content -->
          <!-- ============================================================== -->
            <div class="row" id="cargavideos" style="margin: 0; padding: 0;">
              
              <div class="col-11" style="height: 9vh;background-color: #1b468c;border-bottom: solid 10px #fe2312;">
                <img src="../assets/images/guaca.png" style="margin-top: 10px; width: 200px;" alt="">
              </div>
              <div class="col-1">            
                <div class="row">
                  <div class="col-9" style="height: 9vh; background-color: #1b468c;border-bottom: solid 10px #fe2312;"></div>
                  <div class="col-3" style="height: 9vh; margin: 0; padding: 0; background-color: #fe2312;border-bottom: solid 10px #fe2312;"></div>
                </div>
              </div>
                
              <div class="col-5" style="height: 90vh; margin: 0; padding: 0; background-color: #c0c0c0">  
                <img id="promo" src="" width="100%" height="100%" alt="">
                <!-- <img id="promo" src="https://citas.laguaca.cr/imagenes/tlinea/junio23.png" width="100%" height="100%" alt="">-->
              </div>
              <div class="col-7" id="botones" style="margin: 0; padding: 40px; max-height: 90vh; overflow-y: auto;"></div>
            </div>
            
            
          <!-- ============================================================== -->
          <!-- End PAge Content -->
          <!-- ============================================================== -->
          
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php //include 'footer.php' ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!--<script src="../assets/libs/jquery/dist/jquery.min.js"></script>-->
    
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    
    <script>
      /****************************************
       *       Basic Table                   *
       ****************************************/
      //$("#zero_config").DataTable();
    </script>
  </body>
</html>
<script src="scripts/menus.js"></script>

<script>
function getFullscreen(element){
  if(element.requestFullscreen) {
      element.requestFullscreen();
    } else if(element.mozRequestFullScreen) {
      element.mozRequestFullScreen();
    } else if(element.webkitRequestFullscreen) {
      element.webkitRequestFullscreen();
    } else if(element.msRequestFullscreen) {
      element.msRequestFullscreen();
    }
}

function cambioPantalla(){
  getFullscreen(document.documentElement);
}
</script>
