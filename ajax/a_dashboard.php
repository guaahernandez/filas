<style type="text/css">
    @media only screen and (min-width: 1530px) {
    .mi_pie {
        width: 400px;
        height: 350px;
    }
}
@media only screen and (min-width: 1375px) and (max-width: 1529px) {
    .mi_pie {
        width: 350px;
        height: 300px;
    }
}
@media only screen and (min-width: 992px) and (max-width: 1374px) {
    .mi_pie {
        width: 300px;
        height: 280px;
    }
}
@media only screen and (min-width: 600px) and (max-width: 991px) {
    .mi_pie {
        width: 500px;
        height: 450px;
    }
}
@media only screen and (min-width: 400px) and (max-width: 599px) {
    .mi_pie {
        width: 450px;
        height: 400px;
    }
}
@media only screen and (max-width: 399px) {
    .mi_pie {
        width: 300px;
        height: 250px;
    }
}
</style>


<?php 

if (strlen(session_id())<1) 
	session_start();

require "../config/Conexion.php";

$color = array(
    "CAJAS" => "#E12227",
    "ENTREGAS" => "#FEE605",
    "VENTAS" => "#205589"
);
$s="";
$i=0;
$g="";
$icolor=0;

$wher = $_POST["wher"];
$name = isset($_POST["name"]) ? $_POST["name"] : "";
$sede = isset($_POST["sede"]) ? $_POST["sede"] : "";
$fini = isset($_POST["fini"]) ? $_POST["fini"] : "";
$ffin = isset($_POST["ffin"]) ? $_POST["ffin"] : "";
$titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";

switch ($_GET["op"]) {
    
    case 'totvisitas':  
        
        $sql="SELECT COUNT(*)CANT FROM `ticket_enc` e WHERE ".$wher ;
        //echo $sql;
	    $fila = ejecutarConsultaSimpleFila($sql);
        echo $fila["CANT"];
        break;

    case 'totcompras':
        $sql="SELECT SUM(f_totalventaxturno2(e.`id`, e.factur, '$fini', '$ffin'))venta FROM `ticket_enc` e 
        WHERE $wher and e.destin = 'V';";
        //echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '₡'.number_format($fila["venta"],0);
        break;

    case 'completos':
        $sql="SELECT count(*)CANT FROM `ticket_enc` e  
        WHERE $wher and e.`posact`='9';";

        //echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo $fila["CANT"];
        break;

    case 'graficomes2':
        // $sql="SELECT DATE_FORMAT(e.fechac,'%d/%m')dia, COUNT(*)total, count(d.codigt)completo FROM `ticket_enc` e 
        //     LEFT JOIN `ticket_det` d ON d.`codigt`=e.`id` AND d.`destin`='COMPLETO'
        //     WHERE $wher
        //     GROUP BY DATE_FORMAT(e.fechac,'%y-%M-%d') ORDER BY e.fechac;";
        //     echo $sql;
        // $rspta = ejecutarConsulta($sql);
        // // echo json_encode($rspta->fetch_assoc());
        // $data = array();
        // $pos = 0;
        //  while ($reg=$rspta->fetch_assoc()) {
		//  	$data[]=$reg;
		//  }
        // echo json_encode($data);
        // break;
    
    case 'graficomes':
        $sql="SELECT GROUP_CONCAT(dia)dias, GROUP_CONCAT(total)total, GROUP_CONCAT(completo)completo FROM(
            SELECT CONCAT('\"',DATE_FORMAT(e.fechac,'%d/%m'),'\" ')dia, count(*)total, SUM(IF(posact='9',1,0))completo 
            FROM `ticket_enc` e
            WHERE $wher
            GROUP BY DATE_FORMAT(e.fechac,'%y-%M-%d') ORDER BY e.fechac
            )AS s;";
        
        //  echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        
        ?>
            <canvas id="agrafica"></canvas>
            
        <script>
            // Obtener una referencia al elemento canvas del DOM
        var $grafica = document.querySelector("#agrafica");
        // Pasaamos las etiquetas desde PHP
		
        var etiquetas = [<?=$fila["dias"]?>];
        // Podemos tener varios conjuntos de datos. Comencemos con uno
        var dato_total = {
            label: "Total",
            // Pasar los datos igualmente desde PHP
            data: [<?=$fila["total"]?>],
            backgroundColor: '#204489', // Color de fondo
            //borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
            borderColor: '#204489', // Color del borde
            borderWidth: 1.5, // Ancho del borde
            tension: 0.2,
        };

        var dato_compl = {
            label: "Completados",
            // Pasar los datos igualmente desde PHP
            data: [<?=$fila["completo"]?>],
            backgroundColor: '#E12227', // Color de fondo
            borderColor: '#E12227', // Color del borde
            borderWidth: 1.5, // Ancho del borde  
            tension : 0.2,
        };
        new Chart($grafica, {
            type: 'bar', // Tipo de gráfica
            data: {
                labels: etiquetas,
                datasets: [
                    dato_total,     
                    dato_compl,               
                    // Aquí más datos...
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {                       
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Total Turnos Diarios Atendidos',
                    }
                }
            }
        });
        </script>
        <?php
        break;

    case 'turnostot':
        // $sql="SELECT COUNT(*)total, COUNT(d.`codigt`)compl FROM `ticket_enc` e 
        // LEFT JOIN `ticket_det` d ON d.`codigt`=e.`id` AND d.estado=1 and d.`destin`='completo'
        // WHERE $wher;";
        $sql="SELECT count(*)total, SUM(IF(posact='9',1,0))compl FROM `ticket_enc` e 
        WHERE $wher;";
            //  echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
            
        ?>            
            
            <!-- <div id="#aturnostot" style="width: 500px; height: 400px;"></div> -->
            <div id="#aturnostot" class="mi_pie"></div>
            <script type="text/javascript">
                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                    ['Estado', 'Turnos'],
                    // ['Emitidos',     <?=$fila["total"]?>],
                    ['Completos',    <?=$fila["compl"]?>],
                    ['Abortados',    <?=$fila["total"]-$fila["compl"]?>]
                    ]);

                    var options = {
                        title: 'Turnos por estado',
                        //pieHole: 0.4,
                        legend: 'top',
                        slices: {
                            0: { color: '#FFC50C' },
                            1: { color: '#2C89EE' },
                            2: { color: '#02A302' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('#aturnostot'));
                    chart.draw(data, options);
                }
        
        </script>
        <?php
        break;

    case 'turnosetapas':
        $g=""; 
        $coma="";        
        $sql = "SELECT 'VENTAS' destin, COUNT(*)COMPL,(SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='V')TOTAL FROM `ticket_enc` e WHERE $wher AND e.`destin`='V' AND posact <= 5 UNION
        SELECT 'CAJAS' destin, COUNT(*)COMPL,(SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='C')TOTAL FROM `ticket_enc` e WHERE $wher AND posact IN (13, 6) AND e.`destin`='C' UNION 
        SELECT 'ENTREGAS' destin, COUNT(*)COMPL,(SELECT COUNT(*)cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='E')TOTAL FROM `ticket_enc` e WHERE $wher AND posact IN (8) AND e.`destin`='E';";
        // $sql = "SELECT `fun_destino2`(e.destin)destin, COUNT(*)cant FROM `ticket_enc` e 
        // WHERE $wher GROUP BY e.`destin`";
        //echo $sql;
        $rspta = ejecutarConsulta($sql);
        while ($reg=$rspta->fetch_assoc()) {
            $g .= $coma.'[\''.$reg["destin"].'\', '.$reg["TOTAL"].', '.$reg["COMPL"].']';
            $s .= $coma.$i.': { color: \''.$color[$reg["destin"]].'\'}';
            $coma=',';
            $i++;
            if($reg["TOTAL"]>0) { $icolor++; }
        }
        if($icolor==1){
            $color = 'black';
        }else{
            $color = 'white';
        }
        //echo $g;
        echo '<div class="mi_pie" id="'.$name.'"></div>';
        ?>

        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawVisualization);

            function drawVisualization() {
                // Some raw data (not necessarily accurate)
                var data = google.visualization.arrayToDataTable([
                ['Etapa', 'Total', 'Abortados'],
                <?=$g?>
                // ['CAJAS',  30, 1],
                // ['ENTREGAS',  22, 15],
                // ['VENTAS',  167,   19]
                ]);

                var options = {
                title : 'Destino Seleccionado en Kiosco',
                legend : 'top',
                seriesType: 'bars',
                colors: ['#205589', '#E12227', '#FEE605'],
                // slices: {
                //     <?=$s?>
                // },
                series: {1: {type: 'area'}}
                };

                var chart = new google.visualization.ComboChart(document.getElementById('<?=$name?>'));
                chart.draw(data, options);
            }

            // google.charts.load("current", {packages:["corechart"]});
            // google.charts.setOnLoadCallback(drawChart);
            // function drawChart() {
            //     var data = google.visualization.arrayToDataTable([
            //     ['Estado', 'Turnos'],
            //     <?=$g?>                
            //     ]);

            //     var options = {
            //     title: 'Destino Seleccionado en Kiosco',
            //     pieHole: 0.45,
            //     legend: 'top',
            //     pieSliceTextStyle: {
            //         color: '<?=$color?>',
            //     },
            //     slices: {
            //         <?=$s?>
            //     }
            //     };
            //     var chart = new google.visualization.PieChart(document.getElementById('<?=$name?>'));
            //     chart.draw(data, options);
            // }
            
        
        </script>
            <?php
        break;

    case 'turnosetapas2':
         
        $coma="";  
        // $sql="SELECT destin, COUNT(*)cant FROM `ticket_det` e 
        //     WHERE $wher AND e.estado=1 and ubicac IN(2, 14, 8) GROUP BY destin;";
        $sql="SELECT `fun_destino3`(ubicac)destin, COUNT(*)cant FROM `ticket_det` e 
        WHERE $wher AND e.estado=1 AND ubicac IN(2,6,8) GROUP BY ubicac;";
        //  echo $sql; 
        $rspta = ejecutarConsulta($sql);
        while ($reg=$rspta->fetch_assoc()) {
            $g .= $coma.'[\''.$reg["destin"].'\', '.$reg["cant"].']';
            $s .= $coma.$i.': { color: \''.$color[$reg["destin"]].'\'}';
            $coma=',';
            $i++;
            if($reg["cant"]>0) { $icolor++; }
        }
        if($icolor==1){
            $color = 'black';
        }else{
            $color = 'white';
        }
        ?>
            <div id="aturnosetapas2" class="mi_pie"></div>
            <!-- <canvas id="agrafica" style="max-height: 350px; max-width: 850px;"></canvas> -->
        <script>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                ['Estado', 'Turnos'],
                <?=$g?>                
                ]);

                var options = {
                title: 'Tramites por Etapa',
                pieHole: 0.45,
                pieSliceTextStyle: {
                    color: '<?=$color?>',
                },
                legend: 'top',
                slices: {
                    <?=$s?> 
                }
                };
                var chart = new google.visualization.PieChart(document.getElementById('aturnosetapas2'));
                chart.draw(data, options);
            }
        
        </script>
        <?php
        break;

    case 'canceladosxetapa':
        $coma="";
        $sql="SELECT IF(IFNULL(SEGMEN,'')='','N/A',segmen)segmen, COUNT(*)cant FROM `ticket_enc` e WHERE $wher and segmen!='' GROUP BY IFNULL(SEGMEN,'');";
        
        //echo $sql; 
        $rspta = ejecutarConsulta($sql);
        while ($reg=$rspta->fetch_assoc()) {
            $g .= $coma.'[\''.$reg["segmen"].'\', '.$reg["cant"].']';
            //$s .= $coma.$i.': { color: \''.$color[$reg["destin"]].'\'}';
            $coma=',';
            $i++;
            //if($reg["cant"]>0) { $icolor++; }
        }
        if($i==1){
            $color = 'black';
        }else{
            $color = 'white';
        }
        ?>
        <div id="asegmentos" class="mi_pie"></div>
            
        <script>
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                ['Segmento', 'Turnos'],
                <?=$g?>                
                ]);

                var options = {
                title: 'Facturación por Segmento',
                pieHole: 0.45,
                pieSliceTextStyle: {
                    color: '<?=$color?>',
                },
                legend: 'top',
                slices: {
                    <?=$s?>     
                }
                };
                var chart = new google.visualization.PieChart(document.getElementById('asegmentos'));
                chart.draw(data, options);
            }            
            </script>
            <?php
            break;
    case "turnosxvendedor":
        /////////usa la funcion f_totalturnoconmonto - todos los turnos con montos aunque no fue facturado
        // $sql = "SELECT IFNULL(c.`nombl03`,vi.`nombl03`)nombre, COUNT(*) cant
        // , IFNULL(SUM(IF(f_totalturnoconmonto(e.`id`)>0,1,0)),0)cantmont
        // , IFNULL(c.nombc03,'n/a')nombc03,SUM(f_totalventaxturno2(e.`id`, e.factur, '$fini', '$ffin'))monto
        // , ifnull(c.nombc03,'n/a')nombc03 FROM `ticket_enc` e 
        // LEFT JOIN `cias_vendedores_img` c ON c.`nombc03`=e.`agnomb` 
        // LEFT JOIN `cias_vendedores` vi ON vi.`nombc03`=e.`agnomb`
        // where $wher AND e.destin='v' GROUP BY agnomb ORDER BY monto DESC, cant DESC";

        $sql = "SELECT IFNULL(c.`nombl03`,vi.`nombl03`)nombre, COUNT(*) cant
        , IFNULL(SUM(IF(e.`factur`!='',1,0)),0)cantmont
        , IFNULL(c.nombc03,'n/a')nombc03,SUM(f_totalventaxturno2(e.`id`, e.factur, '$fini', '$ffin'))monto
        , ifnull(c.nombc03,'n/a')nombc03 FROM `ticket_enc` e 
        LEFT JOIN `cias_vendedores_img` c ON c.`nombc03`=e.`agnomb` 
        LEFT JOIN `cias_vendedores` vi ON vi.`nombc03`=e.`agnomb`
        where $wher AND e.destin='v' GROUP BY agnomb ORDER BY monto DESC, cant DESC";

        //echo $sql;
        
        $rspta = ejecutarConsulta($sql);
        ?>
        <style type="text/css">
            .mcard-title{
                margin-bottom:.5rem;
                font-size: 10px;
                color: #5C636A;
            }
            .mcol{
                margin: 0 !important;
                padding: 2px !important;
            }
            .mborde{
                border-left: #dddddd 1px solid;
            }
        </style>
        <div class="" style="margin-top: 10px; padding: 5px;">
            <h4>Turnos y ventas por vendedor</h4>
            <?php
            echo "<div class='row'>";
                while ($reg=$rspta->fetch_assoc()) {
                    $nombc = $reg["nombc03"];
                    ?>
                    <div class='col-lg-2 col-md-4 col-sm-12'>                    
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modaldatos" onclick="griddatostotal('<?=$nombc?>','')">
                        <div class="card">
                            <h6 class="card-header"><?=$reg["nombre"]?></h6>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 mcol"><p class="mcard-title">Turnos</p></div>
                                    <div class="col-4 mcol mborde"><p class="mcard-title">Con monto</p></div>
                                    <div class="col-4 mcol mborde"><p class="mcard-title">Efectiv.</p></div>

                                    <div class="col-4 mcol"><h6 class="card-title"><?=$reg["cant"]?></h6></div>
                                    <div class="col-4 mcol mborde"><h6 class="card-title"><?=$reg["cantmont"]?></h6></div>
                                    <div class="col-4 mcol mborde"><h6 class="card-title"><?=number_format($reg["cantmont"]/$reg["cant"]*100,0)?>%</h6></div>
                                </div>
                                
                                <h3 class="card-text" style="margin-top: 10px;">₡<?=number_format($reg["monto"],0)?></h3>                                
                                
                            </div>
                        </div>
                        <!-- <div style="background-color: #204489; border-radius: 15px; height: 130px; padding: 10px; margin-top: 5px;">
                            <div class="row">
                                <div class="col-12">
                                    <h6 style="color: #fff;"><?=$reg["nombre"]?></h6>                        
                                </div>
                                <div class="col-12">
                                    <div style="background-color: #fff; margin-left: 10px; margin-right: 10px; border-radius: 5px; color: #204489;">
                                        <h3>Turnos: <?=$reg["cant"]?></h3>
                                        <h3>₡<?=number_format($reg["monto"],0)?></h3>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        </a>
                    </div>
                    <?php
                }
        echo "</div></div>";
        break;
    case "tiempopromventas":
        $sql = "SELECT 'Espera' posic,'ventas' area, ubicac, `seg_to_hora`(AVG(e.`segund`))tiempo FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 2 UNION
        SELECT 'Atención' posic,'ventas' area, ubicac, `seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 5 UNION
        SELECT 'Espera' posic,'cajas' area, '6,13' ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac in (6,13) UNION
        SELECT 'Atención' posic,'cajas' area, '7,14' ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac in (7,14) UNION
        SELECT 'Espera' posic,'entregas' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 8 UNION
        SELECT 'Atención' posic,'entregas' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac = 9;";
        //echo $sql;
        $rspta = ejecutarConsulta($sql);
        
        ?>

        <div class='row'>
        <?php

        while ($reg=$rspta->fetch_assoc()) {
            if($reg["posic"]=='Espera'){ ?>
                <div class='col-lg-4 col-md-6 col-sm-12 card' style="padding: 5px;"><h4>Tiempo promedio <?=$reg["area"]?></h4>
                <div class="row">
            <?php }
            ?>
            
                <div class="col-6">
                    <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#modaldatos" onclick="griddatostotal('','2')">  -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modaldatos" onclick="gridtiemposxubic('<?=$reg["ubicac"]?>')"> 
                        <div style="background-color: #204489; border-radius: 65px; color: #FEE605; height: 130px; width: 130px; padding-top: 30px; margin-top: 5px;">
                        <h5 style="color: white;"><?=$reg["posic"]?></h5>
                        <!-- <div style="border: solid 2px #E12227; border-radius: 65px; color: #204489; 
                        height: 130px; width: 130px; padding-top: 30px; margin-top: 5px;">
                            <h5 style="color: black;"><?=$reg["posic"]?></h5>-->
                            <h3 style="background-color: #fff; margin-left: 10px; margin-right: 10px; border-radius: 5px; color: #204489;"><?=$reg["tiempo"]?></h3>
                        </div>
                    </a>
                </div>
            
            
            <?php
            if($reg["posic"]=='Atención'){ ?>
                </div></div>
            <?php }
        }
        ?>
        </div> <!--fin row -->
        <?php
        
        break;
    case "emisionesporhora":
        // $sql="SELECT GROUP_CONCAT(CONCAT('\"',hora,'\"'))hora, GROUP_CONCAT(cant) cant FROM (
        //     SELECT tienda.NombreHora(DATE_FORMAT(fechac,'%H'))hora, COUNT(*)cant FROM `ticket_enc` e where $wher GROUP BY DATE_FORMAT(fechac,'%H')) AS s;";
            $sql = "SELECT GROUP_CONCAT(CONCAT('\"',hora,'\"'))hora, GROUP_CONCAT(vent) vent, GROUP_CONCAT(caja) caja, GROUP_CONCAT(entr) entr 
            FROM ( 
            SELECT tienda.NombreHora(DATE_FORMAT(fechac,'%H'))hora, SUM(IF(e.`destin`='V',1,0))vent,SUM(IF(e.`destin`='C',1,0))caja, SUM(IF(e.`destin`='E',1,0))entr FROM `ticket_enc` e 
            WHERE $wher GROUP BY DATE_FORMAT(fechac,'%H')
            ) AS s;";
           // echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
     
        ?>
            <canvas id="aemisionesporhora"></canvas>
            <!-- <canvas id="agrafica" style="max-height: 350px; max-width: 850px;"></canvas> -->
        <script>
            // Obtener una referencia al elemento canvas del DOM
        var $grafica = document.querySelector("#aemisionesporhora");
        // Pasaamos las etiquetas desde PHP
		
        var etiquetas = [<?=$fila["hora"]?>];
        // Podemos tener varios conjuntos de datos. Comencemos con uno
        var venta = {
            label: "Ventas",
            data: [<?=$fila["vent"]?>],
            backgroundColor: '<?=$color["VENTAS"]?>', // Color de fondo
            borderColor: '<?=$color["VENTAS"]?>', // Color del borde
            borderWidth: 1.5, // Ancho del borde
        };
        var caja = {
            label: "Cajas",
            data: [<?=$fila["caja"]?>],
            backgroundColor: '<?=$color["CAJAS"]?>', // Color de fondo
            borderColor: '<?=$color["CAJAS"]?>', // Color del borde
            borderWidth: 1.5, // Ancho del borde
        };
        var entrega = {
            label: "Entrega",
            data: [<?=$fila["entr"]?>],
            backgroundColor: '<?=$color["ENTREGAS"]?>', // Color de fondo
            borderColor: '<?=$color["ENTREGAS"]?>', // Color del borde
            borderWidth: 1.5, // Ancho del borde
        };

        new Chart($grafica, {
            type: 'bar', // Tipo de gráfica
            data: {
                labels: etiquetas,
                datasets: [
                    venta, caja, entrega, entrega
                    // Aquí más datos...
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {                       
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Vistantes por Hora'
                    }
                }
            }
        });
        </script>
        <?php
        break;
    case "esperageneral":
        //$sql="SELECT `seg_to_hora`(AVG(e.`segund`))dato FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac IN (2,6,8,13);";
        $sql="SELECT seg_to_hora(SUM(dato))dato FROM(
        SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (2) UNION ALL
        SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (6,13) UNION ALL
        SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (8)) AS s;";
        //    echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;
    case "atenciongeneral":
        //$sql="SELECT `seg_to_hora`(AVG(e.`segund`))dato FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac IN (5,7,9,14);";
        $sql="SELECT seg_to_hora(SUM(dato))dato FROM(
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (5) UNION ALL
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (7,14) UNION ALL
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (9)) AS s;";
        //   echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;
    case "respuestageneral":
        // $sql="SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac IN (2,6,8,13);";
        // $fila = ejecutarConsultaSimpleFila($sql);
        // $dat=$fila["dato"];
        // $sql="SELECT `seg_to_hora`(AVG(e.`segund`)+".$dat.")dato FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac IN (5,7,9,14);";
        $sql="SELECT seg_to_hora(SUM(dato))dato FROM(
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (2) UNION ALL
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (6,13) UNION ALL
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (8) union ALL
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (5) UNION ALL
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (7,14) UNION ALL
            SELECT AVG(e.`segund`)dato FROM `ticket_det` e WHERE $wher AND e.estado=1 AND ubicac IN (9)) AS s;";
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;
    case "porcentajecompra":
        //comentado porque usa todos los tiquets que tengan monto aunque no se facturen
        //$sql="SELECT COUNT(*)tot, (SELECT COUNT(*) FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac IN (5) AND e.`montof`>0)venta FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac IN (2);";
        $sql="SELECT COUNT(*)tot, (SELECT COUNT(*) FROM `ticket_enc` e WHERE $wher AND /*e.estado=3 and*/ e.`destin`='V' AND e.`monto`>0 AND factur!='')venta FROM `ticket_det` e WHERE $wher AND e.estado=1 and ubicac IN (2);";
        //   echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        if($fila["tot"]>0){
            echo '<h2>'.number_format((($fila["venta"]/$fila["tot"]) * 100),0).'%</h2>';
        }else{
            echo '<h2>0%</h2>';
        }
        break;
    
    case "ingresoporventas":
        $sql="SELECT SUM(f_totalventaxturno2(e.`id`, e.factur, '$fini', '$ffin'))venta FROM `ticket_enc` e 
        WHERE $wher and e.destin = 'V';";
        //    echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql); ?>
        <h2><a href="#" data-bs-toggle="modal" onclick="griddatostotal('');" data-bs-target="#modaldatos">₡<?=number_format($fila["venta"],0)?></a></h2>
        <?php
        break;
    case "promedioatendidos":
        $sql="SELECT ROUND(SUM(cant)/SUM(valor),0)dato FROM (
            SELECT COUNT(*)cant, CASE DATE_FORMAT(fechac,'%w') WHEN 0 THEN 0.25 WHEN 6 THEN 0.75 ELSE 1 END AS valor,DATE_FORMAT(fechac,'%Y-%m-%d')  FROM(
            SELECT e.`id`, e.`fechac` FROM `ticket_enc` e 
            INNER JOIN `ticket_det` d ON d.`n_sede`=e.`n_sede` AND d.`codigt`=e.`id` AND d.`ubicac`='2'
            WHERE $wher GROUP BY id) AS s GROUP BY DATE_FORMAT(fechac,'%y%m%d')) AS d;";
        //    echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;
    case "griddatostotal":
        $sql="SELECT e.n_sede, e.ticket, e.`id`, e.`nombre`, e.`agnomb`, e.fechac, seg_to_hora(SUM(segund))tiempo, e.factur
        ,f_totalventaxturno(e.`id`)monto
        ,f_totalnotas(e.factur, '$fini', '$ffin')notas
        FROM `ticket_enc` e
        INNER JOIN `ticket_det` d ON d.`codigt`=e.`id` and d.estado=1
        WHERE $wher and e.destin='V' GROUP BY e.`id`;";
        //    echo $sql;
        $rspta = ejecutarConsulta($sql);

        $iwher = str_replace("' and '", " al ", $wher);
        $iwher = str_replace('and e.fechac BETWEEN ', ', del ', $iwher);
        $iwher = str_replace(' and e.agnomb=', ', vendedor ', $iwher);
        $iwher = str_replace(' 23:59:59', '', $iwher);
        $iwher = str_replace(' and ', ' ', $iwher);
        $iwher = str_replace('1=1 ', ' ', $iwher);
        $iwher = str_replace('e.n_sede=', 'Sede ', $iwher);
        $iwher = str_replace("'", "", $iwher);
        $iwher = str_replace(" e.destin=V", "", $iwher);
        $iwher = "Total de turnos: ". $iwher;
        ?>
        <!-- <table class="table table-hover table-sm"> -->
        
        <!-- <h4><?=$wher?></h4> -->
        <!-- <h4><?=$iwher?></h4> -->
        <h4><?=$titulo?></h4>
        <table id="tbllistado" class="table table-striped table-bordered table-sm" style="padding: 2px;">
            <thead>
                <th>SEDE</th><th>TURNO</th><th>CLIENTE</th><th>VENDEDOR</th><th>FECHA</th><th>TIEMPO</th><th>FACT</th><th>MONTO</th><th>NC</th>
            </thead>
            <tbody>
        <?php
        while ($reg=$rspta->fetch_assoc()) { ?>
            <tr>
            <td><?=$reg["n_sede"]?></td>
            <td><?=$reg["ticket"]?></td>
            <td><?=$reg["nombre"]?></td>
            <td><?=$reg["agnomb"]?></td>
            <td><?=$reg["fechac"]?></td>
            <td><?=$reg["tiempo"]?></td>
            <td><?=$reg["factur"]?></td>
            <td><?=number_format($reg["monto"],0)?></td> 
            <td><?=number_format($reg["notas"],0)?></td>        
            </tr>
        <?php }
        ?>
        </tbody>
        <tfoot>
            <th colspan="9"></th>
        </tfoot>
        </table>

        <script>
              
            tabla=$('#tbllistado').dataTable({
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
                    messageTop: '<?=$iwher?>'
                    },
                    'excelHtml5',
                    'pdf',      
                    'pageLength'   
                ],
                "language": {
                    "url": "esp.json"
                },
                "bDestroy":true,
                "iDisplayLength":10,//paginacion
                "order":[[4,"asc"]]//ordenar (columna, orden)
            }).DataTable();

        </script>
        <?php
        break;

    case "gridtiemposxubic":
        $sql="SELECT e.n_sede, e.ticket, e.`id`, e.`nombre`, e.`agnomb`, e.fechac, segund, seg_to_hora(segund)tiempo, d.montof
        FROM `ticket_enc` e 
        INNER JOIN `ticket_det` d ON d.`codigt`=e.`id` and d.estado=1
        WHERE $wher
        ORDER BY e.`fechac`;";
         //   echo $sql;
        $rspta = ejecutarConsulta($sql);

        $iwher = str_replace("' and '", " al ", $wher);
        $iwher = str_replace('and e.fechac BETWEEN ', ', del ', $iwher);
        $iwher = str_replace(' and e.agnomb=', ', vendedor ', $iwher);
        $iwher = str_replace(' 23:59:59', '', $iwher);
        $iwher = str_replace(' and ', ' ', $iwher);
        $iwher = str_replace('1=1 ', ' ', $iwher);
        $iwher = str_replace('e.n_sede=', 'Sede ', $iwher);
        $iwher = str_replace("'", "", $iwher);
        $iwher = str_replace("d.ubicac=2", ", espera en ventas", $iwher);
        $iwher = str_replace("d.ubicac=5", ", atención en ventas", $iwher);
        $iwher = str_replace("d.ubicac in(6,13)", ", espera en cajas", $iwher);
        $iwher = str_replace("d.ubicac in(7,14)", ", atención en cajas", $iwher);
        $iwher = str_replace("d.ubicac=8", ", espera en entrega", $iwher);
        $iwher = str_replace("d.ubicac=9", ", atención en entrega", $iwher);
        $iwher = "Total de turnos: ". $iwher;
        ?>
        <!-- <table class="table table-hover table-sm"> -->
        
        <!-- <h4><?=$wher?></h4> -->
        <h4><?=$iwher?></h4>
        <table id="tbllistado" class="table table-striped table-bordered table-sm" style="padding: 2px;">
            <thead>
                <th>SEDE</th><th>TURNO</th><th>ID</th><th>CLIENTE</th><th>VENDEDOR</th><th>FECHA</th><th>TIEMPO</th><th>MONTO</th>
            </thead>
            <tbody>
        <?php
        while ($reg=$rspta->fetch_assoc()) { ?>
            <tr>
            <td><?=$reg["n_sede"]?></td>
            <td><?=$reg["ticket"]?></td>
            <td><?=$reg["id"]?></td>
            <td><?=$reg["nombre"]?></td>
            <td><?=$reg["agnomb"]?></td>
            <td><?=$reg["fechac"]?></td>
            <td><?=$reg["tiempo"]?></td>
            <td><?=number_format($reg["montof"],0)?></td>        
            </tr>
        <?php }
        ?>
        </tbody>
        <tfoot>
            <th colspan="8"></th>
        </tfoot>
        </table>

        <script>
                
            tabla=$('#tbllistado').dataTable({
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
                    messageTop: '<?=$iwher?>'
                    },
                    'excelHtml5',
                    'pdf',      
                    'pageLength'   
                ],
                "language": {
                    "url": "esp.json"
                },
                "bDestroy":true,
                "iDisplayLength":10,//paginacion
                "order":[[5,"asc"]]//ordenar (columna, orden)
            }).DataTable();

        </script>
        <?php
        break;
    
    case "prestamo_cant":
        $sql="SELECT count(*)dato 
        FROM `ticket_det` e WHERE $wher AND e.`destin`='REGISTRAR' AND e.`subproc`='prest' ORDER BY e.`consec`;";
        //    echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;

    case "prestamo_prom":
        $sql="SELECT seg_to_hora(IFNULL(AVG(time_prest),0))dato FROM(
            SELECT  
            (SELECT d2.segund FROM `ticket_det` d2 WHERE d2.codigt=e.`codigt` AND d2.consec>e.`consec` ORDER BY d2.`consec` LIMIT 1) time_prest
            FROM `ticket_det` e WHERE $wher AND e.`destin`='REGISTRAR' AND e.`subproc`='prest' ORDER BY e.`consec`) AS s
            WHERE s.time_prest > 0;";
        //    echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;    

    case "prestamo_consulta":
        $sql="SELECT e.`ticket` Tiquete, e.fechac Fecha, te.`agnomb` Agente, te.`factur` Factura, te.`nombre` Nombre
        ,seg_to_hora(IFNULL((SELECT d2.segund FROM `ticket_det` d2 WHERE d2.codigt=e.`codigt` AND d2.consec>e.`consec` ORDER BY d2.`consec` LIMIT 1),0)) Tiempo
        FROM `ticket_det` e 
        LEFT JOIN `ticket_enc` te ON te.`n_sede`=e.`n_sede` AND te.id=e.`codigt`
        WHERE $wher AND e.`destin`='REGISTRAR' AND e.`subproc`='prest' ORDER BY e.`consec`;";
        //    echo $sql;
        $rspta = ejecutarConsulta($sql);
        ?>
        
        <h4>Consulta de prestamos</h4>
        <table id="tbllistado" class="table table-striped table-bordered table-sm" style="padding: 2px;">
            <thead>
                <?php
                $colums = 0;
                $finfo = $rspta->fetch_fields();
                foreach ($finfo as $val) {
                    $colums++;
                    echo('<th>'.$val->name.'</th>');            
                }
                ?>
            </thead>
            <tbody>
        <?php
        while ($reg=$rspta->fetch_array()) {
            echo '<tr>';
            for ($i=0; $i < $colums ; $i++) { 
                echo '<td>'.$reg[$i].'</td>';
            }           
            echo '</tr>'; 
        }
        ?>
        </tbody>
        </table>

        <script>
              
            tabla=$('#tbllistado').dataTable({
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
                    messageTop: 'Consulta de prestamos'
                    },
                    'excelHtml5',
                    'pdf',      
                    'pageLength'   
                ],
                "language": {
                    "url": "esp.json"
                },
                "bDestroy":true,
                "iDisplayLength":10,//paginacion
                "order":[[1,"asc"]]//ordenar (columna, orden)
            }).DataTable();

        </script>
        <?php
        break;  
    }       

      
    
    
 ?>