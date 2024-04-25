<?php 
ob_start();
include 'header.php' 
?>


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
              <h4 class="page-title">Informes</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Informes
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
        <form onsubmit="tipoInforme(); return false" class="form" method="post">
            <div class="row" style="margin-left: 7px;">
                <div class="col-sm-4 col-md-3 col-lg-2 mb-2">
                    <!-- <div class="form-group"> -->
                    <label>Tipo</label>
                    <select class="form-control" name="ftipo" id="ftipo">
                        <option value="1">Turnos resumido</option>
                    </select>
                    <!-- </div> -->
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2 mb-2">
                    <!-- <div class="form-group"> -->
                    <label>Sede</label>
                    <select class="form-control" name="fsede" id="fsede">
                        <option value="1">Sede Central</option>
                    </select>
                    <!-- </div> -->
                </div>
                <div class="col-sm-3 col-md-2 col-lg-1 mb-2">
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

                <div class="col-sm-4 col-md-3 col-lg-2 mb-2">
                    <!-- <div class="form-group"> -->
                    <?php 
                    $date = date_create(date('Y-m-d')); 
                    date_sub($date, date_interval_create_from_date_string('15 days')); 
                    ?>
                    <label>Desde</label>
                    <a href="javascript:void(0);" onclick="MoverFecha('-');" class="btn btn-sm btn-secondary"><i class="mdi mdi-arrow-left-bold"></i></a>
                    <a href="javascript:void(0);" onclick="MoverHoy();" class="btn btn-sm btn-info">hoy</a>
                    <a href="javascript:void(0);" onclick="MoverFecha('+');" class="btn btn-sm btn-secondary"><i class="mdi mdi-arrow-right-bold"></i></a>
                    <input type="date" class="form-control" id="fecini" name="fecini" value="<?=date('Y-m-01')?>">
                    <!-- </div> -->
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2 mb-2">
                    <!-- <div class="form-group"> -->
                    <label>Hasta</label>
                    <input type="date" class="form-control" id="fecfin" name="fecfin" value="<?=date('Y-m-d')?>">
                    <!-- </div> -->
                </div>

                <div class="col-sm-3 col-md-2 col-lg-1 mb-2">
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
            <!-- Grid  -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- Column -->
                <div class="col-12">
                <div class="card">
                    <div class="card-body" id="griddatos">
                    
                    </div>
                </div>
                </div>
                
            </div>
            <!-- ============================================================== -->
            <!-- Grid -->
            <!-- ============================================================== -->
          
          
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


    <script>
    $(document).ready(function() {
        $('#tdatos').DataTable( {
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            responsive: true,
            buttons: [
                {
                    extend: 'print',
                    messageTop: 'Usuarios incluidos en el CMS'
                },
            'excel', 'pdf', 'pageLength'
            ],
            "order": [],
            "order": [[ 6, 'desc' ]],
            //"order": [[ 0, 'asc' ], [ 1, 'asc' ]]
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                }
        } );
    } );
    </script>
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
<script src="scripts/informes.js"></script>
<?php ob_end_flush(); ?>