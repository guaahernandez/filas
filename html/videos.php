<?php 
ob_start();
include 'header.php';
ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); 
?>
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->

      <!-- Custom CSS -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"/>
    
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title"></h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../html">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Videos
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
            <div class="row">
                <div class="col-12">
                    <div class="card" id="listadoregistros">
                        <div class="card-body">
                        <h5 class="box-title">Videos <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h5>
                        <div class="table-responsive">
                            <table id="tbllistado" class="table table-striped table-bordered table-sm" style="padding: 2px;">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Agencia</th>
                                    <th>Video</th>
                                    <th>Descripción</th>
                                    <th>Ver</th>
                                    <th>Ult.Modif</th>
                                    <th>Formato Vid</th>
                                </tr>
                            </thead>
                            
                            <tfoot>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Agencia</th>
                                    <th>Video</th>
                                    <th>Descripción</th>
                                    <th>Ver</th>
                                    <th>Ult.Modif</th>
                                    <th>Formato Vid</th>
                                </tr>
                            </tfoot>
                            </table>
                        </div>
                        </div>
                    </div>
                    <div class="panel-body" style="height: 400px;" id="formularioregistros">
                        <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Agregar video</h5>
                            <div class="table-responsive">
                                <form action="" name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                    <label for="agenci">Agencia</label>
                                    <input class="form-control" type="hidden" name="codigo" id="codigo">
                                    <select class="form-control" name="agenci" id="agenci" required></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="direcc">Video</label>
                                        <input class="form-control form-control-file" accept="video/mp4" type="file" name="direcc" id="direcc" maxlength="256" placeholder="Descripcion">
                                        <input type="hidden" name="videoa" id="videoa">
                                    </div>
                                    <div class="form-group">
                                        <label for="descri">Descripción</label>                                        
                                        <input class="form-control" type="text" name="descri" id="descri">
                                    </div>
                                    <div class="form-group">
                                      <label for="estado">Formato Video</label>
                                      <select class="form-control" name="format" id="format">
                                          <option value="vertical">Vertical</option>
                                          <option value="horizontal">Horizontal</option>
                                      </select>
                                    </div>
                                    <!-- <div class="form-group">
                                      <label for="estado">Estado</label>
                                      <select class="form-control" name="estado" id="estado">
                                          <option value="0">Inactivo</option>
                                          <option value="1">Activo</option>
                                      </select>
                                    </div> -->
                                    <div class="form-group">
                                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

                                    <button class="btn btn-danger" id="btnCancelar" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                                    <div align="center" id="subiendo" style="display: none;">
                                      <label>Subiendo video, espere por favor  </label>
                                      <img src="../assets/images/proc.gif">
                                    </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                
                <!-- <div class="col-lg-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="vervideo">
                                
                            </div>                        
                        </div>
                    </div>
                </div> -->
            </div>

        
          <!-- ============================================================== -->
          <!-- End PAge Content -->
          <!-- ============================================================== -->
          
          <!-- ============================================================== -->
          <!-- Modal -->
          <!-- ============================================================== -->
          <div class="modal fade" id="videomodal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Video</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="vervideo" class="modal-body">
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        <!-- ============================================================== -->
        <!-- End Modal -->
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
    
    <!-- this page js -->
    <!-- datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    

  </body>
</html>
<script src="scripts/videos.js"></script>
<?php ob_end_flush(); ?>