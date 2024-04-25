var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   getcias();
   listar();    
   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })
}

//funcion limpiar
function limpiar(){
	$("#agenci").val("");
	$("#codigo").val("");
	$("#direcc").val("");
    $("#estado").val(""); 
    $("#videoa").val(""); 
    $("#descri").val("");
	$("#format").val(""); 
}
 
//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnCancelar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#subiendo").hide();
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
	tabla.column(1).visible(false);
	tabla.column(3).visible(false);
}

//funcion listar
function listar(){    
    
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
			messageTop: 'Listado de Videos'
			},
			  'excelHtml5',
			  'pdf',      
			  'pageLength'   
		],
		"language": {
			"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
		},
		"ajax":
		{
			url:'../ajax/a_videos.php?op=listar',
			type: "post",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[6,"desc"]]//ordenar (columna, orden)
	}).DataTable();
	tabla.column(1).visible(false);
	tabla.column(3).visible(false);
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
	 $("#btnCancelar").prop("disabled",true);
	 $("#subiendo").show();
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/a_videos.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			Swal.fire({
				title: "Procesado...",
				position: 'top-end',
				text: datos,
				timer: 2000,
				});
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
}

function mostrar(codigo){
	
	$.post("../ajax/a_videos.php?op=mostrar",{codigo : codigo},
		function(data,status)
		{
			// tabla.column(1).visible(true);
			// tabla.column(3).visible(true);
			data=JSON.parse(data);
			mostrarform(true);
			
            $("#descri").val(data.descri);    
			$("#agenci").val(data.agenci);
			$("#videoa").val(data.direcc);
			$("#estado").val(data.estado);
            $("#codigo").val(data.codigo);
			$("#format").val(data.format);
		})
}


//funcion para desactivar
function desactivar(codigo){
	swal.fire({
		title: "Este proceso desactiva el dato seleccionado",
		text: "¿Desea continuar?",
		icon: "info",
  		showCancelButton: true,
  		focusConfirm: false,
		confirmButtonText: 'Continuar',
		cancelButtonText: `Cancelar`,
	})
	.then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/a_videos.php?op=desactivar", {codigo : codigo}, function(e){
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}

function activar(codigo){
	swal.fire({
		title: "Este proceso activa el dato seleccionado",
		text: "¿Desea continuar?",
		icon: "info",
		showCancelButton: true,
		focusConfirm: false,
	  confirmButtonText: 'Continuar',
	  cancelButtonText: `Cancelar`,
	})
	.then((result) => {
		if (result.isConfirmed) {
			$.post("../ajax/a_videos.php?op=activar", {codigo : codigo}, function(e){
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}

function eliminar(codigo){
	swal.fire({
		title: "Este proceso elimina el video seleccionado",
		text: "¿Desea continuar?",
		icon: "error",
		buttons: ["Cancelar", "Aceptar"],
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			$.post("../ajax/a_videos.php?op=eliminar", {codigo : codigo}, function(e){
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}

function getdirecc(codigo){
    $.post("../ajax/a_videos.php?op=getdirecc", { codigo : codigo }, function(e){
        //alert("a");
        $.ajax({
            url: "videos_preview.php?direcc="+e,
             type: "GET",
             success: function(dato){
                 document.getElementById("vervideo").innerHTML = dato;
             }
        })
    });    
}

async function getcias(){
    await $.post("../ajax/a_videos.php?op=getcias", function(e){
        //alert(e);
        $("#agenci").html(e);
    });   
}

init();