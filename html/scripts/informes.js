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