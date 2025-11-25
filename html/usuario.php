<?php include 'header.php' ?>
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
              <h4 class="page-title">Usuarios</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../html">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Usuarios
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
              <h5 class="box-title"><button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h5>
              <div class="table-responsive">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" style="padding: 2px;">
                  <thead>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Foto</th>
                    <th>Fecha/Registro</th>
                    <th>Estado</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Foto</th>
                    <th>Fecha/Registro</th>
                    <th>Estado</th>
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
                <label for="">Tipo usuario(*):</label>
              <select name="idtipousuario" id="idtipousuario" class="form-control" required>

              </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Departamento(*):</label>
              <select name="iddepartamento" id="iddepartamento" class="form-control" required>
                
              </select>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Nombre(*):</label>
                <input class="form-control" type="hidden" name="idusuario" id="idusuario">
                <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Apellidos(*):</label>
                <input class="form-control" type="text" name="apellidos" id="apellidos" maxlength="100" placeholder="Apellidos" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Email: </label>
                <input class="form-control" type="email" name="email" id="email" maxlength="70" placeholder="email">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Login(*):</label>
                <input autocomplete="off" class="form-control" type="text" name="login" id="login" maxlength="20" placeholder="nombre de usuario" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Clave de ingreso:</label>
                <input autocomplete="off" class="form-control" type="password" name="clave" id="clave" maxlength="64" placeholder="Clave">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Sede:</label>
                <select class="form-control" name="fsede" id="fsede">
                  <option value="1">Sede Central</option>
                </select>
              </div>

              <!-- <div class="form-group col-lg-6 col-md-6 col-xs-12" id="claves">
                <label for="">Código Colaborador:</label>
                <input class="form-control" type="text" name="codigo_persona" id="codigo_persona" maxlength="64" placeholder="Código">
              </div> -->
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Imagen:</label>
                <input class="form-control filestyle" data-buttonText="Seleccionar foto" type="file" name="imagen" id="imagen">
                <input type="hidden" name="imagenactual" id="imagenactual">
                <img src="" alt="" width="150px" height="120" id="imagenmuestra">
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

        <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 20% !important;">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cambio de contraseña</h4>
              </div>
              <div class="modal-body">
        <form action="" name="formularioc" id="formularioc" method="POST">
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">Password:</label>
                  <input class="form-control" type="hidden" name="idusuarioc" id="idusuarioc">
                  <input class="form-control" type="password" name="clavec" id="clavec" maxlength="64" placeholder="Clave" required>
                </div>
                <button class="btn btn-primary" type="submit" id="btnGuardar_clave"><i class="fa fa-save"></i>  Guardar</button>
            <button class="btn btn-danger"  type="button"  data-dismiss="modal" ><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
              </form>

              <div class="modal-footer">
                <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
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
<script src="scripts/usuario.js"></script>