var tabla;

//funcion que se ejecuta al inicio
function init(){
	cargaSedes();	
}


function totvisitas(){
	nsede = $("#fsede option:selected").text();	
	isede = $("#fsede").val();
	iarea = $("#farea").val();
	fecini = $("#fecini").val();
	fecfin = $("#fecfin").val();
	titulo = "Turnos atendidos del " + fecini + " al " + fecfin

	if(nsede == "Todas...") {
		nsede = "";
	}else{
		titulo += ", " + nsede
	}
	wher = " 1=1";
	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'"; 
	$.post("../ajax/a_dashboard.php?op=totvisitas",{wher : wher},
		function(data)
		{
			$("#totvisitas").html(data);
			totcompras(wher, fecini, fecfin);
			graficomes(wher);
			graficomes2(wher);
			completo(wher);
			turnostot(wher);
			turnosetapas(wher, "","aturnosetapas");
			turnosetapas2(wher);
			canceladosxetapa(wher);
			turnosxvendedor(wher, fecini, fecfin);
			tiempopromventas(wher);
			emisionesporhora(wher);	
			datosresumidos(wher, fecini, fecfin);
			promedioatendidos(wher);
		})
}

function turnosetapas_(){
	sede = $("#fsede").val();
	iarea = $("#farea").val();
	fecini = $("#fecini").val();
	fecfin = $("#fecfin").val();
	wher = " 1=1";
	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'";
	$.post("../ajax/a_dashboard.php?op=turnosetapas",{wher : wher, sede: nsede, name : "aturnosetapas_m"},
	function(data)
	{
		$("#divdatos").html(data);
	})
}
function totcompras(wher, fini, ffin){
	$.post("../ajax/a_dashboard.php?op=totcompras",{wher : wher, fini : fini, ffin : ffin},
	function(data)
	{
		$("#totcompras").html(data);
	})
}

function completo(wher){
	$.post("../ajax/a_dashboard.php?op=completos",{wher : wher},
	function(data)
	{
		$("#completos").html(data);
	})
}

function graficomes(wher){
	$.post("../ajax/a_dashboard.php?op=graficomes",{wher : wher},
	function(data)
	{
		$("#grafica").html(data);
	})
}

function graficomes2(wher){
	$.post("../ajax/a_dashboard.php?op=graficomes2",{wher : wher},
	function(res)
	{
		//alert(res);
		jsonData = JSON.parse(res);
		//console.log( jsonData );
		//alert(jsonData[0]);
		

		//google.load("current", "1", {packages:["corechart"], callback: drawChart});
		google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
		
        function drawChart() {		 
			var dia = "";
			var tot = 0;
			var com = 0;
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Dia');
			data.addColumn('number', 'Total');
			data.addColumn('number', 'Completo');
			for (let i in jsonData) { 
				dia  = jsonData[i]["dia"];
				tot  = jsonData[i]["total"];
				com  = jsonData[i]["completo"];
				data.addRows([ [dia, parseInt(tot), parseInt(com)]]);
			 }
			
          var options = {
			animation:{ 
				duration: 600, 
				easing: 'out', 
				startup: true
				},
				
          title: 'Visitas por etapa',
          pieHole: 0.5,
          legend: 'top',
          backgroundColor: 'transparent',
          'is3D':true,
		  curveType: 'function',
		  vAxis: { gridlines: { count: 4, scaletype : 'log' } }
          };

          var chart = new google.visualization.LineChart(document.getElementById('lineaschart'));
          chart.draw(data, options);
        }
	})
}

function turnostot(wher){
	$.post("../ajax/a_dashboard.php?op=turnostot",{wher : wher},
	function(data)
	{
		$("#turnostot").html(data);
	})
}

function turnosetapas(wher, nsede, name){
	$.post("../ajax/a_dashboard.php?op=turnosetapas",{wher : wher, sede: nsede, name : name},
	function(data)
	{
		$("#turnosetapas").html(data);
	})
}

function turnosetapas2(id_eve){
	$.post("../ajax/a_dashboard.php?op=turnosetapas2",{wher : wher},
	function(data)
	{
		$("#turnosetapas2").html(data);
	})
  }

function canceladosxetapa(id_eve){
	$.post("../ajax/a_dashboard.php?op=canceladosxetapa",{wher : wher},
	function(data)
	{
		$("#canceladosxetapa").html(data);
	})
  }

function turnosxvendedor(wher, fini, ffin){
	$.post("../ajax/a_dashboard.php?op=turnosxvendedor",{wher : wher, fini : fini, ffin : ffin},
	function(data)
	{
		$("#turnosxvendedor").html(data);
	})
  }

  function tiempopromventas(id_eve){
	$.post("../ajax/a_dashboard.php?op=tiempopromventas",{wher : wher},
	function(data)
	{
		$("#tiempopromventas").html(data);
	})
  }

  function promedioatendidos(){
	$.post("../ajax/a_dashboard.php?op=promedioatendidos",{wher : wher},
	function(data)
	{
		$("#promedioatendidos").html(data);
	})
  }

  function emisionesporhora(wher){
	$.post("../ajax/a_dashboard.php?op=emisionesporhora",{wher : wher},
	function(data)
	{
		$("#emisionesporhora").html(data);
	})
	}

function gridtiemposxubic(pDest){
	wher = " 1=1";	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	//if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'";
	
	if(pDest != ''){
		wher += " and d.ubicac in(" + pDest + ")";
	}

	$.post("../ajax/a_dashboard.php?op=gridtiemposxubic",{wher : wher},
	function(data)
	{
		$("#divdatos").html(data);
	})
	}

function griddatostotal(pNombre, data){
	fini = $("#fecini").val();
	ffin = $("#fecfin").val() + ' 23:59:59';
	tit = titulo;
	wher = " 1=1";	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	//if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'";
	
	if(pNombre != ''){
		wher += " and e.agnomb='" + pNombre + "' and e.destin='V'";
		tit += ", por " + pNombre;
	}
	
	$.post("../ajax/a_dashboard.php?op=griddatostotal",{wher : wher, titulo : tit, fini: fini, ffin: ffin},
	function(data)
	{
		$("#divdatos").html(data);
	})
	}

function prestamo_consulta(){
	wher = " 1=1";	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	//if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'";
	
	$.post("../ajax/a_dashboard.php?op=prestamo_consulta",{wher : wher},
	function(data)
	{
		$("#divdatos").html(data);
	})
	}
	
function datosresumidos(wher, fini, ffin){
	$.post("../ajax/a_dashboard.php?op=esperageneral",{wher : wher},
	function(data)
	{
		$("#esperageneral").html(data);
	})

	$.post("../ajax/a_dashboard.php?op=atenciongeneral",{wher : wher},
	function(data)
	{
		$("#atenciongeneral").html(data);
	})

	$.post("../ajax/a_dashboard.php?op=respuestageneral",{wher : wher},
	function(data)
	{
		$("#respuestageneral").html(data);
	})
	
	$.post("../ajax/a_dashboard.php?op=porcentajecompra",{wher : wher},
	function(data)
	{
		$("#porcentajecompra").html(data);
	})
	
	$.post("../ajax/a_dashboard.php?op=ingresoporventas",{wher : wher, fini: fini, ffin: ffin},
	function(data)
	{
		$("#ingresoporventas").html(data);
	})

	$.post("../ajax/a_dashboard.php?op=prestamo_cant",{wher : wher},
	function(data)
	{
		$("#prestamo_cant").html(data);
	})

	$.post("../ajax/a_dashboard.php?op=prestamo_prom",{wher : wher},
	function(data)
	{
		$("#prestamo_prom").html(data);
	})
}

function cargaSedes(){
	$.post("../ajax/a_sedes.php?op=selectSede",
		function(data)
		{
			$("#fsede").html(data);
			
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

init();