var tabla;

//funcion que se ejecuta al inicio
function init(){
	//cargaSedes();	
}

function tipoInforme(){
    itipo = $("#ftipo").val();   
	wher = "";
	$("#elboton").hide();
	$("#lafecha1").hide();
	$("#lafecha2").hide();
	$("#lblf1").html('Fecha');
	$("#lblf2").html('hasta');
	$("#lasede").hide();
	$("#lasede2").hide();
	cargaSedes();
	//alert(itipo);
	switch (itipo) {
		case "1":
			$.post("inf_traza_x_turno.php",{wher : wher},
			function(data)
			{
				$("#griddatos").html(data);
				
			});	
			$("#lasede").show();
			$("#lasede").attr("onchange","traza_turnos()")
			$("#lafecha1").show();
			$("#lafecha1").attr("onchange","traza_turnos()")
			$("#elboton").show();			
			$("#elboton").attr("onclick","traza_turnos()")
			break;
		case "2":
			$("#elboton").show();
			$("#elboton").attr("onclick","comp_dia_emisi_x_hora_d()")
			$("#lafecha1").show();
			$("#lafecha1").attr("onchange","comp_dia_emisi_x_hora_d()")
			$("#lafecha2").show();
			$("#lafecha2").attr("onchange","comp_dia_emisi_x_hora_d()")
			$("#lasede").show();
			$("#lasede").attr("onchange","comp_dia_emisi_x_hora_d()")
			$("#lblf1").html('desde');
			$("#griddatos").html('');
			$.post("comp_dia_emisi_x_hora.php",{wher : wher},
			function(data)
			{
				$("#griddatos").html(data);
				
			});	
			break;
		case "3":
			$("#elboton").show();			
			$("#elboton").attr("onclick","turnos_por_ubicacion()")
			turnos_por_ubicacion();
			break;
		case "4":
			$("#elboton").show();			
			$("#elboton").attr("onclick","estado_en_linea()")
			estado_en_linea();
			break;
		case "5":
			$("#elboton").show();
			$("#elboton").attr("onclick","estudio_detallado()")
			$("#lafecha1").show();
			$("#lafecha1").attr("onchange","estudio_detallado()")
			// $("#lafecha2").show();
			// $("#lafecha2").attr("onchange","estudio_detallado()")
			$("#lasede").show();
			$("#lasede").attr("onchange","estudio_detallado()")
			// $("#lblf1").html('desde');
			$("#griddatos").html('');
			//estudio_detallado();
			break;
		case "6":
			$("#elboton").show();
			$("#elboton").attr("onclick","metricas_turnos()")
			$("#lafecha1").show();
			$("#lafecha1").attr("onchange","metricas_turnos()")
			$("#lafecha2").show();
			$("#lafecha2").attr("onchange","metricas_turnos()")
			$("#lasede").show();
			$("#lasede").attr("onchange","metricas_turnos()")
			$("#lblf1").html('desde');
			$("#griddatos").html('');
			break;
		case "7":
			$("#elboton").show();
			$("#elboton").attr("onclick","comparar_sedes()")
			$("#lafecha1").show();
			$("#lafecha1").attr("onchange","comparar_sedes()")
			$("#lafecha2").show();
			$("#lafecha2").attr("onchange","comparar_sedes()")
			$("#lasede").show();
			$("#lasede").attr("onchange","comparar_sedes()")
			$("#lasede2").show();
			$("#lasede2").attr("onchange","comparar_sedes()")
			$("#lblf1").html('desde');
			$("#griddatos").html('');
			break;
		default:
			break;
	}
	//alert(itipo);
}


function cargaSedes(){
	$.post("../ajax/a_sedes.php?op=selectSede",
		function(data)
		{
			$("#fsede").html(data);
			$("#fsede2").html(data);
		});
	
}

function MoverFecha(pOp){
	let fecin = moment(document.getElementById('fecini').value);
	let fecfi = moment(document.getElementById('fecfin').value);
	if(pOp=='+') {
		fecin.add(1, 'day');
		fecfi.add(1, 'day');
	}else{
		fecin.add(-1, 'day');
		fecfi.add(-1, 'day');
	}
	document.getElementById('fecini').value = fecin.format('YYYY-MM-DD');
	document.getElementById('fecfin').value = fecfi.format('YYYY-MM-DD');
	totvisitas();
}

function MoverHoy(){
	let fecin = moment().format('YYYY-MM-DD');
	document.getElementById('fecini').value = fecin;
	document.getElementById('fecfin').value = fecin;
	totvisitas();
}

function traza_turnos(){
	//alert('1');
	isede = $("#fsede").val();
	if (isede == 0){
		swal.fire({
			title: "La sede es requerida",
			text: "Favor seleccione una",
			icon: "info",
		});
		//alert('Seleccione una sede');
		document.getElementById('fsede').focus();
        return;
	}
	fecha = $("#ffecha1").val();
	wher = " 1=1";
	
	wher += " and e.n_sede='" + isede + "'";
	wher += " and e.fechac BETWEEN '" + fecha + "' and '" + fecha + " 23:59:59'"; 
	
	$.post("../ajax/a_informes.php?op=traza_turnos",{wher : wher},
		function(data)
	 	{
	 		$("#traza_turnos").html(data);
			$("#griddatos_det").html('');
	 	})
}

function traza_x_turno(){
	//alert('1');
	isede = $("#fsede").val();
	if (isede == 0){
		swal.fire({
			title: "La sede es requerida",
			text: "Favor seleccione una",
			icon: "info",
		});
		//alert('Seleccione una sede');
		document.getElementById('fsede').focus();
        return;
	}
	fecha = $("#ffecha1").val();
	turno = $("#selturnos").val();
	wher = " 1=1";
	
	wher += " and e.n_sede='" + isede + "'";
	wher += " and e.codigt='" + turno + "'";
	wher += " and e.fechac BETWEEN '" + fecha + "' and '" + fecha + " 23:59:59'"; 
	
	$.post("../ajax/a_informes.php?op=traza_x_turno",{wher : wher, turno: turno},
		function(data)
	 	{
	 		$("#griddatos_det").html(data);
	 	})
}

function comp_emisiones_x_hora(){
	//alert('1');
	isede = $("#fsede").val();
	if (isede == 0){
		swal.fire({
			title: "La sede es requerida",
			text: "Favor seleccione una",
			icon: "info",
		});
		document.getElementById('fsede').focus();
        return;
	}
	fecha = $("#ffecha1").val();
	wher = " 1=1";
	
	wher += " and e.n_sede='" + isede + "'";
	wher += " and e.codigt='" + turno + "'";
	wher += " and e.fechac BETWEEN '" + fecha + "' and '" + fecha + " 23:59:59'"; 
	
	$.post("../ajax/a_informes.php?op=comp_emisiones_x_hora",{wher : wher, turno: turno},
		function(data)
	 	{
	 		$("#griddatos_det").html(data);
	 	})
}

function comp_dia_emisi_x_hora_d(){
	//alert('1');
	isede = $("#fsede").val();
	if (isede == 0){
		swal.fire({
			title: "La sede es requerida",
			text: "Favor seleccione una",
			icon: "info",
		});
		document.getElementById('fsede').focus();
        return;
	}
	fecha1 = $("#ffecha1").val();
	fecha2 = $("#ffecha2").val();
	wher = " 1=1";	
	wher += " and e.n_sede='" + isede + "'";
	
	$.post("../ajax/a_informes.php?op=comp_dia_emisi_x_hora_d",{wher : wher, fecha1: fecha1, fecha2: fecha2},
		function(data)
	 	{
	 		$("#griddatos_det").html(data);
	 	})
}

function turnos_por_ubicacion(){
	wher = " 1=1";	
	
	$.post("../ajax/a_informes.php?op=turnos_por_ubicacion",{wher : wher},
		function(data)
	 	{
	 		//$("#griddatos_det").html(data);
			$("#griddatos").html(data);
	 	})
}

function estado_en_linea(){
	wher = " 1=1";	
	
	$.post("../ajax/a_informes.php?op=estado_en_linea",{wher : wher},
		function(data)
	 	{
	 		//$("#griddatos_det").html(data);
			$("#griddatos").html(data);
	 	})
}

function estudio_detallado(){
	sede = $('#fsede').val();
	if (sede == 0){
		swal.fire({
			title: "La sede es requerida",
			text: "Favor seleccione una",
			icon: "info",
		});
		//alert('Seleccione una sede');
		document.getElementById('fsede').focus();
        return;
	}
	fecha = $('#ffecha1').val();
	
	wher = " 1=1";	
	wher += " and e.n_sede = '" + sede + "'";
	wher += " and e.fechac BETWEEN '" + fecha + "' and '" + fecha + " 23:59:59'";	
	$.post("../ajax/a_informes.php?op=estudio_detallado",{wher : wher},
		function(data)
	 	{
	 		//$("#griddatos_det").html(data);
			$("#griddatos").html(data);
	 	})
}

init();

function metricas_turnos() {
	var sede = $('#fsede').val();
	var fecha_inicio = $('#ffecha1').val();
	var fecha_fin = $('#ffecha2').val();
	
	if (!fecha_inicio || !fecha_fin) {
		return;
	}

    var paramSede = (sede == "0") ? "" : sede;

	$.ajax({
		url: '../ws/turnos.php',
		type: 'GET',
		data: {
			fecha_inicio: fecha_inicio,
			fecha_fin: fecha_fin,
			sede: paramSede
		},
		dataType: 'json',
		success: function(response) {
			if (response.status === 'success') {
				render_metricas_turnos(response.data);
			} else {
				swal.fire({
					title: "Atención",
					text: response.message,
					icon: "warning"
				});
				$("#griddatos").html('');
			}
		},
		error: function() {
			swal.fire({
				title: "Error",
				text: "Error de conexión con el Web Service",
				icon: "error"
			});
		}
	});
}

function render_metricas_turnos(data) {
	if (!data || data.length === 0) {
		$("#griddatos").html('<div class="alert alert-info">No se encontraron registros en este rango de fechas.</div>');
		return;
	}

	var html = '<div class="row mb-4" style="margin-top: 15px;">';
	html += '<div class="col-md-6"><div id="chart_div_tiempos" style="width: 100%; height: 350px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px;"></div></div>';
	html += '<div class="col-md-6"><div id="chart_div_efectividad" style="width: 100%; height: 350px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px;"></div></div>';
	html += '<div class="col-md-6"><div id="chart_div_sedes" style="width: 100%; height: 350px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px;"></div></div>';
	html += '<div class="col-md-6"><div id="chart_div_vendedores" style="width: 100%; height: 350px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px;"></div></div>';
	html += '</div>';

	html += '<div class="table-responsive" style="margin-top: 20px;">';
	html += '<table id="tabla_metricas" class="table table-striped table-bordered blueTable">';
	html += '<thead><tr><th>Sede</th><th>Vendedor</th><th>Turnos</th><th>Turnos con Monto</th><th>Monto Total</th><th>Promedio Min.</th></tr></thead>';
	html += '<tbody>';

	var datosSede = {};
    var datosVendedor = {};

	data.forEach(function(item) {
		html += '<tr>';
		html += '<td>' + (item.Sede ? item.Sede : item.n_sede) + '</td>';
		html += '<td>' + (item.nombre ? item.nombre : item.agnomb) + '</td>';
		html += '<td>' + item.TURNOS + '</td>';
		html += '<td>' + item.TURNOS_monto + '</td>';
		html += '<td>₡' + parseFloat(item.Monto).toLocaleString('en-US', {minimumFractionDigits: 2}) + '</td>';
		html += '<td>' + item.prom_minutos + '</td>';
		html += '</tr>';

        // Procesar datos numéricos
        var turnos = parseInt(item.TURNOS) || 0;
        var turnos_monto = parseInt(item.TURNOS_monto) || 0;
        var monto = parseFloat(item.Monto) || 0;
        var prom_min = parseFloat(item.prom_minutos) || 0;

        // Agrupar por sede
        var nombreSede = item.Sede ? item.Sede : item.n_sede;
        if(!datosSede[nombreSede]) {
            datosSede[nombreSede] = { turnos: 0, turnos_monto: 0, monto: 0, sum_minutos: 0 };
        }
        datosSede[nombreSede].turnos += turnos;
        datosSede[nombreSede].turnos_monto += turnos_monto;
        datosSede[nombreSede].monto += monto;
        datosSede[nombreSede].sum_minutos += (prom_min * turnos); // Minutos totales ponderados

        // Agrupar por vendedor
        var nombreVend = item.nombre ? item.nombre : item.agnomb;
        if(!datosVendedor[nombreVend]) {
            datosVendedor[nombreVend] = { turnos: 0, turnos_monto: 0, monto: 0 };
        }
        datosVendedor[nombreVend].turnos += turnos;
        datosVendedor[nombreVend].turnos_monto += turnos_monto;
        datosVendedor[nombreVend].monto += monto;
	});

    // Calcular promedios para Sedes
    for(var s in datosSede) {
        var sede = datosSede[s];
        sede.promedio_minutos = sede.turnos > 0 ? (sede.sum_minutos / sede.turnos) : 0;
        sede.tasa_conversion = sede.turnos > 0 ? ((sede.turnos_monto / sede.turnos) * 100) : 0;
    }

	html += '</tbody></table></div>';
	$("#griddatos").html(html);

	// Inicializar DataTable
	if ($.fn.DataTable.isDataTable('#tabla_metricas')) {
        $('#tabla_metricas').DataTable().destroy();
    }
	$('#tabla_metricas').DataTable({
		language: {
			url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json'
		},
		dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		],
		"order": [[ 4, "desc" ]] // Ordenar por monto por defecto
	});

	// Renderizar gráficos con Google Charts
	google.charts.load('current', {'packages':['corechart', 'bar']});
	google.charts.setOnLoadCallback(function() {
        drawCharts(datosSede, datosVendedor);
    });
}

function drawCharts(datosSede, datosVendedor) {
    var sedesKeys = Object.keys(datosSede);

    // 1. Gráfico de Tiempos Promedio por Sede
    var dataTiemposArray = [['Sede', 'Promedio de Atención (Min.)', { role: 'annotation' }]];
    sedesKeys.forEach(function(s) {
        var prom = Math.round(datosSede[s].promedio_minutos * 10) / 10;
        dataTiemposArray.push([s, prom, prom.toString()]);
    });

    if (dataTiemposArray.length > 1) {
        var dataTiempos = google.visualization.arrayToDataTable(dataTiemposArray);
        var optionsTiempos = {
            title: 'Tiempo Promedio de Atención por Sede (Minutos)',
            hAxis: {title: 'Sede'},
            vAxis: {title: 'Minutos'},
            colors: ['#e74c3c'],
            animation: { startup: true, duration: 1000, easing: 'out' },
            legend: { position: 'none' }
        };
        var chartTiempos = new google.visualization.ColumnChart(document.getElementById('chart_div_tiempos'));
        chartTiempos.draw(dataTiempos, optionsTiempos);
    } else {
        document.getElementById('chart_div_tiempos').innerHTML = '<div style="padding:40px; text-align:center; color:#666;">Seleccione "Todas" en sedes para ver comparativa de tiempos</div>';
    }

    // 2. Gráfico de Efectividad de Cierre (Tasa de Conversión)
    var dataEfectividadArray = [['Sede', 'Efectividad (%)', { role: 'annotation' }]];
    sedesKeys.forEach(function(s) {
        var efect = Math.round(datosSede[s].tasa_conversion * 10) / 10;
        dataEfectividadArray.push([s, efect, efect.toString() + '%']);
    });

    if (dataEfectividadArray.length > 1) {
        var dataEfectividad = google.visualization.arrayToDataTable(dataEfectividadArray);
        var optionsEfectividad = {
            title: 'Efectividad de Cierre por Sede (%)',
            hAxis: {title: 'Sede'},
            vAxis: {title: '% de Turnos Facturados', minValue: 0, maxValue: 100},
            colors: ['#2ecc71'],
            animation: { startup: true, duration: 1000, easing: 'out' },
            legend: { position: 'none' }
        };
        var chartEfectividad = new google.visualization.ColumnChart(document.getElementById('chart_div_efectividad'));
        chartEfectividad.draw(dataEfectividad, optionsEfectividad);
    } else {
        document.getElementById('chart_div_efectividad').innerHTML = '<div style="padding:40px; text-align:center; color:#666;">Seleccione "Todas" en sedes para ver comparativa de efectividad</div>';
    }

    // 3. Gráfico de Sedes (Turnos vs Monto)
    var dataSedeArray = [['Sede', 'Turnos', 'Monto']];
    sedesKeys.forEach(function(s) {
        dataSedeArray.push([s, datosSede[s].turnos, datosSede[s].monto]);
    });
    
    if (dataSedeArray.length > 1) {
        var data1 = google.visualization.arrayToDataTable(dataSedeArray);
        var options1 = {
            title: 'Comparación de Volumen e Ingresos por Sede',
            hAxis: {title: 'Sede'},
            vAxes: {
                0: {title: 'Turnos'},
                1: {title: 'Monto (₡)'}
            },
            seriesType: 'bars',
            series: {1: {type: 'line', targetAxisIndex: 1}},
            colors: ['#204489', '#f39c12'],
            animation: { startup: true, duration: 1000, easing: 'out' },
            legend: { position: 'bottom' }
        };
        var chart1 = new google.visualization.ComboChart(document.getElementById('chart_div_sedes'));
        chart1.draw(data1, options1);
    } else {
        document.getElementById('chart_div_sedes').innerHTML = '<div style="padding:40px; text-align:center; color:#666;">Seleccione "Todas" para comparar volumen e ingresos</div>';
    }

    // 4. Gráfico de Vendedores (Top 10 por Monto)
    var dataVendArray = [['Vendedor', 'Monto Generado (₡)', 'Tasa Conversión (%)']];
    var vendedoresSorted = Object.keys(datosVendedor).sort(function(a,b){ return datosVendedor[b].monto - datosVendedor[a].monto; });
    
    for(var i=0; i<Math.min(10, vendedoresSorted.length); i++) {
        var v = vendedoresSorted[i];
        var efect_v = datosVendedor[v].turnos > 0 ? ((datosVendedor[v].turnos_monto / datosVendedor[v].turnos) * 100) : 0;
        dataVendArray.push([v, datosVendedor[v].monto, Math.round(efect_v)]);
    }

    if (dataVendArray.length > 1) {
        var data2 = google.visualization.arrayToDataTable(dataVendArray);
        var options2 = {
            title: 'Top 10 Vendedores por Monto Generado',
            hAxis: {title: 'Vendedor'},
            vAxes: {
                0: {title: 'Monto (₡)'},
                1: {title: 'Conversión (%)', minValue: 0, maxValue: 100}
            },
            seriesType: 'bars',
            series: {1: {type: 'line', targetAxisIndex: 1}},
            colors: ['#8e44ad', '#16a085'],
            animation: { startup: true, duration: 1000, easing: 'out' },
            legend: { position: 'bottom' }
        };
        var chart2 = new google.visualization.ComboChart(document.getElementById('chart_div_vendedores'));
        chart2.draw(data2, options2);
    } else {
        document.getElementById('chart_div_vendedores').innerHTML = '<div style="padding:40px; text-align:center; color:#666;">No hay suficientes datos para gráfica de vendedores</div>';
    }
}

function comparar_sedes() {
    var sede1 = $('#fsede').val();
    var sede2 = $('#fsede2').val();
    var fecha_inicio = $('#ffecha1').val();
    var fecha_fin = $('#ffecha2').val();
    
    if (!sede1 || !sede2 || sede1 == "0" || sede2 == "0") {
        return; // wait until both are selected
    }
    if (sede1 == sede2) {
        swal.fire({title: "Atención", text: "Debe seleccionar dos sedes diferentes para comparar", icon: "warning"});
        return;
    }
    if (!fecha_inicio || !fecha_fin) {
        return;
    }

    // Call WS getting all sedes
    $.ajax({
        url: '../ws/turnos.php',
        type: 'GET',
        data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, sede: "" },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                render_comparacion_sedes(response.data, sede1, sede2);
            } else {
                swal.fire({ title: "Atención", text: response.message, icon: "warning" });
            }
        },
        error: function() {
            swal.fire({ title: "Error", text: "Error de conexión con el Web Service", icon: "error" });
        }
    });
}

function render_comparacion_sedes(data, sede1_id, sede2_id) {
    if (!data || data.length === 0) {
		$("#griddatos").html('<div class="alert alert-info">No se encontraron registros en este rango de fechas.</div>');
		return;
	}

    var s1_name = $("#fsede option:selected").text().replace(/^[0-9]+\s*-\s*/, ''); 
    var s2_name = $("#fsede2 option:selected").text().replace(/^[0-9]+\s*-\s*/, '');

    var d1 = { turnos: 0, turnos_monto: 0, monto: 0, sum_minutos: 0 };
    var d2 = { turnos: 0, turnos_monto: 0, monto: 0, sum_minutos: 0 };

    data.forEach(function(item) {
        var turnos = parseInt(item.TURNOS) || 0;
        var turnos_monto = parseInt(item.TURNOS_monto) || 0;
        var monto = parseFloat(item.Monto) || 0;
        var prom_min = parseFloat(item.prom_minutos) || 0;
        var sedeCode = (item.n_sede || "").toString();

        if (sedeCode === sede1_id) {
            d1.turnos += turnos;
            d1.turnos_monto += turnos_monto;
            d1.monto += monto;
            d1.sum_minutos += (prom_min * turnos);
        } else if (sedeCode === sede2_id) {
            d2.turnos += turnos;
            d2.turnos_monto += turnos_monto;
            d2.monto += monto;
            d2.sum_minutos += (prom_min * turnos);
        }
    });

    // Calculate averages
    d1.promedio_min = d1.turnos > 0 ? (d1.sum_minutos / d1.turnos) : 0;
    d1.conversion = d1.turnos > 0 ? ((d1.turnos_monto / d1.turnos) * 100) : 0;
    d1.ticket = d1.turnos_monto > 0 ? (d1.monto / d1.turnos_monto) : 0;

    d2.promedio_min = d2.turnos > 0 ? (d2.sum_minutos / d2.turnos) : 0;
    d2.conversion = d2.turnos > 0 ? ((d2.turnos_monto / d2.turnos) * 100) : 0;
    d2.ticket = d2.turnos_monto > 0 ? (d2.monto / d2.turnos_monto) : 0;

    function getHighlightStyle(val1, val2, lowerIsBetter) {
        if (val1 === val2) return 'background-color: #f8f9fa;'; // Tie
        var win = 'background-color: #d4edda; color: #155724; font-weight: bold; font-size: 1.1em;';
        if (lowerIsBetter) {
            return val1 < val2 ? win : '';
        } else {
            return val1 > val2 ? win : '';
        }
    }

    var html = '<div class="row mb-4" style="margin-top: 15px;">';
    
    // Tabla Head-to-Head
    html += '<div class="col-md-12 mb-4">';
    html += '<h4 class="text-center mb-4" style="color: #204489;"><b>Frente a Frente:</b> ' + s1_name + ' <span style="color:#e74c3c">VS</span> ' + s2_name + '</h4>';
    html += '<div class="table-responsive"><table class="table table-bordered text-center blueTable" style="font-size: 16px;">';
    html += '<thead><tr>';
    html += '<th style="width: 33%; background: #204489; font-size: 18px;">' + s1_name + '</th>';
    html += '<th style="width: 33%; background: #444444; font-size: 16px;">MÉTRICA</th>';
    html += '<th style="width: 33%; background: #204489; font-size: 18px;">' + s2_name + '</th>';
    html += '</tr></thead>';
    html += '<tbody>';
    
    // Turnos
    html += '<tr>';
    html += '<td style="' + getHighlightStyle(d1.turnos, d2.turnos, false) + '">' + d1.turnos + '</td>';
    html += '<td style="background:#f9f9f9;"><strong>Turnos Totales</strong></td>';
    html += '<td style="' + getHighlightStyle(d2.turnos, d1.turnos, false) + '">' + d2.turnos + '</td>';
    html += '</tr>';

    // Monto
    html += '<tr>';
    html += '<td style="' + getHighlightStyle(d1.monto, d2.monto, false) + '">₡' + d1.monto.toLocaleString('en-US', {minimumFractionDigits: 2}) + '</td>';
    html += '<td style="background:#f9f9f9;"><strong>Monto Generado (₡)</strong></td>';
    html += '<td style="' + getHighlightStyle(d2.monto, d1.monto, false) + '">₡' + d2.monto.toLocaleString('en-US', {minimumFractionDigits: 2}) + '</td>';
    html += '</tr>';

    // Conversión
    html += '<tr>';
    html += '<td style="' + getHighlightStyle(d1.conversion, d2.conversion, false) + '">' + d1.conversion.toFixed(1) + '%</td>';
    html += '<td style="background:#f9f9f9;"><strong>Efectividad (Conversión %)</strong></td>';
    html += '<td style="' + getHighlightStyle(d2.conversion, d1.conversion, false) + '">' + d2.conversion.toFixed(1) + '%</td>';
    html += '</tr>';

    // Tiempo Promedio
    html += '<tr>';
    html += '<td style="' + getHighlightStyle(d1.promedio_min, d2.promedio_min, true) + '">' + d1.promedio_min.toFixed(1) + ' min</td>';
    html += '<td style="background:#f9f9f9;"><strong>Tiempo Promedio de Atención</strong><br><small class="text-muted">(Menor es mejor)</small></td>';
    html += '<td style="' + getHighlightStyle(d2.promedio_min, d1.promedio_min, true) + '">' + d2.promedio_min.toFixed(1) + ' min</td>';
    html += '</tr>';

    // Ticket Promedio
    html += '<tr>';
    html += '<td style="' + getHighlightStyle(d1.ticket, d2.ticket, false) + '">₡' + d1.ticket.toLocaleString('en-US', {minimumFractionDigits: 2}) + '</td>';
    html += '<td style="background:#f9f9f9;"><strong>Ticket Promedio de Venta</strong></td>';
    html += '<td style="' + getHighlightStyle(d2.ticket, d1.ticket, false) + '">₡' + d2.ticket.toLocaleString('en-US', {minimumFractionDigits: 2}) + '</td>';
    html += '</tr>';

    html += '</tbody></table></div></div>';

    // Contenedores de Gráficos
    html += '<div class="col-md-6"><div id="chart_comp_volumen" style="width: 100%; height: 350px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);"></div></div>';
    html += '<div class="col-md-6"><div id="chart_comp_eficiencia" style="width: 100%; height: 350px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);"></div></div>';
    
    html += '</div>';

    $("#griddatos").html(html);

    // Dibujar gráficos
    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(function() {
        // Gráfico 1: Volumen y Monto
        var dataVol = google.visualization.arrayToDataTable([
            ['Sede', 'Turnos', 'Monto (₡)'],
            [s1_name, d1.turnos, d1.monto],
            [s2_name, d2.turnos, d2.monto]
        ]);
        var optionsVol = {
            title: 'Volumen e Ingresos (Comparativa)',
            vAxes: {0: {title: 'Turnos'}, 1: {title: 'Monto (₡)'}},
            seriesType: 'bars',
            series: {1: {type: 'line', targetAxisIndex: 1}},
            colors: ['#3498db', '#f39c12'],
            animation: { startup: true, duration: 1000, easing: 'out' },
            legend: { position: 'bottom' }
        };
        var chartVol = new google.visualization.ComboChart(document.getElementById('chart_comp_volumen'));
        chartVol.draw(dataVol, optionsVol);

        // Gráfico 2: Eficiencia (Conversión y Tiempo)
        var dataEfi = google.visualization.arrayToDataTable([
            ['Sede', 'Conversión (%)', 'Tiempo Promedio (Min)'],
            [s1_name, d1.conversion, d1.promedio_min],
            [s2_name, d2.conversion, d2.promedio_min]
        ]);
        var optionsEfi = {
            title: 'Velocidad y Efectividad',
            vAxes: {0: {title: 'Conversión (%)'}, 1: {title: 'Minutos'}},
            seriesType: 'bars',
            series: {1: {type: 'line', targetAxisIndex: 1}},
            colors: ['#2ecc71', '#e74c3c'],
            animation: { startup: true, duration: 1000, easing: 'out' },
            legend: { position: 'bottom' }
        };
        var chartEfi = new google.visualization.ComboChart(document.getElementById('chart_comp_eficiencia'));
        chartEfi.draw(dataEfi, optionsEfi);
    });
}