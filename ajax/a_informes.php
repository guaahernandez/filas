<style>
    .mialert {
        /*background-color: #1D4791;*/
        border-top-right-radius: 15px;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
        padding-top: 5px;
        padding-left: 10px;
        padding-bottom: 5px;
        margin-bottom: 5px;
        border: solid 1px #1D4791;
        /*max-width: 350px;*/
        /*color: #f2f2f2;*/
    }
    .miselect {
        background-color: #D3DDED;
        margin-bottom: 5px;
        /*color: #1D4791;*/
    }
    .mititulo{
        /*color: #FFF;*/
        font-weight: bold;
        font-size: large;
        /* padding-top: 3px;
        padding-left: 3px; */
    }
    .mitabla td {
        padding: 7px;
        font-size: 18px;
    }
    .mitabla td label {
        /* background-color: #8ffe89; */
        padding: 0;
        margin: 0;
    }
    .mitabla td:hover {
        border: 1px solid black;
        /* background-color: #e1f5fd; */
    }
    .mititulo{
        font-size: 18px; 
        font-weight: bold;
    }
</style>

<?php 

if (strlen(session_id())<1) 
	session_start();

require "../config/Conexion.php";
require "a_tools.php";

$wher = $_POST["wher"];
$turno=isset($_POST["turno"])? limpiarCadena($_POST["turno"]):"";

switch ($_GET["op"]) {
    
    case 'turnosresumido':
        
        $sql="SELECT s.`sede` Sede, s.`descrip` Nombre, f_totalturnos(s.`id`,0)Total,f_totalturnos(s.`id`,1)Completados 
        FROM sedes s 
        WHERE s.filas = 1 $wher;";
        echo $sql;
	    $rspta = ejecutarConsulta($sql);
        $campos = array();
        $tipos = array();
        $sumas = array();
        $class = array();
        ?>
        <table id="tdatos" class="table table-sm table-striped table-bordered table-condensed table-hover">
            <thead>
                <tr>
                <?php
                    $fields=$rspta->fetch_fields();
                    foreach ($fields as $field) {
                        if(map_field_type_to_bind_type($field->type)=='d'){
                            $class[] = "text-end";
                            echo "<th class='text-end'>$field->name</th>";
                        }else{
                            $class[] = "";
                            echo "<th>$field->name</th>";
                        }
                        
                        $campos[] = $field->name;
                        $tipos[] = map_field_type_to_bind_type($field->type);
                        $sumas[] = 0;
                    }
                ?>
                </tr>
            </thead>
            <tbody>
        <?php
            $fila = 0;
            while ($reg=$rspta->fetch_array()) {
                echo '<tr>';
                for ($i=0; $i < count($campos); $i++) { 
                    if($tipos[$i] == 'd'){
                        $sumas[$i] += $reg[$i];
                    }else{
                        $sumas[$i] = "";
                    }
                    echo "<td class='$class[$i]'>$reg[$i]</td>";
                    
                }
                echo '</tr>';                
            }
            
        ?>
            </tbody>
            <tfoot>
                <tr>
                <?php
                    
                    for ($i=0; $i < count($campos); $i++) { 
                        echo "<th class='$class[$i]'>$sumas[$i]</th>";
                    }
                       
                ?>
                </tr>
            </tfoot>
        </table>
        <?php
        
        break;

        
    case "traza_x_turno":
        $sql="(SELECT e.`fechac`, 0 segund, 1 ubicac, n.nombre, '' factur, '' estacion, 0 montof, e.id, ifnull(e.tidoc,'')tidoc, '' subproc FROM `ticket_enc` e LEFT JOIN enum_ubic n ON n.`id`=1 WHERE ";
        $sql.= str_replace('codigt','id', $wher);
        $sql.=") UNION ALL (SELECT e.`fechac`, seg_to_hora(e.`segund`)segund, e.`ubicac`, n.`nombre`, e.`factur`, e.`estacion`, e.montof, e.codigt, '' tidoc, e.`subproc` FROM `ticket_det` e
        LEFT JOIN enum_ubic n ON n.`id`=e.`ubicac`
        WHERE $wher and e.ubicac>1 ORDER BY e.codigt DESC, e.fechac ASC) order by id, fechac;";
            //echo $sql;
        $rspta = ejecutarConsulta($sql);
        $rspta2 = ejecutarConsulta($sql);
        //echo $rspta2->num_rows;
        $tidoc = "";
        if($rspta2->num_rows > 1){
            $tidoc = $rspta2->fetch_array()[8];
            $_id = $rspta2->fetch_array()[7];
        }
        if($tidoc=='V'){ ?>
            <div style="text-align: center;">
                <span class="badge bg-success" style="font-size: larger; font-weight: bold; padding: 5px; margin: 5px;">CONTADO (<?=$_id ?>)</span>
            </div>
        <?php }
        if($tidoc=='C'){ ?>
            <div style="text-align: center;">
                <span class="badge bg-warning" style="font-size: larger; font-weight: bold; padding: 5px; margin: 5px;">CREDITO (<?=$_id ?>)</span>
            </div>
        <?php }
        echo '<div class="row">';
        while ($reg=$rspta->fetch_array()) {   
            echo '<div class="col-lg-4 col-md-6 col-sm-12">';
            echo '<div class="alert alert-info mialert">';
            if($reg["subproc"]=='prest'){
                $nombre = "SOLICITA PRESTAMO";
            }    else {
                $nombre = $reg["nombre"];
            }

            echo '<div class="mititulo"><strong>'.$nombre.'</strong></div>';
            echo "<div><strong>Fecha y hora:</strong> ".$reg["fechac"];
            echo '<strong style="padding-left: 5px; font-size: 20px; color: #D21E23"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></strong> ';
            echo "</div>";
            echo "<div><strong>Tiempo:</strong> ".$reg["segund"]."";
                    
            switch ($reg["ubicac"]) {
                case '2':
                    echo '<strong style="padding-left: 10px">Estación:</strong> '.$reg["estacion"];
                    break; 
                case '5':
                    echo '<strong style="padding-left: 10px">Monto:</strong> ₡ '.number_format($reg["montof"],0);
                    break; 
                case '14':
                    echo '<strong style="padding-left: 10px">Factura:</strong> '.$reg["factur"];
                    break;            
                default:
                    # code...
                    break;
            }
            echo "</div>"; 
            echo "</div>";
            echo "</div>";    
        }     
        echo "</div>";   
        break;

    case "traza_turnos":
        $sql="SELECT e.ticket, e.id, e.nombre FROM `ticket_enc` e
        WHERE $wher ORDER BY e.id DESC";
            //echo $sql;
        $rspta = ejecutarConsulta($sql);
        ?>
        <!-- <select class="form-select selectpicker" data-show-subtext="true" data-live-search="true"  onchange="traza_x_turno();" style="font-size: large;" name="selturnos" id="selturnos"> -->
        <select class="form-select miselect selectpicker" data-show-subtext="true" data-live-search="true"  onchange="traza_x_turno();" size="5" style="height: 400px; font-size: large;" name="selturnos" id="selturnos">
        <?php
        while ($reg=$rspta->fetch_array()) {  
            ?>
                <!-- <option value="<?=$reg["id"]?>"><?=$reg["id"]." - ".$reg["ticket"]." - ".$reg["nombre"]?></option> -->
                <option value="<?=$reg["id"]?>"><?=$reg["ticket"]." - ".$reg["nombre"]?></option>
            <?php
        }      
        echo '</select>';
        break;
    
    case "comp_dia_emisi_x_hora_d":
        $fecha1=$_POST["fecha1"];
        $fecha2=$_POST["fecha2"];
        $tot1 = 0;
        $tot2 = 0;
        $max = "";
        $yellowFrom = 150;
        $redFrom = 175;
        $gl = "";
        $sql="SELECT tienda.NombreHora(DATE_FORMAT(fechac,'%H'))hora, IFNULL(f1.cant,0) c1, IFNULL(f2.cant,0) c2 FROM `ticket_enc` e 
        LEFT JOIN (SELECT tienda.NombreHora(DATE_FORMAT(fechac,'%H'))hora, COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND DATE_FORMAT(e.fechac,'%Y-%m-%d') = '$fecha1' GROUP BY DATE_FORMAT(fechac,'%H')) AS f1 ON f1.hora=tienda.NombreHora(DATE_FORMAT(fechac,'%H'))
        LEFT JOIN (SELECT tienda.NombreHora(DATE_FORMAT(fechac,'%H'))hora, COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND DATE_FORMAT(e.fechac,'%Y-%m-%d') = '$fecha2' GROUP BY DATE_FORMAT(fechac,'%H')) AS f2 ON f2.hora=tienda.NombreHora(DATE_FORMAT(fechac,'%H'))
        WHERE $wher AND DATE_FORMAT(e.fechac,'%Y-%m-%d') IN ('$fecha1','$fecha2') GROUP BY DATE_FORMAT(fechac,'%H');";
            //echo $sql;
        $rspta = ejecutarConsulta($sql);
        echo '<div class="col-sm-12 col-md-4">';
            ?>
            <table class="table table-bordered table-striped table-hover table-sm blueTable">
            
            <!-- echo '<table class="table table-striped table-hover table-bordered table-sm">'; -->
            <thead><tr><th>HORA</th><th><?=$fecha1?></th><th><?=$fecha2?></th></tr></thead><tbody>
            <?php
            while ($reg=$rspta->fetch_array()) {  
                ?>
                <tr>
                    <td><?=$reg["hora"]?></td>
                    <td><?=$reg["c1"]?></td>
                    <td><?=$reg["c2"]?></td>
                </tr>                    
                <?php
                $tot1 += $reg["c1"];
                $tot2 += $reg["c2"];
                //$dh.='"'.$reg["hora"].'",';
                //$f1.=$reg["c1"].',';
                //$f2.=$reg["c2"].',';
                $gl.='[\''.$reg["hora"].'\','.$reg["c1"].','.$reg["c2"].'],';
            }
            $max = $tot1;
            if ($max < $tot2) $max = $tot2;
            if ($max <= 200) {
                $max = 200;
            }else{
                $max = round($max/8,0);
                $yellowFrom = $max*6;
                $redFrom = $max*7;
                $max = ($max*8)+5;
            }

            echo '</tbody><tfoot><tr><th>Total:</th><th>';
            ?>
            <div align="center">
                <div id="gauge_div" style="width:150px; height: 140px; align-items: center;"></div>
            </div>
            <?php
            echo '</th><th>';
            ?>
            <div align="center">
                <div id="gauge_div2" style="width:150px; height: 140px; align-items: center;"></div>
            </div>
            <?php
            echo '</th></tr></tfoot></table>';      
        echo '</div>';
        
        ?>           
            <script type="text/javascript">
                google.charts.load('current', {'packages':['gauge']});
                google.charts.setOnLoadCallback(drawGauge);

                var gaugeOptions = {min: 0, max: <?=$max?>, yellowFrom: <?=$yellowFrom?>, yellowTo: <?=$redFrom?>,
                redFrom: <?=$redFrom?>, redTo: <?=$max?>, minorTicks: 5,
                animation:{
                    duration: 3000,
                    easing: 'out',
                    start: true
                },
                };
                var gauge;
                var gauge2;

                function drawGauge() {
                gaugeData = new google.visualization.DataTable();
                gaugeData.addColumn('number', '');
                gaugeData.addRows(1);
                gaugeData.setCell(0, 0, <?=$tot1?>);

                gaugeData2 = new google.visualization.DataTable();
                gaugeData2.addColumn('number', '');
                gaugeData2.addRows(1);
                gaugeData2.setCell(0, 0, <?=$tot2?>);

                gauge = new google.visualization.Gauge(document.getElementById('gauge_div'));
                gauge.draw(gaugeData, gaugeOptions);
                gauge2 = new google.visualization.Gauge(document.getElementById('gauge_div2'));
                gauge2.draw(gaugeData2, gaugeOptions);
                }
  
            </script>
            
        <?php
        echo '</div>';
        echo '<div class="col-sm-12 col-md-8">';
        $gl = substr($gl,0,strlen($gl)-1);
        //echo $gl;
        ?>



        <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Hora', '<?=$fecha1?>', '<?=$fecha2?>'],
            <?=$gl?>
            ]);

            var options = {            
            title: 'Comparativo de visitas por hora',
            legend: { position: 'bottom' },
            lineWidth: 3,
            pointSize:5,
            curveType: 'function',
            bar: {groupWidth: '95%'},
            vAxis: { gridlines: { count: 2 }, title: 'Visitas'},
            hAxis: { title: 'Horas', gridlines: {count:0, color: '#123'}}, 
                colors: ['#204489', '#E12227']
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);

        }      
        </script>
        <div id="curve_chart" style="width: 100%; height: 500px;"></div>
        
        <?php
        echo '</div>';
        break;

    
    case "turnos_por_ubicacion_enc":
        ?>
        <form onsubmit="turnos_por_ubicacion(); return false" class="form" method="post">
            <div class="row" style="margin-left: 0px;">
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2">
                    <div class="form-group">
                        <input type="submit" class="form-control btn btn-info" value="Ver">
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                
                <div id="griddatos_det">
                
                </div>
            
            </div>
        </div>
        <?php
        break;
    case "turnos_por_ubicacion":
        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;
        $tot5 = 0;
        $tot6 = 0;
        $ttot1 = 0;
        $ttot2 = 0;
        $ttot3 = 0;
        $ttot4 = 0;
        $ttot5 = 0;
        $ttot6 = 0;
        
        $sql="SELECT CONCAT(e.`n_sede`,' ',s.`descrip`) n_sede, SUM(IF(posact='0' AND `destin`='V',1,0))EspV, SUM(IF(posact='2',1,0))AteV,SUM(IF(posact='5',1,0))EspC
        , SUM(IF(posact IN ('6','13'),1,0))AteC, SUM(IF(posact IN ('7','14'),1,0))EspE, SUM(IF(posact='8',1,0))AteE 
        , SUM(IF(posact='0' AND `destin`='V',TIMESTAMPDIFF(SECOND, fecham, NOW()),0))tEspV, SUM(IF(posact='2',TIMESTAMPDIFF(SECOND, fecham, NOW()),0))tAteV,SUM(IF(posact='5',TIMESTAMPDIFF(SECOND, fecham, NOW()),0))tEspC, SUM(IF(posact IN ('6','13'),TIMESTAMPDIFF(SECOND, fecham, NOW()),0))tAteC, SUM(IF(posact IN ('7','14'),TIMESTAMPDIFF(SECOND, fecham, NOW()),0))tEspE, SUM(IF(posact='8',TIMESTAMPDIFF(SECOND, fecham, NOW()),0))tAteE 
        FROM ticket_enc e
        INNER JOIN `sedes` s ON s.`sede`=e.`n_sede` 
        WHERE DATE_FORMAT(e.fechac,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_ADD(e.`fecham`,INTERVAL 30 MINUTE)>NOW() GROUP BY e.n_sede;";
            //echo $sql;
        $rspta = ejecutarConsulta($sql);
        echo '<div class="col-sm-12">';
            ?>
            <div class="table-responsive">
                <div style="text-align: center;">
                    <h4>Turnos por puesto y tiempo promedio</h4>
                </div>
            <table id="turnos_por_ubicacion_g" class="table table-striped table-bordered table-condensed table-hover" style="padding: 2px;">
            <thead><tr><th>SEDE</th>
            <th>Esp. Ventas</th><th>Ate. Ventas</th>
            <th>Esp. Cajas</th><th>Ate. Cajas</th>
            <th>Esp. Entrega</th><th>Ate. Entrega</th>
            </tr></thead><tbody>
            <?php
            while ($reg=$rspta->fetch_array()) {  
                ?>
                <tr>
                    <td><?=$reg["n_sede"]?></td>
                    
                    <td><?=($reg["EspV"]>0) ? $reg["EspV"].' -> '.seg_to_hora($reg["tEspV"]/$reg["EspV"]):'0';?></td>
                    <td><?=($reg["AteV"]>0) ? $reg["AteV"].' -> '.seg_to_hora($reg["tAteV"]/$reg["AteV"]):'0';?></td>
                    <td><?=($reg["EspC"]>0) ? $reg["EspC"].' -> '.seg_to_hora($reg["tEspC"]/$reg["EspC"]):'0';?></td>
                    <td><?=($reg["AteC"]>0) ? $reg["AteC"].' -> '.seg_to_hora($reg["tAteC"]/$reg["AteC"]):'0';?></td>
                    <td><?=($reg["EspE"]>0) ? $reg["EspE"].' -> '.seg_to_hora($reg["tEspE"]/$reg["EspE"]):'0';?></td>
                    <td><?=($reg["AteE"]>0) ? $reg["AteE"].' -> '.seg_to_hora($reg["tAteE"]/$reg["AteE"]):'0';?></td>
                </tr>                    
                <?php
                $tot1 += $reg["EspV"];
                $tot2 += $reg["AteV"];
                $tot3 += $reg["EspC"];
                $tot4 += $reg["AteC"];
                $tot5 += $reg["EspE"];
                $tot6 += $reg["AteE"];
                $ttot1 += $reg["tEspV"];
                $ttot2 += $reg["tAteV"];
                $ttot3 += $reg["tEspC"];
                $ttot4 += $reg["tAteC"];
                $ttot5 += $reg["tEspE"];
                $ttot6 += $reg["tAteE"];
            }
            

            echo '</tbody><tfoot><tr><th>General:</th>';            
            ?>
            <th><?=($tot1>0) ? $tot1.' -> '.seg_to_hora($ttot1/$tot1):'0'; ?></th>
            <th><?=($tot2>0) ? $tot2.' -> '.seg_to_hora($ttot2/$tot2):'0'; ?></th>
            <th><?=($tot3>0) ? $tot3.' -> '.seg_to_hora($ttot3/$tot3):'0'; ?></th>
            <th><?=($tot4>0) ? $tot4.' -> '.seg_to_hora($ttot4/$tot4):'0'; ?></th>
            <th><?=($tot5>0) ? $tot5.' -> '.seg_to_hora($ttot5/$tot5):'0'; ?></th>
            <th><?=($tot6>0) ? $tot6.' -> '.seg_to_hora($ttot6/$tot6):'0'; ?></th>
            <?php
            echo '</tr></tfoot></table>';      
            echo '</div></div>';
        ?>
        <script type="text/javascript"> 
            tabla=$('#turnos_por_ubicacion_g').dataTable({
            "aProcessing": true,//activamos el procedimiento del datatable
            "aServerSide": true,//paginacion y filrado realizados por el server
            dom: 'Bfrtip',//definimos los elementos del control de la tabla
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            responsive: true,
            buttons: [
                    {
                    extend: 'print',
                    messageTop: 'Listado de Turnos'
                    },
                    'excelHtml5',
                    'pdf',      
                    'pageLength'   
            ],
            "language": {
                    "url": "esp.json"
                },
            
            "bDestroy":true,
            "iDisplayLength":25,//paginacion
            "order":[[0,"asc"]]//ordenar (columna, orden)
        }).DataTable();
        </script>
        <?php
        break;

    case "estado_en_linea":
        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;
        $tot5 = 0;
        $tot6 = 0;
        $ttot1 = 0;
        $ttot2 = 0;
        $ttot3 = 0;
        $ttot4 = 0;
        $ttot5 = 0;
        $ttot6 = 0;
        
        $sql="SELECT CONCAT(e.`n_sede`,' ',s.`descrip`) n_sede
        , SUM(IF(posact!='0',1,0))total, SUM(IF(e.`posact`='9',1,0))completos,SUM(IF(posact!='0' AND `posact`!='9',1,0))abortados
        ,(SELECT (AVG(e.`segund`))dato FROM `ticket_det` e WHERE DATE_FORMAT(e.fechac,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') AND e.estado=1 AND ubicac IN (2,6,8,13))espera
        ,(SELECT (AVG(e.`segund`))dato FROM `ticket_det` e WHERE DATE_FORMAT(e.fechac,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') AND e.estado=1 AND ubicac IN (5,7,9,14))atencion
        FROM ticket_enc e 
        INNER JOIN `sedes` s ON s.`sede`=e.`n_sede`
        WHERE DATE_FORMAT(e.fechac,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') GROUP BY e.n_sede;";
            //echo $sql;
        $rspta = ejecutarConsulta($sql);
        echo '<div class="col-sm-12">';
            ?>
            <div class="table-responsive">
                <div style="text-align: center;">
                    <h4>Estado en línea por sucursal</h4>
                </div>
            <table id="estado_en_linea_g" class="table table-striped table-bordered table-condensed table-hover" style="padding: 2px;">
            <thead><tr><th>SEDE</th>
            <th>TOTAL</th><th>COMPLETOS</th><th>ABORTADOS</th><th>ESPERA</th><th>ATENCION</th><th>RESPUESTA</th>
            </tr></thead><tbody>
            <?php
            while ($reg=$rspta->fetch_array()) {  
                ?>
                <tr>
                    <td><?=$reg["n_sede"]?></td>
                    
                    <td><?=$reg["total"]?></td>
                    <td><?=$reg["completos"]?></td>
                    <td><?=$reg["abortados"]?></td>
                    <td><?=seg_to_hora($reg["espera"])?></td>
                    <td><?=seg_to_hora($reg["atencion"])?></td>
                    <td><?=seg_to_hora($reg["espera"]+$reg["atencion"])?></td>
                </tr>                    
                <?php
                $tot1 += $reg["total"];
                $tot2 += $reg["completos"];
                $tot3 += $reg["abortados"];
                $tot4 += $reg["espera"];
                $tot5 += $reg["atencion"];
                $tot6 += $reg["espera"]+$reg["atencion"];
                
            }
            

            echo '</tbody><tfoot><tr><th>General:</th>';            
            ?>
            <th><?=$tot1?></th>
            <th><?=$tot2?></th>
            <th><?=$tot3?></th>
            <th><?=seg_to_hora($tot4)?></th>
            <th><?=seg_to_hora($tot5)?></th>
            <th><?=seg_to_hora($tot6)?></th>
            <?php
            echo '</tr></tfoot></table>';      
            echo '</div></div>';
        ?>
        <script type="text/javascript"> 
            tabla=$('#estado_en_linea_g').dataTable({
            "aProcessing": true,//activamos el procedimiento del datatable
            "aServerSide": true,//paginacion y filrado realizados por el server
            dom: 'Bfrtip',//definimos los elementos del control de la tabla
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            responsive: true,
            buttons: [
                    {
                    extend: 'print',
                    messageTop: 'Listado de Turnos'
                    },
                    'excelHtml5',
                    'pdf',      
                    'pageLength'   
            ],
            "language": {
                    "url": "esp.json"
                },
            // "ajax":
            // {
            //     url:'../ajax/a_usuario.php?op=listar',
            //     type: "get",
            //     dataType : "json",
            //     error:function(e){
            //         console.log(e.responseText);
            //     }
            // },
            "bDestroy":true,
            "iDisplayLength":25,//paginacion
            "order":[[0,"asc"]]//ordenar (columna, orden)
        }).DataTable();
        </script>
        <?php
        break;

    case "estudio_detallado":
        $g20 = 0;
        $h21 = 0;
        $i22 = 0;
        $h18 = 0;
        $i17 = 0;
        $j16 = 0;
        $j20 = 0;
        $j21 = 0;
        $j22 = 0;
        $i20 = 0;
        
        
        $sql="SELECT 'VENTAS' destin, COUNT(*)COMPL,
        (SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='V')TOTAL,
        (SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='V' AND posact=9)final 
        FROM `ticket_enc` e WHERE $wher AND posact IN (2) 
        UNION 
        SELECT 'CAJAS' destin, COUNT(*)COMPL,
        (SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='C')TOTAL,
        (SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='C' AND posact=9)final  
        FROM `ticket_enc` e WHERE $wher AND posact IN (13, 6) 
        UNION 
        SELECT 'ENTREGAS' destin, COUNT(*)COMPL,
        (SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='E')TOTAL,
        (SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='E' AND posact=9)final 
        FROM `ticket_enc` e WHERE $wher AND posact IN (8);";        
        //    echo $sql;
        $rspta = ejecutarConsulta($sql);
        while ($reg=$rspta->fetch_array()) { 
            switch ($reg['destin']) {
                case 'VENTAS':
                    $g20 = $reg['TOTAL'];
                    $h18 = $reg['COMPL'];
                    $j20 = $reg['final'];
                    break;
                case 'CAJAS':
                    $h21 = $reg['TOTAL'];
                    $i17 = $reg['COMPL'];
                    $j21 = $reg['final'];
                    break;
                case 'ENTREGAS':
                    $i22 = $reg['TOTAL'];
                    $j16 = $reg['COMPL'];
                    //$j22 = $reg['final'];
                    break;
            }
        }
        
        $sql = "SELECT SUM(IFNULL((SELECT 1 FROM `ticket_det` d WHERE d.codigt=e.`id` AND d.estado=1 AND d.`ubicac`=7 LIMIT 1),0))cant FROM `ticket_enc` e 
        WHERE $wher AND e.`destin`='V';";
        $rspta = ejecutarConsultaSimpleFila($sql);
        $i20=$rspta['cant'];

        $j17 = $h21-$i17-$j21;
        $i18 = $g20-$h18-$i20;
        $j18 = $i20-$j20;
        $k16 = $j16+$i17+$j17+$h18+$i18+$j18;//total de abandonos
        $h20 = $g20-$h18; //pasa a cajas desde ventas
        $i21 = $h21-$i17;//pasa a entregas desde cajas
        $j22 = $i22-$j16;// finaliza desde entregas
        $h23 = $h20+$h21;//total ventas
        $i23 = $i20+$i21+$i22;//total entregas
        $j23 = $j20+$j21+$j22;//total completos
        $k23 = $g20+$h21+$i22;//total 

        $sql = "SELECT 'EV' area, ubicac, `seg_to_hora`(AVG(e.`segund`))tiempo FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 2 UNION
        SELECT 'AV' area, ubicac, `seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 5 UNION
        SELECT 'EC' area, '6,13' ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac in (6,13) UNION
        SELECT 'AC' area, '7,14' ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac in (7,14) UNION
        SELECT 'EE' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 8 UNION
        SELECT 'AE' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 9;";
        //echo $sql;
        $rspta = ejecutarConsulta($sql);
        while ($reg=$rspta->fetch_array()) { 
            switch ($reg['area']) {
                case 'EV':
                    $ev = $reg['tiempo'];
                    break;
                case 'AV':
                    $av = $reg['tiempo'];
                    break;
                case 'EC':
                    $ec = $reg['tiempo'];
                    break;
                case 'AC':
                    $ac = $reg['tiempo'];
                    break;
                case 'EE':
                    $ee = $reg['tiempo'];
                    break;
                case 'AE':
                    $ae = $reg['tiempo'];
                    break;                            
            }
        }

        echo '<div class="col-sm-12">';
            ?>
            <div class="table-responsive">
                <div style="text-align: center;">
                    <h4>Estudio detallado</h4>
                </div>
                
                <table id="estudio_detallado_g" class="table table-bordered table-condensed mitabla" 
                style="padding: 2px; text-align: center; vertical-align: middle;">
                <tr>
                    <td colspan="2" rowspan="3" class="mititulo">Abandono</td><td></td><td></td><td></td>
                    <td style="background-color: #FBE2D5;"><label title="Abandono entrega"><?=$j16?></label></td>
                    <td rowspan="3" style="background-color: #FFFF00; font-size: 25px; font-weight: bold;">
                        <label title="Total de Abandonos"><?=$k16?></label>
                    </td>                   
                </tr>
                <tr>
                    <td></td><td></td>
                    <td style="background-color: #DAF2D0;"><label title="Abandono cajas"><?=$i17?></label></td>
                    <td><label title="Abandono desde cajas"><?=$j17?></label></td>                    
                </tr>
                <tr>
                    <td></td><td style="background-color: #94DCF8;"><label title="Abandono ventas"><?=$h18?></label></td>
                    <td><label title="Abandono ventas->cajas a entrega"><?=$i18?></label></td>
                    <td><label title="Abandono ventas->cajas->entrega en entrega"><?=$j18?></label></td>                    
                </tr>
                <tr>
                    <td colspan="2" class="mititulo">Etapa</td><td class="mititulo">Ventas</td><td class="mititulo">Cajas</td>
                    <td class="mititulo">Entrega</td><td class="mititulo">Completos</td><td class="mititulo" style="background-color: #D9D9D9;">TC Parcial</td>                   
                </tr>

                <tr>    
                    <td colspan="2" rowspan="3" class="mititulo">Visitas y derivación</td>
                    <td style="background-color: #61CBF3;"><label title="Selección ventas"><?=$g20?></label></td>
                    <td style="background-color: #94DCF8;"><label title="Pasa a cajas"><?=$h20?></label></td>
                    <td style="background-color: #CAEDFB; color: red;"><label title="Pasa a entregas"><?=$i20?></label></td>
                    <td style="background-color: #F2CEEF; color: red;"><label title="Finaliza desde ventas"><?=$j20?></label></td>
                    <td style="background-color: #D9D9D9;"><label title="TC ventas">
                    <?php 
                        if($j20==0){
                            echo "0";
                        }else{
                            echo number_format($j20/$g20*100,0);
                        }                        
                    ?>
                    %</label></td>
                </tr>
                <tr>
                    <td></td><td style="background-color: #B5E6A2;"><label title="Selección cajas"><?=$h21?></label></td>
                    <td style="background-color: #DAF2D0;"><label title="Pasa a Entrega"><?=$i21?></label></td>
                    <td style="background-color: #F2CEEF; color: red;"><label title="Finaliza desde cajas"><?=$j21?></label></td>
                    <td style="background-color: #D9D9D9;"><label title="TC cajas">
                    <?php 
                        if($j21==0){
                            echo "0";
                        }else{
                            echo number_format($j21/$h21*100,0);
                        }                        
                    ?>
                    %</label></td>
                    
                </tr>
                <tr>
                    <td></td><td></td><td style="background-color: #F7C7AC;"><label title="Selección entregas"><?=$i22?></label></td>
                    <td style="background-color: #F2CEEF; color: black;"><label title="Finaliza desde entrega"><?=$j22?></label></td>
                    <td style="background-color: #D9D9D9;"><label title="TC entregas">
                    <?php 
                        if($j22==0){
                            echo "0";
                        }else{
                            echo number_format($j22/$i22*100,0);
                        }                        
                    ?>
                    %</label></td>
                           
                </tr>
                <tr>
                    <td colspan="2" class="mititulo">Total</td>
                    <td><label title="Total ventas"><?=$g20?></label></td>
                    <td><label title="Total cajas"><?=$h23?></label></td>
                    <td><label title="Total entregas"><?=$i23?></label></td>
                    <td><label title="Total completos"><?=$j23?></label></td>
                    <td><label title="Total visita"><?=$k23?></label></td>                  
                </tr>
                <tr style="height: 10px;"></tr>
                <tr><td rowspan="2" class="mititulo">Tiempos</td>
                    <td class="mititulo">Espera</td>
                    <td><label><?=$ev?></label></td>
                    <td><label><?=$ec?></label></td>
                    <td><label><?=$ee?></label></td>
                    <td colspan="2" class="mititulo">Tasa Completación Global</td>
                </tr>
                <tr>
                    <td class="mititulo">Atención</td>
                    <td><label><?=$av?></label></td>
                    <td><label><?=$ac?></label></td>
                    <td><label><?=$ae?></label></td>
                    <td colspan="2" style="background-color: #D9D9D9; font-size: 20px; font-weight: bold;"><label>
                    <?php
                    if($j23==0){
                        echo "0";
                    } else{
                        echo number_format($j23/$k23*100,0);
                    }
                    ?>
                    %</label></td>                   
                </tr>
                </table>
            </div>
            
            <?php
            
            echo '</div>';
        
        break;
}

function seg_to_hora($segundos) {
    $minutos = $segundos / 60;
    $horas = floor($minutos / 60);
    $minutos2 = $minutos % 60;
    $segundos_2 = $segundos % 60 % 60 % 60;
    if ($minutos2 < 10) 
        $minutos2 = '0'.$minutos2;
    
    if ($segundos_2 < 10) 
        $segundos_2 = '0'.$segundos_2;
    
    if ($segundos < 60) { /* segundos */
        $resultado = round($segundos).' Seg';
    }
    elseif($segundos > 60 && $segundos < 3600) { /* minutos */
        $resultado = $minutos2
            .':'
            .$segundos_2
            .' Min';
    } else { /* horas */
        $resultado = $horas . ':' . $minutos2 . ':' . $segundos_2 . ' Horas';
    }
    return $resultado;
  }
 ?>


