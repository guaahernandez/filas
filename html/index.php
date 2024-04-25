<?php 
ob_start();
include 'header.php' 
?>

<!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Dashboard
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- Inicia filtros -->
        <!-- ============================================================== -->
        <form onsubmit="totvisitas(); return false" class="form" method="post">
        <div class="row" style="margin-left: 7px;">
          <div class="col-sm-3">
            <!-- <div class="form-group"> -->
              <label>Sede</label>
              <select class="form-control" name="fsede" id="fsede" onchange="totvisitas();">
                <option value="1">Sede Central</option>
              </select>
            <!-- </div> -->
          </div>
          <div class="col-sm-2" style="display: none;">
            <!-- <div class="form-group"> -->
              <label>Area</label>
              <select class="form-control" name="farea" id="farea">
                <option value="0">Todas...</option>
                <option value="V">Ventas</option>
                <option value="C">Cajas</option>
                <option value="E">Entregas</option>
              </select>
            <!-- </div> -->
          </div>

          <div class="col-sm-2">
            <!-- <div class="form-group"> -->
              <?php 
              $date = date_create(date('Y-m-d')); 
              date_sub($date, date_interval_create_from_date_string('30 days')); 
              ?>
              <label>Desde</label>
              <a href="javascript:void(0);" onclick="MoverFecha('-');" class="btn btn-sm btn-secondary"><i class="mdi mdi-arrow-left-bold"></i></a>
              <a href="javascript:void(0);" onclick="MoverHoy();" class="btn btn-sm btn-info"><i class="mdi mdi-calendar"></i> hoy </a>
              <a href="javascript:void(0);" onclick="MoverFecha('+');" class="btn btn-sm btn-secondary"><i class="mdi mdi-arrow-right-bold"></i></a>
              <input type="date" onchange="totvisitas();" class="form-control" id="fecini" name="fecini" value="<?=date('Y-m-d')?>" min="<?=date_format($date, 'Y-m-d')?>">
            <!-- </div> -->
          </div>
          <div class="col-sm-2">
            <!-- <div class="form-group"> -->
              <label>Hasta</label>
              <input type="date" onchange="totvisitas();" class="form-control" id="fecfin" name="fecfin" value="<?=date('Y-m-d')?>" min="<?=date_format($date, 'Y-m-d')?>">
            <!-- </div> -->
          </div>

          <div class="col-sm-1">
            <div class="form-group">
              <label>&nbsp;</label>
              <input type="submit" class="form-control btn btn-info" value="Ver">
            </div>
          </div>
        </div>
        </form>
        <!-- ============================================================== -->
        <!-- Finaliza filtros -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
          <!-- ============================================================== -->
          <!-- Sales Cards  -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- Column -->
            <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <a href="#">
                    <h1 class="font-light text-white">
                      <i class="mdi mdi-view-dashboard"></i>
                    </h1>
                    <h6 class="text-white">Dashboard</h6>
                  </a>
                </div>
              </div>
            </div> -->
            <?php if($_SESSION["cftidtipousuario"] != 4){ ?>
              <!-- Column -->
              <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
                <div class="card card-hover">
                  <div class="box bg-success text-center">
                    <a href="videos.php">
                      <h1 class="font-light text-white">
                        <i class="mdi mdi-chart-areaspline"></i>
                      </h1>
                      <h6 class="text-white">Videos</h6>
                    </a> 
                  </div>
                </div>
              </div> -->
              <!-- Column -->
              <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
                <div class="card card-hover">
                  <div class="box bg-warning text-center">
                    <a href="usuario.php">
                      <h1 class="font-light text-white">
                        <i class="mdi mdi-collage"></i>
                      </h1>
                      <h6 class="text-white">Usuarios</h6>
                    </a>
                  </div>
                </div>
              </div> -->
              <!-- Column -->
              <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
                <div class="card card-hover">
                  <div class="box bg-danger text-center">
                    <h1 class="font-light text-white">
                      <i class="mdi mdi-border-outside"></i>
                    </h1>
                    <h6 class="text-white">Vendedores</h6>
                  </div>
                </div>
              </div> -->
            <?php } ?>
            <!-- Column -->
            <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-arrow-all"></i>
                  </h1>
                  <h6 class="text-white">Full Width</h6>
                </div>
              </div>
            </div> -->
            <!-- Column -->
            <!-- Column -->
            <!-- <div class="col-md-6 col-lg-4 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-danger text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-receipt"></i>
                  </h1>
                  <h6 class="text-white">Forms</h6>
                </div>
              </div>
            </div> -->
            <!-- Column -->
            <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-relative-scale"></i>
                  </h1>
                  <h6 class="text-white">Buttons</h6>
                </div>
              </div>
            </div> -->
            <!-- Column -->
            <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-pencil"></i>
                  </h1>
                  <h6 class="text-white">Elements</h6>
                </div>
              </div>
            </div> -->
            <!-- Column -->
            <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-calendar-check"></i>
                  </h1>
                  <h6 class="text-white">Calnedar</h6>
                </div>
              </div>
            </div> -->
            <!-- Column -->
            <!-- <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-warning text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-alert"></i>
                  </h1>
                  <h6 class="text-white">Errors</h6>
                </div>
              </div>
            </div> -->
            <!-- Column -->
          </div>
          <!-- ============================================================== -->
          <!-- Sales chart -->
          <!-- ============================================================== -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="d-md-flex align-items-center">
                    <div>
                      <!-- <h4 class="card-title">Analisis de visitas</h4> -->
                      <!-- <h5 class="card-subtitle">Ultimos 11 días</h5> -->
                    </div>
                  </div>
                  <div class="row">
                    <!-- column -->
                    <div class="col-lg-7">
                      <div id="grafica" style="max-height: 350px;"></div>
                      
                    </div>
                    <div class="col-lg-1">
                      <div class="row">
                        <div class="col-12">
                          <a href="#" onclick="turnosetapas_();" data-bs-toggle="modal" data-bs-target="#modaldatos">
                            <div class="bg-dark p-10 text-white text-center">
                              <i class="mdi mdi-account fs-3 mb-1 font-16"></i>
                              <h5 id="totvisitas" class="mb-0 mt-1">-</h5>
                              <small class="font-light">Visitantes</small>
                            </div>
                          </a>
                        </div>
                        
                        <div class="col-12 mt-3">
                          <a href="#" data-bs-toggle="modal" onclick="griddatostotal('');" data-bs-target="#modaldatos">
                          <div class="bg-dark p-10 text-white text-center">
                            <i class="mdi mdi-cart fs-3 mb-1 font-16"></i>
                            <h5 id="totcompras" class="mb-0 mt-1">-</h5>
                            <small class="font-light">Ventas</small>
                          </div>
                          </a>
                        </div>
                        
                        <div class="col-12 mt-3">
                          <div class="bg-dark p-10 text-white text-center">
                            <i class="mdi mdi-check fs-3 mb-1 font-16"></i>
                            <h5 id="completos" class="mb-0 mt-1">-</h5>
                            <small class="font-light">Completos</small>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                    <div class="col-lg-4">                      
                      <div align="center" id="turnostot" style="text-align: center; max-height: 350px;"></div>                        
                    </div>
                    
                    <!-- column -->
                  </div>
                  
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="col-12">
                    <div align="center" id="turnosetapas" style="max-height: 350px;">etapas</div>
                  </div>     
                </div>           
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="col-12">
                    <div align="center" id="turnosetapas2" style="max-height: 350px;">tramites</div>
                  </div>     
                </div>           
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="col-12">
                    <div align="center" id="canceladosxetapa" style="max-height: 350px;">cancelados x etapa</div>
                  </div>     
                </div>           
              </div>
            </div>
            <div class="col-lg-12">                        
              <div align="center" id="turnosxvendedor">Turnos por vendedor</div>                              
            </div>
            <div class="col-lg-12">                        
              <div align="center" id="tiempopromventas">Tiempo promedio ventas</div>                              
            </div>
            <div class="col-lg-6">                        
              <div align="center" class="card" id="emisionesporhora">Emisiones por hora</div>                              
            </div>
            <div class="col-lg-6">  
              <div align="center" class="col-12"><h4>Datos resumidos</h4></div>                      
              <div class="row">
                <div class="col-md-6">
                  <div class="card mt-0">
                    <div class="row">
                      <div class="col-md-12">
                        <div id="esperageneral" class="peity_line_neutral left text-center mt-2">
                          <h2>00:00</h2>
                        </div>
                      </div>
                      <div class="col-md-12 border-left text-center pt-2">
                        <span class="text-muted">Espera General</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card mt-0">
                    <div class="row">
                      <div class="col-md-12">
                        <div id="atenciongeneral" class="peity_line_neutral left text-center mt-2">
                          <h2>00:00</h2>
                        </div>
                      </div>
                      <div class="col-md-12 border-left text-center pt-2">
                        <span class="text-muted">Atención General</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card mt-0">
                    <div class="row">
                      <div class="col-md-12">
                        <div id="porcentajecompra" class="peity_line_neutral left text-center mt-2">
                          <h2>00%</h2>
                        </div>
                      </div>
                      <div class="col-md-12 border-left text-center pt-2">
                        <span class="text-muted">Porcentaje de compra</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="card mt-0">
                    <div class="row">
                      <div class="col-md-12">
                        <div id="ingresoporventas" class="peity_line_neutral left text-center mt-2">
                          <h2>₡ 000,000</h2>
                        </div>
                      </div>
                      <div class="col-md-12 border-left text-center pt-2">
                        <span class="text-muted">Ingreso por ventas</span>
                      </div>
                    </div>
                  </div>
                </div>

              </div>                              
            </div>
            <!-- <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <div class="col-12">
                    <div align="center" id="donutchart" style="text-align: center; height: 300px;">etapas</div>
                  </div>     
                </div>           
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card">
                <div class="card-body">
                  <div class="col-12">
                    <div align="center" id="lineaschart" style="text-align: center; height: 300px;">lineas</div>
                  </div>     
                </div>           
              </div>
            </div> -->
          </div> 
          </div> 
          
          <!-- ============================================================== -->
          <!-- Sales chart -->
          <!-- ============================================================== -->

          <!-- Cards -->
          <!-- <div class="row">
            <div class="col-md-3">
              <div class="card mt-0">
                <div class="row">
                  <div class="col-md-6">
                    <div class="peity_line_neutral left text-center mt-2">
                      <span
                        ><span style="display: none">10,15,8,14,13,10,10</span>
                        <canvas width="50" height="24"></canvas>
                      </span>
                      <h6>10%</h6>
                    </div>
                  </div>
                  <div class="col-md-6 border-left text-center pt-2">
                    <h3 class="mb-0 fw-bold">150</h3>
                    <span class="text-muted">New Users</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card mt-0">
                <div class="row">
                  <div class="col-md-6">
                    <div class="peity_bar_bad left text-center mt-2">
                      <span
                        ><span style="display: none">3,5,6,16,8,10,6</span>
                        <canvas width="50" height="24"></canvas>
                      </span>
                      <h6>-40%</h6>
                    </div>
                  </div>
                  <div class="col-md-6 border-left text-center pt-2">
                    <h3 class="mb-0 fw-bold">4560</h3>
                    <span class="text-muted">Orders</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card mt-0">
                <div class="row">
                  <div class="col-md-6">
                    <div class="peity_line_good left text-center mt-2">
                      <span
                        ><span style="display: none">12,6,9,23,14,10,17</span>
                        <canvas width="50" height="24"></canvas>
                      </span>
                      <h6>+60%</h6>
                    </div>
                  </div>
                  <div class="col-md-6 border-left text-center pt-2">
                    <h3 class="mb-0">5672</h3>
                    <span class="text-muted">Active Users</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card mt-0">
                <div class="row">
                  <div class="col-md-6">
                    <div class="peity_bar_good left text-center mt-2">
                      <span>12,6,9,23,14,10,13</span>
                      <h6>+30%</h6>
                    </div>
                  </div>
                  <div class="col-md-6 border-left text-center pt-2">
                    <h3 class="mb-0 fw-bold">2560</h3>
                    <span class="text-muted">Register</span>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
          <!-- End cards -->
          
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php include 'footer.php' ?>
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
   <!-- Modal -->
  <div class="modal fade" id="modaldatos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Datos detallados</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="divdatos" class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
  </body>
</html>
<!--This page JavaScript -->
<script src="scripts/dashboard.js"></script>
<?php ob_end_flush(); ?>

