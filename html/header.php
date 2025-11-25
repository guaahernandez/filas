<?php 
  include '../config/global_dat.php'; 
  session_start();
  
  if (!isset($_SESSION['cftnombre'])) {
    header("Location: login.html");
  }
  date_default_timezone_set('America/Costa_Rica');
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
      content="control de filas la guaca"
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
    <!-- <link href="../assets/libs/flot/css/float-chart.css" rel="stylesheet" /> -->
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet" />
    <!-- <link href="../dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- <script src="scripts/side.js"></script> -->
  </head>

  <body>
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
      data-boxed-layout="full">
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header" data-logobg="skin5">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="index.php">
              <!-- Logo icon -->
              <span class="logo-text ms-2">
                <!-- dark Logo text -->
                <img
                  src="../assets/images/guaca.webp"
                  alt="homepage"
                  class="light-logo"
                  width="150px"
                />
              </span>


              <!--End Logo icon -->
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a
              class="nav-toggler waves-effect waves-light d-block d-md-none"
              href="javascript:void(0)"
              ><i class="ti-menu ti-close"></i
            ></a>
          </div>
          <!-- ============================================================== -->
          <!-- End Logo -->
          <!-- ============================================================== -->
          <div
            class="navbar-collapse collapse"
            id="navbarSupportedContent"
            data-navbarbg="skin5"
          >
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-start me-auto">
              <li class="nav-item d-none d-lg-block">
                <!-- <a
                  class="nav-link sidebartoggler waves-effect waves-light"
                  href="javascript:void(0)"
                  data-sidebartype="mini-sidebar"
                  ><i class="mdi mdi-menu font-24"></i
                ></a> -->
                <a
                  class="nav-link sidebartoggler waves-effect waves-light"
                  href="javascript:void(0)"
                  data-sidebartype="mini-sidebar"
                  ><i class="mdi mdi-menu font-24"></i
                ></a>
              </li>
              
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  
                  aria-expanded="false"
                >
                  <span class="d-none d-md-block"
                    ><?=PRO_NOMBRE?></span>
                  <span class="d-block d-md-none"
                    ><i class="fa fa-plus"></i
                  ></span>
                </a>
                
              </li>

            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-end">
              <!-- ============================================================== -->
              <!-- Comment -->
              <!-- ============================================================== -->
              
              <!-- ============================================================== -->
              <!-- End Comment -->
              <!-- ============================================================== -->
              

              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                    pro-pic
                  "
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    src="../assets/usuarios/<?php echo $_SESSION['cftimagen']; ?>"
                    alt="user"
                    class="rounded-circle"
                    width="31"
                  />
                  <?php echo " ".$_SESSION['cftnombre'] ?>
                </a>
                <ul
                  class="dropdown-menu dropdown-menu-end user-dd animated"
                  aria-labelledby="navbarDropdown"
                >
                  <div class="ps-4 p-10">
                    <p>
                      <?php echo $_SESSION['cftnombre'] ?>
                    </p>
                      <small><?=$_SESSION["cftdepartamento_name"]?></small>
                                  
                  </div>
                  <a class="dropdown-item" href="../ajax/a_usuario.php?op=salir"
                    ><i class="fa fa-power-off me-1 ms-1"></i> Salir</a
                  >
                  <div class="dropdown-divider"></div>
                  
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <aside class="left-sidebar" data-sidebarbg="skin5">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-4">
            <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="index.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-view-dashboard"></i
                  ><span class="hide-menu">Dashboard</span></a
                >
              </li>

              <?php if($_SESSION["cftidtipousuario"] != 4 and $_SESSION["cftidtipousuario"] != 3 and $_SESSION["cftidtipousuario"] != 2){ ?> <!--oculta opciones del menu -->
                <li class="sidebar-item">
                  <a
                    class="sidebar-link waves-effect waves-dark sidebar-link"
                    href="videos.php"
                    aria-expanded="false"
                    ><i class="mdi mdi-video-switch"></i
                    ><span class="hide-menu">Videos</span></a
                  >
                </li>             
                
                <li class="sidebar-item">
                  <a
                    class="sidebar-link waves-effect waves-dark sidebar-link"
                    href="usuario.php"
                    aria-expanded="false"
                    ><i class="mdi mdi-account-plus"></i
                    ><span class="hide-menu">Usuarios</span></a
                  >
                </li>
                
                <li class="sidebar-item">
                  <a
                    class="sidebar-link waves-effect waves-dark sidebar-link"
                    href="textos.php"
                    aria-expanded="false"
                    ><i class="mdi mdi-note-text"></i
                    ><span class="hide-menu">Textos</span></a
                  >
                </li>
                <li class="sidebar-item">
                  <a
                    class="sidebar-link waves-effect waves-dark sidebar-link"
                    href="vendedor.php"
                    aria-expanded="false"
                    ><i class="mdi mdi-account-multiple"></i
                    ><span class="hide-menu">Vendedores</span></a
                  >
                </li>
                <li class="sidebar-item">
                  <a
                    class="sidebar-link waves-effect waves-dark sidebar-link"
                    href="sedes.php"
                    aria-expanded="false"
                    ><i class="mdi mdi-crosshairs-gps"></i
                    ><span class="hide-menu">Sedes</span></a
                  >
                </li>
                
              <?php } ?>
              <li class="sidebar-item">
                  <a
                    class="sidebar-link waves-effect waves-dark sidebar-link"
                    href="informes.php"
                    aria-expanded="false"
                    ><i class="mdi mdi-clipboard-text"></i
                    ><span class="hide-menu">Informes</span></a
                  >
                </li> 
              
              <?php if($_SESSION["cftidtipousuario"] != 4 and $_SESSION["cftidtipousuario"] != 3 and $_SESSION['cftlogin'] != 'jfallas'){ ?> <!--oculta opciones del menu -->
                <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="kioscoh.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-monitor"></i
                  ><span class="hide-menu">Kiosco</span></a
                >
              </li>
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="pantalla.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-monitor"></i
                  ><span class="hide-menu">Pantallas</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="kioscov.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-monitor"></i
                  ><span class="hide-menu">KioscoV</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="pantentregas.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-monitor"></i
                  ><span class="hide-menu">Entregas</span></a
                >
              </li>
              
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="visor.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-monitor"></i
                  ><span class="hide-menu">Visor Videos</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="kiosco720x1280.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-monitor"></i
                  ><span class="hide-menu">kiosco720x1280</span></a
                >
              </li>
              <?php } ?>
              <!-- <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="pantalla.php"
                  aria-expanded="false"
                  ><i class="mdi mdi-monitor"></i
                  ><span class="hide-menu">Pantalla Entregas</span></a
                >
              </li> -->
                
              
              
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
