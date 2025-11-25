<?php 
include '../config/global_dat.php';

session_start();
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "VENTA1_HEC";
$_SESSION["agenci"] = (isset($_GET["ag"])) ? $_GET["ag"] : "S-20";
$_SESSION["destin"] = (isset($_GET["destin"])) ? $_GET["destin"] : "V";
$_SESSION["ntexto"] = 0;
$_SESSION["texto"] = "";
$_SESSION["formatovideo"] = "horizontal";
//echo $_SESSION["ip"];
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
  <div
    id="main-wrapper"
    data-layout="vertical"
    data-navbarbg="skin5"
    data-sidebartype="full"
    data-sidebar-position="absolute"
    data-header-position="absolute"
    data-boxed-layout="full"
  >
    
  <style>
    @font-face {
        font-family: 'Futura';
        src: url('../assets/fuentes/Futura_Extra_Black_Italic.otf');
    }
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
    .fondorojo{
      background-color: #ed1b2f;
      color: #ffffff;
    }
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
  <!-- Container fluid  -->
  <!-- ============================================================== -->
  <div class="container" style="background-color: #ffffff;">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row" id="cargavideos" style="text-align: center;">
        <div class="col-11" style="height: 9vh;background-color: #1b468c;border-bottom: solid 10px #ed1b2f;">
            <img src="../assets/images/guaca.png" style="margin-top: 10px; width: 400px;" alt="">            
        </div>
        <div class="col-1" style="height: 9vh;background-color: #ed1b2f;border-bottom: solid 10px #ed1b2f;">            
        </div>
        <div class="col-12" style="height: 18.2vh ; padding: 0px; padding-bottom: 0px;">
            <!--<video controls autoplay name="media" muted width="100%" loop >-->
            <video id="vid" preload="auto" autoplay muted height="100%" width="100%">
                <source src=""  type="video/mp4">
            </video>
        </div>
    </div>
    <div class="row" id="pizarra" style="text-align: center;">
    </div>
    <div class="row">
      <div class="col-2 pieizq" style="margin: 0; padding: 0;">
        <img src="../assets/images/Guacamaya.png" height="100%">
      </div>
      <div id="pietexto" class="col-10 pieizq">
        <marquee id="txtmarquee" loop="-1">GRACIAS POR SU VISITA</marquee>          
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
    
  </div>
  <!-- ============================================================== -->
  <!-- End Container fluid  -->
  <!-- ============================================================== -->
  </div>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- slimscrollbar scrollbar JavaScript -->
  <script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  
  <!--Custom JavaScript -->
  <script src="../dist/js/custom.min.js"></script>
  
</body>
</html>
<script src="scripts/pantalla.js"></script>
<!-- <script src="../ajax/tss.js"></script> -->

<script>
    //Sonar beep antes de cada llamada
    async function Sonar(dir){       
    var sonido = new Audio("../assets/audios/beep3.mp3");
    sonido.play();
    setTimeout(function(){ 
        sonido = new Audio(dir);
        sonido.play();
    }, 1000);           
} 
</script>

<script>
    let sonando = 0;
    let primera = 1;
    const playSound = function() {
        if(sonando == 0){
            if(primera==1){
                //var sonido = new Audio("../assets/audios/sonidoact.mp3");
                //sonido.play();
                //Sonidos2();
                primera=0;
            }
            //elimina el evento click
            document.removeEventListener('click', playSound);
            sonando=1;                
        }else{
            sonando=0;
        }
    }
    //dar un click para que inicie la reproduccion de audio
    document.addEventListener('click', playSound);

    //funcion reccurrente que ejecuta los audios
    async function Sonidos2(){            
        var refreshid = setInterval(function(){                
            if(sonando==1){
                $.ajax({
                    url: "sonidos.php",
                    type: "POST",                
                    success: function(datos){
                        if(datos!=''){
                            Sonar(datos);
                            setTimeout(function(){ 
                                $.post("eliminar.php",
                                function(data,status){                                        
                                })
                            },3000);
                        }
                    }
                });                     
            }                
        },5000);
    }    
   </script>