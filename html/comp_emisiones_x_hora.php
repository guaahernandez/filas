

        <!-- ============================================================== -->
        <!-- Inicia filtros -->
        <!-- ============================================================== -->
        <form onsubmit="comp_dia_emisi_x_hora_d(); return false" class="form" method="post">
            <div class="row" style="margin-left: 0px;">
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <!-- <div class="form-group"> -->
                    <label>Sede</label>
                    <select onchange="comp_emisiones_x_hora_d()" class="form-control"  
                        name="fsede" id="fsede" required>
                        <option value="1">Sede Central</option>
                    </select>
                    <!-- </div> -->
                </div>

                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                    <!-- <div class="form-group"> -->
                    <label>Fecha 1</label>
                    <input type="date" class="form-control" id="fecha1" name="fecha1" value="<?=date('Y-m-d')?>">
                    <!-- </div> -->
                </div>

                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                    <!-- <div class="form-group"> -->
                    <label>Fecha 2</label>
                    <input type="date" class="form-control" id="fecha2" name="fecha2" value="<?=date('Y-m-d')?>">
                    <!-- </div> -->
                </div>

                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
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

        
        
            <div class="row">
                <div class="col-lg-4 col-md-12">
                <div id="traza_turnos">
                    
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                
                    <div id="griddatos_det">
                    
                    </div>
                
                </div>
                
            </div>
           
