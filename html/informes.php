<?php 
ob_start();
include 'header.php';
date_default_timezone_set('America/Costa_Rica');
?>

<style type="text/css">
  table.blueTable {
  /* border: 1px solid #1C6EA4; */
  width: 100%;
  text-align: center; 
  border-collapse: collapse;
}
table.blueTable td, table.blueTable th {
  /* border: 1px solid #AAAAAA; */
  padding: 7px 2px;
}
table.blueTable tbody td {
  font-size: 15px;
}
table.blueTable thead {
  background: #204489;
  border-bottom: 2px solid #444444;
}
table.blueTable thead th {
  font-size: 15px;
  font-weight: bold;
  color: #FFFFFF;
  border-left: 1px solid #D0E4F5;
}
table.blueTable thead th:first-child {
  border-left: none;
}

table.blueTable tfoot {
  font-size: 14px;
  font-weight: bold;
  color: #FFFFFF;
  background: #204489;
  border-top: 2px solid #444444;
}
  </style>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
        <form class="form" method="post">
            <div class="row" style="margin-left: 7px;">
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                    <div class="form-group">
                      <label>Tipo</label>
                      <select class="form-control" onchange="tipoInforme();" name="ftipo" id="ftipo">
                          <option value="0">Seleccione una opción</option>
                          <option value="1">Trazabilidad por turno</option>
                          <option value="2">Comparación emisiones por hora</option>
                          <option value="3">Turnos por ubicación</option>
                          <option value="4">Estado en línea</option>
                          <option value="5">Estudio detallado</option>
                      </select>                    
                    </div>
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2 mb-2" id="lasede" style="display: none;">
                    <label>Sede</label>
                    <select class="form-control" name="fsede" id="fsede">
                      <option value="1">Sede Central</option>
                    </select>
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2 mb-2" id="lafecha1" style="display: none;">                  
                    <label id="lblf1">Fecha</label>  
                    <input type="date" name="ffecha1" id="ffecha1" class="form-control" value="<?=date('Y-m-d')?>">                  
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2 mb-2" id="lafecha2" style="display: none;">                  
                    <label id="lblf2">Fecha</label>  
                    <input type="date" name="ffecha2" id="ffecha2" class="form-control" value="<?=date('Y-m-d')?>">                  
                </div>
                <div class="col-sm-4 col-md-3 col-lg-2 mb-2">
                  <div id="elboton" class="form-group" style="display: none;">  
                    <label>&nbsp;</label>  
                    <input type="button" class="form-control btn btn-info" onclick="" value="Ver">
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
<?php 

ob_end_flush(); ?>