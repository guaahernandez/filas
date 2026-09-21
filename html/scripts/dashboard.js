var tabla;

//funcion que se ejecuta al inicio
function init(){
	cargaSedes();	
}

function totvisitas(){

	$("#totvisitas").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#totcompras").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#grafica").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#grafica2").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#turnostot").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#completos").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#turnosetapas").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#turnosetapas2").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#canceladosxetapa").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#turnosxvendedor").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#tiempopromventas").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#emisionesporhora").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#datosresumidos").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#promedioatendidos").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#esperageneral").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#atenciongeneral").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#respuestageneral").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#porcentajecompra").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#ingresoporventas").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#prestamo_cant").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#prestamo_prom").html('<i style="color: #053793;font-size: 30px;" class="fas fa-spinner fa-spin"></i>')
	$("#espere").html('<h2 style="color: #E12227;font-size: 30px;"><i class="fas fa-spinner fa-spin"></i></h2>')

	nsede = $("#fsede option:selected").text();	
	isede = $("#fsede").val();
	iarea = $("#farea").val();
	fecini = $("#fecini").val();
	fecfin = $("#fecfin").val();
	titulo = "Turnos atendidos del " + fecini + " al " + fecfin;

	if(nsede == "Todas...") {
		nsede = "";
	}else{
		titulo += ", " + nsede;
	}
	wher = " 1=1";
	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'"; 
	$.post("../ajax/a_dashboard.php?op=totvisitas",{wher : wher},
		async function(data)
		{
			console.time("Total Carga Dashboard");
			$("#totvisitas").html(data);

			console.time("1. totcompras");
			await totcompras(wher, fecini, fecfin);
			console.timeEnd("1. totcompras");

			console.time("2. graficomes");
			await graficomes(wher);
			console.timeEnd("2. graficomes");

			console.time("3. graficomes2");
			await graficomes2(wher);
			console.timeEnd("3. graficomes2");

			console.time("4. turnostot");
			await turnostot(wher);
			console.timeEnd("4. turnostot");

			console.time("5. completo");
			await completo(wher);
			console.timeEnd("5. completo");

			console.time("6. turnosetapas");
			await turnosetapas(wher, "","aturnosetapas");
			console.timeEnd("6. turnosetapas");

			console.time("7. turnosetapas2");
			await turnosetapas2(wher);
			console.timeEnd("7. turnosetapas2");

			console.time("8. canceladosxetapa");
			await canceladosxetapa(wher);
			console.timeEnd("8. canceladosxetapa");

			console.time("9. turnosxvendedor");
			turnosxvendedor(wher, fecini, fecfin);
			console.timeEnd("9. turnosxvendedor");

			console.time("10. tiempopromventas");
			tiempopromventas(wher);
			console.timeEnd("10. tiempopromventas");

			console.time("11. emisionesporhora");
			emisionesporhora(wher);
			console.timeEnd("11. emisionesporhora");

			console.time("12. datosresumidos");
			datosresumidos(wher, fecini, fecfin);
			console.timeEnd("12. datosresumidos");

			console.time("13. promedioatendidos");
			promedioatendidos(wher);
			console.timeEnd("13. promedioatendidos");
			$("#espere").html('')
			console.timeEnd("Total Carga Dashboard");
		});
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
	return $.post("../ajax/a_dashboard.php?op=turnosetapas",{wher : wher, sede: nsede, name : "aturnosetapas_m"},
	function(data)
	{
		$("#divdatos").html(data);
	});
}

function totcompras(wher, fini, ffin){
	return $.post("../ajax/a_dashboard.php?op=totcompras",{wher : wher, fini : fini, ffin : ffin},
	function(data)
	{
		$("#totcompras").html(data);
	});
}

function completo(wher){
	return $.post("../ajax/a_dashboard.php?op=completos",{wher : wher},
	function(data)
	{
		$("#completos").html(data);
	});
}

function graficomes(wher){
	return $.post("../ajax/a_dashboard.php?op=graficomes",{wher : wher},
	function(data)
	{
		$("#grafica").html(data);
	});
}

function graficomes2(wher){
	var container = document.getElementById('lineaschart');
	if (!container) return Promise.resolve();

	return $.post("../ajax/a_dashboard.php?op=graficomes2",{wher : wher},
	function(res)
	{
		try {
			jsonData = JSON.parse(res);
		} catch(e) {
			console.error("Error al parsear JSON en graficomes2:", e);
			return;
		}

		google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
		
        function drawChart() {		 
			var el = document.getElementById('lineaschart');
			if (!el) return;

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
			legend: 'top',
			backgroundColor: 'transparent',
			curveType: 'function'
          };

          var chart = new google.visualization.LineChart(el);
          chart.draw(data, options);
        }
	});
}

function turnostot(wher){
	return $.post("../ajax/a_dashboard.php?op=turnostot",{wher : wher},
	function(data)
	{
		$("#turnostot").html(data);
	});
}

function turnosetapas(wher, nsede, name){
	return $.post("../ajax/a_dashboard.php?op=turnosetapas",{wher : wher, sede: nsede, name : name},
	function(data)
	{
		$("#turnosetapas").html(data);
	});
}

function turnosetapas2(id_eve){
	return $.post("../ajax/a_dashboard.php?op=turnosetapas2",{wher : wher},
	function(data)
	{
		$("#turnosetapas2").html(data);
	});
}

function canceladosxetapa(id_eve){
	return $.post("../ajax/a_dashboard.php?op=canceladosxetapa",{wher : wher},
	function(data)
	{
		$("#canceladosxetapa").html(data);
	});
}

function turnosxvendedor(wher, fini, ffin){
	return $.post("../ajax/a_dashboard.php?op=turnosxvendedor",{wher : wher, fini : fini, ffin : ffin},
	function(data)
	{
		$("#turnosxvendedor").html(data);
	});
}

function tiempopromventas(id_eve){
	return $.post("../ajax/a_dashboard.php?op=tiempopromventas",{wher : wher},
	function(data)
	{
		$("#tiempopromventas").html(data);
	});
}

function promedioatendidos(){
	return $.post("../ajax/a_dashboard.php?op=promedioatendidos",{wher : wher},
	function(data)
	{
		$("#promedioatendidos").html(data);
	});
}

function emisionesporhora(wher){
	return $.post("../ajax/a_dashboard.php?op=emisionesporhora",{wher : wher},
	function(data)
	{
		$("#emisionesporhora").html(data);
	});
}

function gridtiemposxubic(pDest){
	wher = " 1=1";	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	//if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'";
	
	if(pDest != ''){
		wher += " and d.ubicac in(" + pDest + ")";
	}

	return $.post("../ajax/a_dashboard.php?op=gridtiemposxubic",{wher : wher},
	function(data)
	{
		$("#divdatos").html(data);
	});
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
		wher += " and e.agnomb='" + pNombre + "' and e.destin in ('V','GC')";
		tit += ", por " + pNombre;
	}
	
	return $.post("../ajax/a_dashboard.php?op=griddatostotal",{wher : wher, titulo : tit, fini: fini, ffin: ffin},
	function(data)
	{
		$("#divdatos").html(data);
	});
}

function prestamo_consulta(){
	wher = " 1=1";	
	if(isede != '0') wher += " and e.n_sede='" + isede + "'";
	//if(iarea != '0') wher += " and e.destin='" + iarea + "'";
	wher += " and e.fechac BETWEEN '" + fecini + "' and '" + fecfin + " 23:59:59'";
	
	return $.post("../ajax/a_dashboard.php?op=prestamo_consulta",{wher : wher},
	function(data)
	{
		$("#divdatos").html(data);
	});
}
	
async function datosresumidos(wher, fini, ffin){
	
	await $.post("../ajax/a_dashboard.php?op=esperageneral",{wher : wher},
	function(data)
	{
		$("#esperageneral").html(data);
	});

	await $.post("../ajax/a_dashboard.php?op=atenciongeneral",{wher : wher},
	function(data)
	{
		$("#atenciongeneral").html(data);
	});

	await $.post("../ajax/a_dashboard.php?op=respuestageneral",{wher : wher},
	function(data)
	{
		$("#respuestageneral").html(data);
	});
	
	await $.post("../ajax/a_dashboard.php?op=porcentajecompra",{wher : wher},
	function(data)
	{
		$("#porcentajecompra").html(data);
	});
	
	await $.post("../ajax/a_dashboard.php?op=ingresoporventas",{wher : wher, fini: fini, ffin: ffin},
	function(data)
	{
		$("#ingresoporventas").html(data);
	});

	await $.post("../ajax/a_dashboard.php?op=prestamo_cant",{wher : wher},
	function(data)
	{
		$("#prestamo_cant").html(data);
	});

	await $.post("../ajax/a_dashboard.php?op=prestamo_prom",{wher : wher},
	function(data)
	{
		$("#prestamo_prom").html(data);
	});
}

function cargaSedes(){
	return $.post("../ajax/a_sedes.php?op=selectSede",
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