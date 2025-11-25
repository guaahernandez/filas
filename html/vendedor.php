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
              <h4 class="page-title">Vendedores</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../html">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Vendedores
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
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Foto</th>
                    <th>Teléfono</th>
                    <th>Agencia</th>
                    <th>Estado</th>
                    <th>GC</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Foto</th>
                    <th>Teléfono</th>
                    <th>Agencia</th>
                    <th>Estado</th>
                    <th>GC</th>
                  </tfoot>   
                </table>
              </div>
            </div>
          </div>
        <div class="card" id="formularioregistros">
        <div class="card-body">
           <!-- Button trigger modal -->
           <div style="margin-bottom: 5px;">
           <button type="button" onclick="listarVend();" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                  Agregar desde ERP
                </button>
           </div>
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="row">
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Código:</label>
                <input type="txt" name="agent" id="agent" class="form-control" placeholder="Código">

              </input>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Agencia(*):</label>
              <select name="direc" id="direc" class="form-control" required>
                
              </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Usuario(*):</label>
                <input class="form-control" type="text" name="nombc" id="nombc" placeholder="Usuario" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Nombre(*):</label>
                <input class="form-control" type="text" name="nombl" id="nombl" placeholder="Nombre" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Teléfono: </label>
                <input class="form-control" type="text" name="apart" id="apart" placeholder="Teléfono">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12" id="claves">
                <label for="">Estado:</label>
                <select class="form-control" name="activo" id="activo" >
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
              </div>
              
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Imagen:</label>
                <input class="form-control filestyle" data-buttonText="Seleccionar foto" type="file" name="imagen" id="imagen">
                <input type="hidden" name="imagenactual" id="imagenactual">
                <!-- <img src="" alt="" width="150px" height="120" id="imagenmuestra"> -->
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12" id="claves">
                <label for="">Atiende Guaca Club:</label>
                <select class="form-control" name="gc" id="gc" >
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
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
<script src="scripts/vendedor.js"></script>