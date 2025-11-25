
        <!-- ============================================================== -->
        <!-- Inicia filtros -->
        <!-- ============================================================== -->
        <form onsubmit="tipoInforme(); return false" class="form" method="post">
            <div class="row" style="margin-left: 7px;">
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                    <!-- <div class="form-group"> -->
                    <label>Tipo</label>
                    <select class="form-control" name="ftipo" id="ftipo">
                        <option value="1">Turnos resumido</option>
                    </select>
                    <!-- </div> -->
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                    <!-- <div class="form-group"> -->
                    <label>Sede</label>
                    <select class="form-control" name="fsede" id="fsede">
                        <option value="1">Sede Central</option>
                    </select>
                    <!-- </div> -->
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                    
                    <label>Turno</label>
                    <input type="text" class="form-control" id="turno" name="turno">
                   
                </div>

                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
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
                    <div class="card-body" id="griddatos_det">
                    
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
