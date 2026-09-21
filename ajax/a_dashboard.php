<?php 
/**
 * =============================================================================
 * Sistema de Control de Filas - Controlador AJAX del Dashboard Gerencial (a_dashboard.php)
 * =============================================================================
 * Este controlador procesa todas las métricas, KPIs, gráficas y reportes interactivos
 * del panel de control / dashboard principal:
 * 
 * Métricas e Indicadores:
 * - 'totcompras'        : Total monetario de ventas facturadas (descontando notas de crédito).
 * - 'totvisitas'        : Total de clientes / turnos emitidos.
 * - 'completos'         : Total de turnos atendidos exitosamente.
 * - 'porcentajecompra'  : Efectividad de conversión (turnos con venta vs total emitido).
 * - 'esperageneral'     : Tiempo promedio global de espera.
 * - 'atenciongeneral'   : Tiempo promedio global de atención.
 * - 'respuestageneral'  : Tiempo total de respuesta (espera + atención).
 * - 'promedioatendidos' : Promedio diario ponderado de turnos atendidos.
 * 
 * Gráficas y Visualizaciones:
 * - 'graficomes'        : Gráfica de barras de turnos totales vs completados por día (Chart.js).
 * - 'turnostot'         : Gráfico de pastel de turnos completados vs abandonados (Google Charts).
 * - 'turnosetapas'      : Gráfico combinado de distribución y abandonos por área.
 * - 'turnosetapas2'     : Gráfico de dona de trámites procesados por etapa.
 * - 'canceladosxetapa'  : Facturación y cancelaciones por segmento.
 * - 'emisionesporhora'  : Distribución horaria de turnos emitidos por departamento (Chart.js).
 * 
 * Paneles Interactivos y Tablas de Detalle:
 * - 'turnosxvendedor'   : Tarjetas de rendimiento por vendedor (turnos, ventas, efectividad %).
 * - 'tiempopromventas'  : Indicadores circulares de tiempos de espera y atención por área.
 * - 'griddatostotal'    : Modal con listado detallado de turnos, facturas, montos y notas de crédito.
 * - 'gridtiemposxubic'  : Modal con detalle de tiempos por estación.
 * - 'prestamo_*'        : Indicadores y listado de solicitudes de préstamos/créditos.
 * ============================================================================
 */

if (strlen(session_id()) < 1) {
	session_start();
}

require "../config/Conexion.php";

// Paleta de colores corporativos por departamento
$color = array(
    "CAJAS" => "#E12227",
    "ENTREGAS" => "#FEE605",
    "VENTAS" => "#205589"
);
$s = "";
$i = 0;
$g = "";
$icolor = 0;

// Recepción y sanitización de filtros globales (POST / GET)
$wher = isset($_POST["wher"]) ? $_POST["wher"] : "";
if ($wher == '') $wher = isset($_GET["wher"]) ? $_GET["wher"] : "";
if ($wher == '') $wher = '1=1';

$name = isset($_POST["name"]) ? $_POST["name"] : "";
if ($name == '') $name = isset($_GET["name"]) ? $_GET["name"] : "";

$sede = isset($_POST["sede"]) ? $_POST["sede"] : "";
if ($sede == '') $sede = isset($_GET["sede"]) ? $_GET["sede"] : "";

$fini = isset($_POST["fini"]) ? $_POST["fini"] : "";
if ($fini == '') $fini = isset($_GET["fini"]) ? $_GET["fini"] : "";

$ffin = isset($_POST["ffin"]) ? $_POST["ffin"] : "";
if ($ffin == '') $ffin = isset($_GET["ffin"]) ? $_GET["ffin"] : "";

$titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : "";
if ($titulo == '') $titulo = isset($_GET["titulo"]) ? $_GET["titulo"] : "";

// Enrutador de operaciones
switch ($_GET["op"]) {
    
    /**
     * ------------------------------------------------------------------------
     * Caso: totcompras
     * ------------------------------------------------------------------------
     * Calcula la suma total de ventas en colones netas de notas de crédito.
     */
    case 'totcompras':
        $sql = "SELECT ROUND(SUM(IFNULL(if(e.factur!='',e.`monto`,0),0))-SUM(IFNULL(n.`monto`,0)),2) venta FROM `ticket_enc` e 
        LEFT JOIN notas n on n.factura=e.factur and n.fecha BETWEEN '$fini' AND '$ffin 23:59:59'
        WHERE $wher and e.destin = 'V' AND e.factur!='';";

        $fila = ejecutarConsultaSimpleFila($sql);
        echo '₡' . number_format($fila["venta"], 0);
        $_SESSION["tot_compras"] = $fila["venta"];
        break;

    case 'graficomes2':
        break;
    
    /**
     * ------------------------------------------------------------------------
     * Caso: graficomes
     * ------------------------------------------------------------------------
     * Genera la gráfica de barras de turnos diarios (Total vs Completados) con Chart.js.
     */
    case 'graficomes':
        $sql = "SELECT GROUP_CONCAT(dia) dias, GROUP_CONCAT(total) total, GROUP_CONCAT(completo) completo FROM(
            SELECT CONCAT('\"',DATE_FORMAT(e.fechac,'%d/%m'),'\" ') dia, count(*) total, SUM(IF(posact='9',1,0)) completo 
            FROM `ticket_enc` e
            WHERE $wher
            GROUP BY DATE_FORMAT(e.fechac,'%y-%M-%d') ORDER BY e.fechac
            ) AS s;";
        
        $fila = ejecutarConsultaSimpleFila($sql);
        ?>
        <canvas id="agrafica"></canvas>
            
        <script>
        var $grafica = document.querySelector("#agrafica");
        var etiquetas = [<?=$fila["dias"]?>];

        var dato_total = {
            label: "Total",
            data: [<?=$fila["total"]?>],
            backgroundColor: '#204489',
            borderColor: '#204489',
            borderWidth: 1.5,
            tension: 0.2,
        };

        var dato_compl = {
            label: "Completados",
            data: [<?=$fila["completo"]?>],
            backgroundColor: '#E12227',
            borderColor: '#E12227',
            borderWidth: 1.5,  
            tension: 0.2,
        };

        new Chart($grafica, {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [
                    dato_total,     
                    dato_compl,               
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

    /**
     * ------------------------------------------------------------------------
     * Caso: turnostot
     * ------------------------------------------------------------------------
     * Genera un gráfico de pastel con Google Charts (Completados vs Abortados).
     */
    case 'turnostot':
        $sql = "SELECT count(*) total, SUM(IF(posact='9',1,0)) compl FROM `ticket_enc` e 
                WHERE $wher;";
        $fila = ejecutarConsultaSimpleFila($sql);
        $_SESSION["tot_visitas"] = $fila["total"];
        $_SESSION["completos"] = $fila["compl"];
        $_SESSION["abortados"] = $fila["total"] - $fila["compl"];
        ?>            
        <div id="#aturnostot" class="mi_pie"></div>
        <script type="text/javascript">
            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Estado', 'Turnos'],
                    ['Completos', <?=$fila["compl"]?>],
                    ['Abortados', <?=$fila["total"] - $fila["compl"]?>]
                ]);

                var options = {
                    title: 'Turnos por estado',
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

    /**
     * ------------------------------------------------------------------------
     * Caso: totvisitas
     * ------------------------------------------------------------------------
     * Retorna el número total de visitas/turnos emitidos.
     */
    case 'totvisitas':  
        $sql = "SELECT COUNT(*) CANT FROM `ticket_enc` e WHERE " . $wher;
	    $fila = ejecutarConsultaSimpleFila($sql);
        echo $fila["CANT"];
        $_SESSION["tot_visitas"] = $fila["CANT"];
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: completos
     * ------------------------------------------------------------------------
     * Retorna la cantidad de turnos completados almacenada en sesión.
     */
    case 'completos':
        echo $_SESSION["completos"];
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: turnosetapas
     * ------------------------------------------------------------------------
     * Gráfico combinado (ComboChart) de turnos totales y abortados por destino.
     */
    case 'turnosetapas':
        $g = ""; 
        $coma = "";        
        $sql = "SELECT 'VENTAS' destin, COUNT(*) COMPL, (SELECT COUNT(*) cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='V') TOTAL FROM `ticket_enc` e WHERE $wher AND e.`destin`='V' AND posact <= 5 UNION
        SELECT 'CAJAS' destin, COUNT(*) COMPL, (SELECT COUNT(*) cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='C') TOTAL FROM `ticket_enc` e WHERE $wher AND posact IN (13, 6) AND e.`destin`='C' UNION 
        SELECT 'ENTREGAS' destin, COUNT(*) COMPL, (SELECT COUNT(*) cant FROM `ticket_enc` e WHERE $wher AND e.`destin`='E') TOTAL FROM `ticket_enc` e WHERE $wher AND posact IN (8) AND e.`destin`='E';";
        
        $rspta = ejecutarConsulta($sql);
        while ($reg = $rspta->fetch_assoc()) {
            $g .= $coma . '[\'' . $reg["destin"] . '\', ' . $reg["TOTAL"] . ', ' . $reg["COMPL"] . ']';
            $s .= $coma . $i . ': { color: \'' . $color[$reg["destin"]] . '\'}';
            $coma = ',';
            $i++;
            if ($reg["TOTAL"] > 0) { $icolor++; }
        }
        $colorTexto = ($icolor == 1) ? 'black' : 'white';

        echo '<div class="mi_pie" id="' . $name . '"></div>';
        ?>
        <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawVisualization);

            function drawVisualization() {
                var data = google.visualization.arrayToDataTable([
                    ['Etapa', 'Total', 'Abortados'],
                    <?=$g?>
                ]);

                var options = {
                    title: 'Destino Seleccionado en Kiosco',
                    legend: 'top',
                    seriesType: 'bars',
                    colors: ['#205589', '#E12227', '#FEE605'],
                    series: { 1: { type: 'area' } }
                };

                var chart = new google.visualization.ComboChart(document.getElementById('<?=$name?>'));
                chart.draw(data, options);
            }
        </script>
        <?php
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: turnosetapas2
     * ------------------------------------------------------------------------
     * Gráfico de dona (Donut Chart) con trámites procesados por etapa.
     */
    case 'turnosetapas2':
        $coma = "";  
        $sql = "SELECT `fun_destino3`(ubicac) destin, COUNT(*) cant FROM `ticket_det` e 
                WHERE $wher AND e.estado=1 AND ubicac IN(2,6,8) GROUP BY ubicac;";
        $rspta = ejecutarConsulta($sql);
        while ($reg = $rspta->fetch_assoc()) {
            $g .= $coma . '[\'' . $reg["destin"] . '\', ' . $reg["cant"] . ']';
            $s .= $coma . $i . ': { color: \'' . $color[$reg["destin"]] . '\'}';
            $coma = ',';
            $i++;
            if ($reg["cant"] > 0) { $icolor++; }
        }
        $colorTexto = ($icolor == 1) ? 'black' : 'white';
        ?>
        <div id="aturnosetapas2" class="mi_pie"></div>
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
                        color: '<?=$colorTexto?>',
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

    /**
     * ------------------------------------------------------------------------
     * Caso: canceladosxetapa
     * ------------------------------------------------------------------------
     * Gráfico de pastel con facturación por segmento.
     */
    case 'canceladosxetapa':
        $coma = "";
        $sql = "SELECT IF(IFNULL(SEGMEN,'')='','N/A',segmen) segmen, COUNT(*) cant FROM `ticket_enc` e WHERE $wher and segmen!='' GROUP BY IFNULL(SEGMEN,'');";
        $rspta = ejecutarConsulta($sql);
        while ($reg = $rspta->fetch_assoc()) {
            $g .= $coma . '[\'' . $reg["segmen"] . '\', ' . $reg["cant"] . ']';
            $coma = ',';
            $i++;
        }
        $colorTexto = ($i == 1) ? 'black' : 'white';
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
                        color: '<?=$colorTexto?>',
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

    /**
     * ------------------------------------------------------------------------
     * Caso: turnosxvendedor
     * ------------------------------------------------------------------------
     * Renderiza las tarjetas de rendimiento por vendedor (turnos atendidos,
     * turnos con venta facturada, porcentaje de efectividad y monto total).
     */
    case "turnosxvendedor":
        $sql = "SELECT s.agnomb, agente, sum(cant) cant, sum(cantmont) cantmont, sum(monto) monto, fun_nombre_agente_cod(s.agnomb, s.agente) nombre, s.agnomb nombc03 FROM(
            SELECT e.agnomb, e.agente, 1 cant, IFNULL((IF(e.`factur`!='',1,0)),0) cantmont 
            , ROUND((IFNULL(IF(e.factur!='',e.`monto`,0),0))-sum(IFNULL(n.`monto`,0)),2) monto 
            FROM `ticket_enc` e 
            LEFT JOIN notas n ON n.factura=e.factur AND n.fecha BETWEEN '$fini' AND '$ffin 23:59:59' 
            WHERE $wher AND e.destin in ('GC','V') and not(ifnull(agente,'')='' and ifnull(agnomb,'')='')
            GROUP BY e.id) as s group by agnomb ORDER BY monto DESC, cant DESC;";

        $rspta = ejecutarConsulta($sql);
        ?>
        <style type="text/css">
            .mcard-title{
                margin-bottom: .5rem;
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
        <div style="margin-top: 10px; padding: 5px;">
            <h4>Turnos y ventas por vendedor</h4>
            <div class='row'>
                <?php
                while ($reg = $rspta->fetch_assoc()) {
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
                                    <div class="col-4 mcol mborde"><h6 class="card-title"><?=number_format($reg["cantmont"] / $reg["cant"] * 100, 0)?>%</h6></div>
                                </div>
                                
                                <h3 class="card-text" style="margin-top: 10px;">₡<?=number_format($reg["monto"], 0)?></h3>                                
                            </div>
                        </div>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: tiempopromventas
     * ------------------------------------------------------------------------
     * Círculos con promedios de tiempo de espera y atención en Ventas, Cajas y Entregas.
     */
    case "tiempopromventas":
        $sql = "SELECT 'Espera' posic, 'ventas' area, ubicac, `seg_to_hora`(AVG(e.`segund`)) tiempo, AVG(e.`segund`) prom FROM `ticket_det` e FORCE INDEX(fechac_2) WHERE $wher AND e.estado=1 and ubicac = 2 UNION
        SELECT 'Atención' posic, 'ventas' area, ubicac, `seg_to_hora`(AVG(e.`segund`)) tiempo, AVG(e.`segund`) prom FROM `ticket_det` e FORCE INDEX(fechac_2) WHERE $wher AND e.estado=1 and ubicac = 5 UNION
        SELECT 'Espera' posic, 'cajas' area, '6,13' ubicac, `seg_to_hora`(AVG(e.`segund`)) tiempo, AVG(e.`segund`) prom FROM `ticket_det` e FORCE INDEX(fechac_2) WHERE $wher AND e.estado=1 and ubicac in (6,13) UNION
        SELECT 'Atención' posic, 'cajas' area, '7,14' ubicac, `seg_to_hora`(AVG(e.`segund`)) tiempo, AVG(e.`segund`) prom FROM `ticket_det` e FORCE INDEX(fechac_2) WHERE $wher AND e.estado=1 and ubicac in (7,14) UNION
        SELECT 'Espera' posic, 'entregas' area, ubicac, `seg_to_hora`(AVG(e.`segund`)) tiempo, AVG(e.`segund`) prom FROM `ticket_det` e FORCE INDEX(fechac_2) WHERE $wher AND e.estado=1 and ubicac = 8 UNION
        SELECT 'Atención' posic, 'entregas' area, ubicac, `seg_to_hora`(AVG(e.`segund`)) tiempo, AVG(e.`segund`) prom FROM `ticket_det` e FORCE INDEX(fechac_2) WHERE $wher AND e.estado=1 and ubicac = 9;";
        
        $rspta = ejecutarConsulta($sql);
        ?>
        <div class='row'>
        <?php
        $espera = 0;
        $atencion = 0;
        while ($reg = $rspta->fetch_assoc()) {
            if ($reg["posic"] == 'Espera') { 
                $espera += $reg["prom"];
                ?>
                <div class='col-lg-4 col-md-6 col-sm-12 card' style="padding: 5px;">
                    <h4>Tiempo promedio <?=$reg["area"]?></h4>
                    <div class="row">
            <?php } ?>
            
                <div class="col-6">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modaldatos" onclick="gridtiemposxubic('<?=$reg["ubicac"]?>')"> 
                        <div style="background-color: #204489; border-radius: 65px; color: #FEE605; height: 130px; width: 130px; padding-top: 30px; margin-top: 5px;">
                            <h5 style="color: white;"><?=$reg["posic"]?></h5>
                            <h3 style="background-color: #fff; margin-left: 10px; margin-right: 10px; border-radius: 5px; color: #204489;"><?=$reg["tiempo"]?></h3>
                        </div>
                    </a>
                </div>
            
            <?php
            if ($reg["posic"] == 'Atención') { 
                $atencion += $reg["prom"];
                ?>
                </div></div>
            <?php }
        }
        $total_a = $atencion + $espera;
        $_SESSION["espera"] = $espera;
        $_SESSION["atencion"] = $atencion;
        $_SESSION["tot_atencion"] = $total_a;
        ?>
        </div>
        <?php
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: emisionesporhora
     * ------------------------------------------------------------------------
     * Gráfico de barras por hora dividiendo emisiones de Ventas, Cajas y Entregas.
     */
    case "emisionesporhora":
        $sql = "SELECT GROUP_CONCAT(CONCAT('\"',hora,'\"')) hora, GROUP_CONCAT(vent) vent, GROUP_CONCAT(caja) caja, GROUP_CONCAT(entr) entr 
        FROM ( 
            SELECT NombreHora(DATE_FORMAT(fechac,'%H')) hora, SUM(IF(e.`destin`='V',1,0)) vent, SUM(IF(e.`destin`='C',1,0)) caja, SUM(IF(e.`destin`='E',1,0)) entr 
            FROM `ticket_enc` e 
            WHERE $wher GROUP BY DATE_FORMAT(fechac,'%H')
        ) AS s;";
        
        $fila = ejecutarConsultaSimpleFila($sql);
        ?>
        <canvas id="aemisionesporhora"></canvas>
        <script>
        var $grafica = document.querySelector("#aemisionesporhora");
        var etiquetas = [<?=$fila["hora"]?>];
        
        var venta = {
            label: "Ventas",
            data: [<?=$fila["vent"]?>],
            backgroundColor: '<?=$color["VENTAS"]?>',
            borderColor: '<?=$color["VENTAS"]?>',
            borderWidth: 1.5,
        };
        var caja = {
            label: "Cajas",
            data: [<?=$fila["caja"]?>],
            backgroundColor: '<?=$color["CAJAS"]?>',
            borderColor: '<?=$color["CAJAS"]?>',
            borderWidth: 1.5,
        };
        var entrega = {
            label: "Entrega",
            data: [<?=$fila["entr"]?>],
            backgroundColor: '<?=$color["ENTREGAS"]?>',
            borderColor: '<?=$color["ENTREGAS"]?>',
            borderWidth: 1.5,
        };

        new Chart($grafica, {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [venta, caja, entrega]
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

    /**
     * ------------------------------------------------------------------------
     * Casos de Tiempos Generales (KPI Cards)
     * ------------------------------------------------------------------------
     */
    case "esperageneral":
        echo '<h2>' . seg_to_hora($_SESSION["espera"]) . '</h2>';
        break;

    case "atenciongeneral":
        echo '<h2>' . seg_to_hora($_SESSION["atencion"]) . '</h2>';
        break;

    case "respuestageneral":
        echo '<h2>' . seg_to_hora($_SESSION["tot_atencion"]) . '</h2>';
        break;

    case "porcentajecompra":
        echo '<h5>Total ticket con venta / total</h5>';
        echo '<h2>' . number_format((($_SESSION["completos"] / $_SESSION["tot_visitas"]) * 100), 0) . '%</h2>';
        break;
    
    case "ingresoporventas":
        ?>
        <h2><a href="#" data-bs-toggle="modal" onclick="griddatostotal('');" data-bs-target="#modaldatos">₡<?=number_format($_SESSION["tot_compras"], 0)?></a></h2>
        <?php
        break;

    case "promedioatendidos":
        $sql = "SELECT ROUND(SUM(cant)/SUM(valor),0) dato FROM (
            SELECT COUNT(*) cant, CASE DATE_FORMAT(fechac,'%w') WHEN 0 THEN 0.25 WHEN 6 THEN 0.75 ELSE 1 END AS valor, DATE_FORMAT(fechac,'%Y-%m-%d') FROM(
            SELECT e.`id`, e.`fechac` FROM `ticket_enc` e 
            INNER JOIN `ticket_det` d ON d.`n_sede`=e.`n_sede` AND d.`codigt`=e.`id` AND d.`ubicac`='2'
            WHERE $wher GROUP BY id) AS s GROUP BY DATE_FORMAT(fechac,'%y%m%d')) AS d;";
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>' . $fila["dato"] . '</h2>';
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: griddatostotal
     * ------------------------------------------------------------------------
     * Carga en un modal interactivo la tabla con el detalle de todos los turnos,
     * vendedor, tiempos, facturas asociadas y notas de crédito.
     */
    case "griddatostotal":
        $sql = "SELECT e.n_sede, e.ticket, e.`id`, e.`nombre`, e.`agnomb`, e.fechac, seg_to_hora(SUM(segund)) tiempo, e.factur
        , IF(e.factur!='',e.monto,0) monto
        , f_totalnotas(e.factur, '$fini', '$ffin') notas
        FROM `ticket_enc` e
        WHERE $wher and e.destin in ('V','GC') GROUP BY e.`id`;";
        
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
        $iwher = "Total de turnos: " . $iwher;
        ?>
        <h4><?=$titulo?></h4>
        <table id="tbllistado" class="table table-striped table-bordered table-sm" style="padding: 2px;">
            <thead>
                <th>SEDE</th><th>TURNO</th><th>CLIENTE</th><th>VENDEDOR</th><th>FECHA</th><th>TIEMPO</th><th>FACT</th><th>MONTO</th><th>NC</th>
            </thead>
            <tbody>
        <?php
        while ($reg = $rspta->fetch_assoc()) { ?>
            <tr>
            <td><?=$reg["n_sede"]?></td>
            <td><?=$reg["ticket"]?></td>
            <td><?=$reg["nombre"]?></td>
            <td><?=$reg["agnomb"]?></td>
            <td><?=$reg["fechac"]?></td>
            <td><?=$reg["tiempo"]?></td>
            <td><?=$reg["factur"]?></td>
            <td><?=number_format($reg["monto"], 0)?></td> 
            <td><?=number_format($reg["notas"], 0)?></td>        
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <th colspan="9"></th>
        </tfoot>
        </table>

        <script>
            tabla = $('#tbllistado').dataTable({
                "aProcessing": true,
                "aServerSide": true,
                dom: 'Bfrtip',
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
                "bDestroy": true,
                "iDisplayLength": 10,
                "order": [[4, "asc"]]
            }).DataTable();
        </script>
        <?php
        break;

    /**
     * ------------------------------------------------------------------------
     * Caso: gridtiemposxubic
     * ------------------------------------------------------------------------
     * Detalle de tiempos por estación/módulo específico para visualización en modal.
     */
    case "gridtiemposxubic":
        $sql = "SELECT e.n_sede, e.ticket, e.`id`, e.`nombre`, e.`agnomb`, e.fechac, d.segund, seg_to_hora(d.segund) tiempo, d.montof
        FROM `ticket_enc` e 
        INNER JOIN `ticket_det` d ON d.`codigt`=e.`id` and d.estado=1
        WHERE $wher
        ORDER BY e.`fechac`;";
        
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
        $iwher = "Total de turnos: " . $iwher;
        ?>
        <h4><?=$iwher?></h4>
        <table id="tbllistado" class="table table-striped table-bordered table-sm" style="padding: 2px;">
            <thead>
                <th>SEDE</th><th>TURNO</th><th>ID</th><th>CLIENTE</th><th>VENDEDOR</th><th>FECHA</th><th>TIEMPO</th><th>MONTO</th>
            </thead>
            <tbody>
        <?php
        while ($reg = $rspta->fetch_assoc()) { ?>
            <tr>
            <td><?=$reg["n_sede"]?></td>
            <td><?=$reg["ticket"]?></td>
            <td><?=$reg["id"]?></td>
            <td><?=$reg["nombre"]?></td>
            <td><?=$reg["agnomb"]?></td>
            <td><?=$reg["fechac"]?></td>
            <td><?=$reg["tiempo"]?></td>
            <td><?=number_format($reg["montof"], 0)?></td>        
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <th colspan="8"></th>
        </tfoot>
        </table>

        <script>
            tabla = $('#tbllistado').dataTable({
                "aProcessing": true,
                "aServerSide": true,
                dom: 'Bfrtip',
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
                "bDestroy": true,
                "iDisplayLength": 10,
                "order": [[5, "asc"]]
            }).DataTable();
        </script>
        <?php
        break;
    
    /**
     * ------------------------------------------------------------------------
     * Casos: Solicitudes de Préstamos / Créditos
     * ------------------------------------------------------------------------
     */
    case "prestamo_cant":
        $sql = "SELECT count(*) dato 
        FROM `ticket_det` e WHERE $wher AND e.`destin`='REGISTRAR' AND e.`subproc`='prest' ORDER BY e.`consec`;";
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>' . $fila["dato"] . '</h2>';
        break;

    case "prestamo_prom":
        $sql = "SELECT seg_to_hora(IFNULL(AVG(time_prest),0)) dato FROM(
            SELECT  
            (SELECT d2.segund FROM `ticket_det` d2 WHERE d2.codigt=e.`codigt` AND d2.consec>e.`consec` ORDER BY d2.`consec` LIMIT 1) time_prest
            FROM `ticket_det` e WHERE $wher AND e.`destin`='REGISTRAR' AND e.`subproc`='prest' ORDER BY e.`consec`) AS s
            WHERE s.time_prest > 0;";
        $fila = ejecutarConsultaSimpleFila($sql);
        echo '<h2>' . $fila["dato"] . '</h2>';
        break;    

    case "prestamo_consulta":
        $sql = "SELECT e.`ticket` Tiquete, e.fechac Fecha, te.`agnomb` Agente, te.`factur` Factura, te.`nombre` Nombre
        , seg_to_hora(IFNULL((SELECT d2.segund FROM `ticket_det` d2 WHERE d2.codigt=e.`codigt` AND d2.consec>e.`consec` ORDER BY d2.`consec` LIMIT 1),0)) Tiempo
        FROM `ticket_det` e 
        LEFT JOIN `ticket_enc` te ON te.`n_sede`=e.`n_sede` AND te.id=e.`codigt`
        WHERE $wher AND e.`destin`='REGISTRAR' AND e.`subproc`='prest' ORDER BY e.`consec`;";
        
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
                    echo('<th>' . $val->name . '</th>');            
                }
                ?>
            </thead>
            <tbody>
        <?php
        while ($reg = $rspta->fetch_array()) {
            echo '<tr>';
            for ($i = 0; $i < $colums; $i++) { 
                echo '<td>' . $reg[$i] . '</td>';
            }           
            echo '</tr>'; 
        }
        ?>
        </tbody>
        </table>

        <script>
            tabla = $('#tbllistado').dataTable({
                "aProcessing": true,
                "aServerSide": true,
                dom: 'Bfrtip',
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
                "bDestroy": true,
                "iDisplayLength": 10,
                "order": [[1, "asc"]]
            }).DataTable();
        </script>
        <?php
        break;  
}       

/**
 * Convierte un número de segundos en una cadena de tiempo con formato HH:MM:SS
 * 
 * @param float|int $segundos - Cantidad de segundos
 * @return string Tiempo en formato "HH:MM:SS"
 */
function seg_to_hora($segundos) {
    $horas = floor($segundos / 3600);
    $minutos = floor(($segundos % 3600) / 60);
    $segundos = $segundos % 60;
    if ($horas < 10) $horas = '0' . $horas;
    if ($minutos < 10) $minutos = '0' . $minutos;
    if ($segundos < 10) $segundos = '0' . $segundos;
    return $horas . ":" . $minutos . ":" . $segundos;
}
?>