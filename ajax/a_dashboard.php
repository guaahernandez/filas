<?php 

if (strlen(session_id())<1) 
	session_start();

require "../config/Conexion.php";

$wher = $_POST["wher"];
$name = isset($_POST["name"]) ? $_POST["name"] : "";

switch ($_GET["op"]) {
    
    case 'totvisitas':  
        
        $sql="SELECT COUNT(*)CANT FROM `ticket_enc` e WHERE ".$wher ;
        //echo $sql;
	    $fila = ejecutarConsultaSimpleFila($sql);
        echo $fila["CANT"];
        break;

    case 'totcompras':
        $sql="SELECT IFNULL(SUM(d.`montof`),0)MONTO FROM `ticket_enc` e INNER JOIN `ticket_det` d ON d.`codigt`=e.`id` 
        WHERE $wher /*AND e.estado=3*/ AND d.`ubicac`='5';";
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '₡'.number_format($fila["MONTO"],0);
        break;

    case 'completos':
        $sql="SELECT count(*)CANT FROM `ticket_enc` e INNER JOIN `ticket_det` d ON d.`codigt`=e.`id` 
        WHERE $wher AND d.`destin`='completo';";

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
            SELECT CONCAT('\"',DATE_FORMAT(e.fechac,'%d/%m'),'\" ')dia, COUNT(*)total, count(d.codigt)completo FROM `ticket_enc` e 
            LEFT JOIN `ticket_det` d ON d.`codigt`=e.`id` AND d.`destin`='COMPLETO'
            WHERE $wher
            GROUP BY DATE_FORMAT(e.fechac,'%y-%M-%d') ORDER BY e.fechac)AS s;";
            //echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        //print_r($fila);
        //echo json_encode($fila);
        ?>
            <canvas id="agrafica"></canvas>
            <!-- <canvas id="agrafica" style="max-height: 350px; max-width: 850px;"></canvas> -->
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
            label: "Completos",
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
                        text: 'Turnos diarios',
                    }
                }
            }
        });
        </script>
        <?php
        break;

    case 'turnostot':
        $sql="SELECT COUNT(*)total, COUNT(d.`codigt`)compl FROM `ticket_enc` e 
        LEFT JOIN `ticket_det` d ON d.`codigt`=e.`id` AND d.`destin`='completo'
        WHERE $wher;";
        //echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
            // echo '<h4 class="card-title">Visitas - estados</h4>';
        ?>
            <canvas id="aturnostot"></canvas>
            <!-- <canvas id="agrafica" style="max-height: 350px; max-width: 850px;"></canvas> -->
        <script>
            // Obtener una referencia al elemento canvas del DOM
        var $grafica = document.querySelector("#aturnostot");
        // Pasaamos las etiquetas desde PHP
        
        var etiquetas = ["Emitidos", "Completos", "Cancelados"];
        // Podemos tener varios conjuntos de datos. Comencemos con uno
        var dato_total = {
            label: "Turnos",
            // Pasar los datos igualmente desde PHP
            data: [<?=$fila["total"]?>, <?=$fila["compl"]?>, <?=$fila["total"]-$fila["compl"]?> ],
            backgroundColor: [
            '#204489',
            '#E12227',
            '#FEE605'
            ],
            // borderColor: [
            // 'rgba(54, 162, 235,1)',
            // 'rgba(46, 209, 113,1)',
            // 'rgba(255, 61, 103,1)'
            // ],
            //backgroundColor: 'rgba(39, 169, 227, 0.2)', // Color de fondo
            //borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
            //borderWidth: 2, // Ancho del borde
        };
        graf = new Chart($grafica, {
            type: 'doughnut', // Tipo de gráfica
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Turnos por estado',
                        
                    },
                    legend:{
                        position : 'top'
                    }
                }
            },
            data: {
                labels: etiquetas,
                datasets: [
                    dato_total,                  
                    // Aquí más datos...
                ]
            },
            
        });
        
        </script>
        <?php
        break;

    case 'turnosetapas':
        $sql="SELECT GROUP_CONCAT(destin)destin, GROUP_CONCAT(cant)cant, SUM(cant)total FROM(
            SELECT CONCAT('\"',`fun_destino2`(e.destin),'\"')destin, COUNT(*)cant FROM `ticket_enc` e 
            WHERE $wher GROUP BY e.`destin`) AS s;";
            //echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        if($name == "aturnosetapas_m"){
            echo '<canvas id="'.$name.'" style="max-height: 450px;"></canvas>';
        }else{
            echo '<canvas id="'.$name.'"></canvas>';
        }
        ?>
            <!-- <canvas id="<?=$name?>"></canvas> -->
            <!-- <canvas id="agrafica" style="max-height: 350px; max-width: 850px;"></canvas> -->
        <script>
            // Obtener una referencia al elemento canvas del DOM
        var $grafica = document.querySelector("#<?=$name?>");
        // Pasaamos las etiquetas desde PHP
        
        var etiquetas = [<?=$fila["destin"]?>];
        // Podemos tener varios conjuntos de datos. Comencemos con uno
        var dato_total = {
            label: "Destino",
            // Pasar los datos igualmente desde PHP
            data: [<?=$fila["cant"]?>],
            backgroundColor: [
                '#E12227',
                '#FEE605',
                '#204489'
            ],
            // borderColor: [
            // 'rgba(54, 162, 235,0.8)',
            // 'rgba(46, 209, 113,0.8)',
            // 'rgba(255, 61, 103,0.8)'
            // ],
            //backgroundColor: 'rgba(39, 169, 227, 0.2)', // Color de fondo
            //borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
            //borderWidth: 2, // Ancho del borde
        };
        graf = new Chart($grafica, {
            type: 'doughnut', // Tipo de gráfica
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Destino selec. en kiosco',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    legend:{
                        position : 'top'
                    }
                }
            },
            data: {
                labels: etiquetas,
                datasets: [
                    dato_total,                  
                    // Aquí más datos...
                ]
            },
            
        });
        
        </script>
        
        <?php
        break;

    case 'turnosetapas2':
        $sql="SELECT GROUP_CONCAT(destin)destin, GROUP_CONCAT(cant)cant, SUM(cant)total FROM(
            SELECT CONCAT('\"',destin,'\"') destin, COUNT(*)cant FROM `ticket_det` e 
            WHERE $wher AND ubicac IN(2, 6, 8) GROUP BY destin) as s;";
        //echo $sql; 
        $fila = ejecutarConsultaSimpleFila($sql);
            // echo '<h4 class="card-title">Visitas - etapas</h4>';
        ?>
            <canvas id="aturnosetapas2"></canvas>
            <!-- <canvas id="agrafica" style="max-height: 350px; max-width: 850px;"></canvas> -->
        <script>
            // Obtener una referencia al elemento canvas del DOM
        var $grafica = document.querySelector("#aturnosetapas2");
        // Pasaamos las etiquetas desde PHP
        
        var etiquetas = [<?=$fila["destin"]?>];
        // Podemos tener varios conjuntos de datos. Comencemos con uno
        var dato_total = {
            label: "Etapas",
            // Pasar los datos igualmente desde PHP
            data: [<?=$fila["cant"]?>],
            backgroundColor: [
                '#E12227',
                '#FEE605',
                '#204489'
            ],
            
        };
        graf = new Chart($grafica, {
            type: 'doughnut', // Tipo de gráfica
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Tramites por etapa',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    legend:{
                        position : 'top'
                    }
                }
            },
            data: {
                labels: etiquetas,
                datasets: [
                    dato_total,                  
                    // Aquí más datos...
                ]
            },
            
        });
        
        </script>
        <?php
        break;

    case 'canceladosxetapa':
            $sql="SELECT GROUP_CONCAT(destin)destin, GROUP_CONCAT(cant)cant, SUM(cant)total FROM (
                SELECT '\"Ventas\"' destin, COUNT(*)cant FROM (SELECT IFNULL(COUNT(*),0)cant FROM `ticket_det` e WHERE $wher AND ubicac IN(2, 5) GROUP BY codigt HAVING cant < 2) AS s1 UNION
                SELECT '\"Cajas\"' destin, COUNT(*)cant FROM (SELECT IFNULL(COUNT(*),0)cant FROM `ticket_det` e WHERE $wher AND ubicac IN(13, 7) GROUP BY codigt HAVING cant < 2) AS s2 UNION
                SELECT '\"Entregas\"' destin, COUNT(*)cant FROM (SELECT IFNULL(COUNT(*),0)cant FROM `ticket_det` e WHERE $wher AND ubicac IN(8, 9) GROUP BY codigt HAVING cant < 2)AS s3) AS s;";
            //echo $sql; 
            $fila = ejecutarConsultaSimpleFila($sql);
                // echo '<h4 class="card-title">Visitas - etapas</h4>';
            ?>
                <canvas id="acanceladosxetapa"></canvas>
                <!-- <canvas id="agrafica" style="max-height: 350px; max-width: 850px;"></canvas> -->
            <script>
                // Obtener una referencia al elemento canvas del DOM
            var $grafica = document.querySelector("#acanceladosxetapa");
            // Pasaamos las etiquetas desde PHP
            
            var etiquetas = [<?=$fila["destin"]?>];
            // Podemos tener varios conjuntos de datos. Comencemos con uno
            var dato_total = {
                label: "Etapas",
                // Pasar los datos igualmente desde PHP
                data: [<?=$fila["cant"]?>],
                backgroundColor: [
                    '#E12227',
                    '#FEE605',
                    '#204489'
                ],
                
            };
            graf = new Chart($grafica, {
                type: 'doughnut', // Tipo de gráfica
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Tramites cancelados por etapa',
                            padding: {
                                top: 10,
                                bottom: 30
                            }
                        },
                        legend:{
                            position : 'top'
                        }
                    }
                },
                data: {
                    labels: etiquetas,
                    datasets: [
                        dato_total,                  
                        // Aquí más datos...
                    ]
                },
                
            });
            
            </script>
            <?php
            break;
    case "turnosxvendedor":
        $sql = "SELECT IFNULL(c.`nombl03`,'n/a')nombre, COUNT(*) cant, IFNULL(SUM(IF(d.`montof`>0,1,0)),0)cantmont, IFNULL(SUM(d.`montof`),0)monto, ifnull(c.nombc03,'n/a')nombc03 FROM `ticket_enc` e 
        LEFT JOIN `cias_vendedores_img` c ON c.`nombc03`=e.`agnomb` 
        LEFT JOIN `ticket_det` d ON d.`codigt`=e.`id` AND d.`ubicac`=5
        where $wher AND e.destin='v' GROUP BY agnomb ORDER BY cant DESC";
        //        echo $sql;
        $rspta = ejecutarConsulta($sql);
        ?>
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
                                <h5 class="card-title">Turnos: <?=$reg["cant"]?></h5>
                                <h3 class="card-text">₡<?=number_format($reg["monto"],0)?></h3>
                                <h5 class="card-title"><?=number_format($reg["cantmont"]/$reg["cant"]*100,0)?>%</h5>
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
        $sql = "SELECT 'Espera' posic,'ventas' area, ubicac, `seg_to_hora`(AVG(e.`segund`))tiempo FROM `ticket_det` e WHERE $wher AND ubicac = 2 UNION
        SELECT 'Atención' posic,'ventas' area, ubicac, `seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND ubicac = 5 UNION
        SELECT 'Espera' posic,'cajas' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND ubicac = 13 UNION
        SELECT 'Atención' posic,'cajas' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND ubicac = 7 UNION
        SELECT 'Espera' posic,'entregas' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND ubicac = 8 UNION
        SELECT 'Atención' posic,'entregas' area, ubicac,`seg_to_hora`(AVG(e.`segund`)) FROM `ticket_det` e WHERE $wher AND ubicac = 9;";
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
        $sql="SELECT GROUP_CONCAT(CONCAT('\"',hora,'\"'))hora, GROUP_CONCAT(cant) cant FROM (
            SELECT tienda.NombreHora(DATE_FORMAT(fechac,'%H'))hora, COUNT(*)cant FROM `ticket_enc` e where $wher GROUP BY DATE_FORMAT(fechac,'%H')) AS s;";
            ///echo $sql;
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
        var dato_total = {
            label: "Emisiones",
            // Pasar los datos igualmente desde PHP
            data: [<?=$fila["cant"]?>],
            backgroundColor: '#204489', // Color de fondo
            //borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
            borderColor: '#204489', // Color del borde
            borderWidth: 1.5, // Ancho del borde
            //tension: 0.2,
        };

        new Chart($grafica, {
            type: 'bar', // Tipo de gráfica
            data: {
                labels: etiquetas,
                datasets: [
                    dato_total            
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
                        text: 'Emisiones por hora'
                    }
                }
            }
        });
        </script>
        <?php
        break;
    case "esperageneral":
        $sql="SELECT `seg_to_hora`(AVG(e.`segund`))dato FROM `ticket_det` e WHERE $wher AND ubicac IN (2,8,13);";
            ///echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;
    case "atenciongeneral":
        $sql="SELECT `seg_to_hora`(AVG(e.`segund`))dato FROM `ticket_det` e WHERE $wher AND ubicac IN (5,9,14);";
            ///echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>'.$fila["dato"].'</h2>';
        break;
    case "porcentajecompra":
        $sql="SELECT COUNT(*)tot, (SELECT COUNT(*) FROM `ticket_det` e WHERE $wher AND ubicac IN (5) AND e.`montof`>0)venta FROM `ticket_det` e WHERE $wher AND ubicac IN (2);";
        //echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql);
        if($fila["tot"]>0){
            echo '<h2>'.number_format((($fila["venta"]/$fila["tot"]) * 100),0).'%</h2>';
        }else{
            echo '<h2>0%</h2>';
        }
        break;
    case "ingresoporventas":
        $sql="SELECT ifnull(SUM(e.`montof`),0)venta FROM `ticket_det` e WHERE $wher AND ubicac IN (5) AND e.`montof`>0;";
            //echo $sql;
        $fila = ejecutarConsultaSimpleFila($sql); ?>
        <h2><a href="#" data-bs-toggle="modal" onclick="griddatostotal('');" data-bs-target="#modaldatos">₡<?=number_format($fila["venta"],0)?></a></h2>
        <?php
        break;

    case "griddatostotal":
        $sql="SELECT e.n_sede, e.ticket, e.`id`, e.`nombre`, e.`agnomb`, e.fechac, seg_to_hora(SUM(segund))tiempo,
        (SELECT SUM(montof) FROM `ticket_det` t WHERE t.codigt=e.`id` AND t.ubicac=5)monto
        FROM `ticket_enc` e
        LEFT JOIN `ticket_det` d ON d.`codigt`=e.`id`
        WHERE $wher GROUP BY e.`id`;";
            //echo $sql;
        $rspta = ejecutarConsulta($sql);

        $iwher = str_replace("' and '", " al ", $wher);
        $iwher = str_replace('and e.fechac BETWEEN ', ', del ', $iwher);
        $iwher = str_replace(' and e.agnomb=', ', vendedor ', $iwher);
        $iwher = str_replace(' 23:59:59', '', $iwher);
        $iwher = str_replace(' and ', ' ', $iwher);
        $iwher = str_replace('1=1 ', ' ', $iwher);
        $iwher = str_replace('e.n_sede=', 'Sede ', $iwher);
        $iwher = str_replace("'", "", $iwher);
        $iwher = "Total de turnos: ". $iwher;
        ?>
        <!-- <table class="table table-hover table-sm"> -->
        
        <!-- <h4><?=$wher?></h4> -->
        <h4><?=$iwher?></h4>
        <table id="tbllistado" class="table table-striped table-bordered table-sm" style="padding: 2px;">
            <thead>
                <th>SEDE</th><th>TURNO</th><th>CLIENTE</th><th>VENDEDOR</th><th>FECHA</th><th>TIEMPO</th><th>MONTO</th>
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
            <td><?=number_format($reg["monto"],0)?></td>        
            </tr>
        <?php }
        ?>
        </tbody>
        <tfoot>
            <th colspan="7"></th>
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
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
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
        INNER JOIN `ticket_det` d ON d.`codigt`=e.`id` 
        WHERE $wher
        ORDER BY e.`fechac`;";
            //echo $sql;
        $rspta = ejecutarConsulta($sql);

        $iwher = str_replace("' and '", " al ", $wher);
        $iwher = str_replace('and e.fechac BETWEEN ', ', del ', $iwher);
        $iwher = str_replace(' and e.agnomb=', ', vendedor ', $iwher);
        $iwher = str_replace(' 23:59:59', '', $iwher);
        $iwher = str_replace(' and ', ' ', $iwher);
        $iwher = str_replace('1=1 ', ' ', $iwher);
        $iwher = str_replace('e.n_sede=', 'Sede ', $iwher);
        $iwher = str_replace("'", "", $iwher);
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
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "bDestroy":true,
                "iDisplayLength":10,//paginacion
                "order":[[5,"asc"]]//ordenar (columna, orden)
            }).DataTable();

        </script>
        <?php
        break;
    }       
    
 ?>