<?php
//require '../config/global_dat.php'; 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{


require 'header.php';


 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Sedes <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
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
<div class="panel-body" style="height: 400px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
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
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
</div>
<!--fin centro-->
      </div>
      </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<?php 


require 'footer.php';
 ?>
 <script src="scripts/sede.js"></script>
 <?php 
}

ob_end_flush();
  ?>

