<?php include 'header.php'; 
?>
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->

      <!-- Custom CSS -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>
      <!-- <link rel="stylesheet" type="text/css" href="../assets/libs/bootstrap-select/bootstrap-select.min.css"> -->
      <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> -->

      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Sedes</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../html">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Sedes
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
          <!-- ============================================================== -->
          <!-- Start Page Content -->
          <!-- ============================================================== -->
          <div class="card table-responsive" id="listadoregistros">
            <div class="card-body">
             
              <h5 class="box-title">
                <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar
                </button>
            </h5>
              <div class="table-responsive">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" style="padding: 2px;">
                  <thead>
                    <th>Opciones</th>
                    <th>Id</th>
                    <th>Sede</th>
                    <th>Descripción</th>
                    <th>Dirección</th>
                    <th>Grupo</th>
                    <th>Fecha/registro</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Id</th>
                    <th>Sede</th>
                    <th>Descripción</th>
                    <th>Dirección</th>
                    <th>Grupo</th>
                    <th>Fecha/registro</th>
                  </tfoot>   
                </table>
              </div>
            </div>
          </div>
        <div class="card" id="formularioregistros">
        <div class="card-body">
           
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="id">Id</label>
                <!-- <input class="form-control" type="hidden" name="iddepartamento" id="iddepartamento"> -->
                <input class="form-control" type="text" name="id" id="id" maxlength="50" placeholder="Id" required>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="sede">Sede</label>
                <input class="form-control" type="text" name="sede" id="sede" maxlength="256" placeholder="Sede">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="descrip">Descripción</label>
                <input class="form-control" type="text" name="descrip" id="descrip" maxlength="256" placeholder="Descripción">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="grupo">Grupo</label>
                <input class="form-control" type="text" name="grupo" id="grupo" maxlength="256" placeholder="Grupo">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="grupo">Dirección</label>
                <input class="form-control" type="text" name="direcci" id="direcci" maxlength="256" placeholder="Dirección">
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                </div>
            </div>
          </form>
        </div>          
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Listado de Vendedores</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="listarVend">
            <div class="table-responsive">
              <table style="width: 100%;" id="tbllistadoVend" class="table table-striped table-bordered table-condensed table-hover" style="padding: 2px;">
                <thead>
                  <th>Opciones</th>
                  <th>Código</th>
                  <th>Nombre</th>
                  <th>Usuario</th>
                  <th>Teléfono</th>
                  <th>Agencia</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <th>Opciones</th>
                  <th>Código</th>
                  <th>Nombre</th>
                  <th>Usuario</th>
                  <th>Teléfono</th>
                  <th>Agencia</th>
                </tfoot>   
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary"></button> -->
          </div>
        </div>
      </div>
    </div>

    <!-- this page js -->
    <!-- datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

    <!-- Latest BS-Select compiled and minified CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
  </body>
</html>
<script src="scripts/sede.js"></script>