var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })
}

//funcion limpiar
function limpiar(){
	$("#id").val("");
	$("#sede").val("");
	$("#descrip").val(""); 
    $("#direcci").val(""); 
    $("#grupo").val(""); 
}
 
//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
}

//funcion listar
function listar(){
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"language": {
			"url": "esp.json"
		},
		"ajax":
		{
			url:'../ajax/a_sedes.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/a_sedes.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal(datos, "Sede", "success");
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
}

function mostrar(id){
	$.post("../ajax/a_sedes.php?op=mostrar",{id : id},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#id").val(data.id);
			$("#sede").val(data.sede);
			$("#descrip").val(data.descrip);
            $("#direcci").val(data.direcci);
            $("#grupo").val(data.grupo);
			
		})
}


//funcion para desactivar
function desactivar(id){
	swal({
		title: "Este proceso desactiva la sede seleccionada",
		text: "¿Desea continuar?",
		icon: "warning",
		buttons: ["Cancelar", "Aceptar"],
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			$.post("../ajax/a_sedes.php?op=desactivar", {id : id}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});

}

function activar(id){
	swal({
		title: "Este proceso activa la sede seleccionada",
		text: "¿Desea continuar?",
		icon: "warning",
		buttons: ["Cancelar", "Aceptar"],
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			$.post("../ajax/a_sedes.php?op=activar", {id : id}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}

init();