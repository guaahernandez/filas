<?php 
include '../config/global_dat.php';
session_start();
$_SESSION["ip"] = (isset($_GET["ip"])) ? $_GET["ip"] : "";
//echo $_SESSION["ip"];
?>

<script type="text/javascript">

</script>


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
    <link href="../assets/libs/flot/css/float-chart.css" rel="stylesheet" />
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
        .num{
            height:12vh; 
            background-color: #ffffff;
            color: #000000;
            font-size: 4em;
            padding: 0;
            padding-top: 20px;
            margin: 0;
            vertical-align: bottom;
            font-weight: bolder;
            /*border-bottom: solid 1px #ffe800;*/
        }
        .fondorojo{
          background-color: #ed1b2f;
          color: #ffffff;
        }
        .titulo{
            height:5vh; 
            background-color: #1b468c;
            color: #ffffff;
            font-size: 1.5em;
            padding: 0;
            padding-top: 8px;
            margin: 0;
            vertical-align: bottom;
            font-weight: 700;
            border-bottom: solid 3px #ffe800;
            border-top: solid 3px #ffe800;
        }
        .pieizq{
            height:8vh; 
            background-color: #1b468c;
            color: #ffe800;
            padding-top: 8px;
            margin-right: 0;
            text-align: right;
            font-size: 1.5em;
            font-weight: bold;
            font-style: italic;
            border-bottom: solid 3px #ffe800;
            border-top: solid 3px #ffe800;
        }
        .pieder{
            height:8vh; 
            background-color: #1b468c;
            color: #ffffff;
            padding-top: 8px;
            padding-left: 0;
            text-align: left;
            font-size: 1.5em;
            font-weight: bold;
            font-style: italic;
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
                <div class="col-12" style="height: 7vh;background-color: #1b468c;border-bottom: solid 3px #ffe800;">
                    <img src="../assets/images/guaca.webp" style="margin-top: 3px;" alt="">
                </div>
                <div class="col-12" style="height: 19vh ; padding: 0px; padding-bottom: 0px;">
                    <!--<video controls autoplay name="media" muted width="100%" loop >-->
                    <video id="vid" preload="auto" autoplay muted height="100%" width="100%" ondblclick="window.history.back();">
                        <source src=""  type="video/mp4">
                    </video>
                </div>
            </div>
            <div class="row" id="pizarra" style="text-align: center;">
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
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- this page js -->
    <script src="../assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="../assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="../assets/extra-libs/DataTables/datatables.min.js"></script>
    <script>
      /****************************************
       *       Basic Table                   *
       ****************************************/
      //$("#zero_config").DataTable();
    </script>
  </body>
</html>
<script src="scripts/pantalla.js"></script>
<!-- <script>init('<?=$_GET["c"];?>');</script> -->