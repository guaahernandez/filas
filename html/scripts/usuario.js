var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   mostrarform_clave(false);
   listar();
   cargaSedes();
	$("#formularioc").on("submit",function(c){
	editar_clave(c);
})

$("#formulario").on("submit",function(e){
	guardaryeditar(e);
})

   $("#imagenmuestra").hide();
//mostramos los permisos
$.post("../ajax/a_usuario.php?op=permisos&id=", function(r){
	$("#permisos").html(r);
});

   //cargamos los items al select departamento
   $.post("../ajax/a_departamento.php?op=selectDepartamento", function(r){
   	$("#iddepartamento").html(r);
   	$('#iddepartamento').selectpicker('refresh'); 
   });

   //cargamos los items al select tipousuario
   $.post("../ajax/a_tipousuario.php?op=selectTipousuario", function(r){
   	$("#idtipousuario").html(r);
   	$('#idtipousuario').selectpicker('refresh'); 
   });

}

//funcion limpiar
function limpiar(){
	$("#nombre").val("");
    $("#apellidos").val("");
	$("#direccion").val("");
	// $("#iddepartamento").selectpicker('refresh');
	// $("#idtipousuario").selectpicker('refresh');
	$("#iddepartamento").selectpicker('refresh');
	$("#idtipousuario").selectpicker('refresh');
	$("#email").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#codigo_persona").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#idusuario").val("");
	$("#fsede").val("");
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
function mostrarform_clave(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formulario_clave").show();
		$("#btnGuardar_clave").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formulario_clave").hide();
		$("#btnagregar").show();
	}
}
//cancelar form
function cancelarform(){
	$("#claves").show();
	limpiar();
	mostrarform(false);
}
function cancelarform_clave(){
	limpiar();
	mostrarform_clave(false);

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
                messageTop: 'Listado de Eventos'
                },
                  'excelHtml5',
                  'pdf',      
                  'pageLength'   
		],
        "language": {
                "url": "esp.json"
            },
		"ajax":
		{
			url:'../ajax/a_usuario.php?op=listar',
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
     	url: "../ajax/a_usuario.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		//bootbox.alert(datos);
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });
	//$("#claves").show();
     limpiar();
}

function editar_clave(c){
     c.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar_clave").prop("disabled",true);
     var formData=new FormData($("#formularioc")[0]);

     $.ajax({
     	url: "../ajax/a_usuario.php?op=editar_clave",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform_clave(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
	 $("#getCodeModal").modal('hide');
}
function mostrar(idusuario){
	$.post("../ajax/a_usuario.php?op=mostrar",{idusuario : idusuario},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);
			if ($("#idusuario").val(data.idusuario).length==0) {
           	$("#claves").show();
           	
           }else{
			$("#claves").hide();
			}
			$("#nombre").val(data.nombre);
            $("#iddepartamento").val(data.iddepartamento);
            $("#iddepartamento").selectpicker('refresh');
            $("#idtipousuario").val(data.idtipousuario);
            $("#idtipousuario").selectpicker('refresh');
            $("#apellidos").val(data.apellidos);
            $("#email").val(data.email);
            $("#login").val(data.login);
            $("#codigo_persona").val(data.codigo_persona);
            //$("#imagenmuestra").show();
            //$("#imagenmuestra").attr("src","../files/usuarios/"+data.imagen);
            $("#imagenactual").val(data.imagen);
            $("#idusuario").val(data.idusuario);
			$("#fsede").val(data.n_sede);
			$("#fsede").selectpicker('refresh');
 
		});
	// $.post("../ajax/a_usuario.php?op=permisos&id="+idusuario, function(r){
	// $("#permisos").html(r);
	// });
}

function mostrar_clave(idusuario){
	 $("#getCodeModal").modal('show');
	$.post("../ajax/a_usuario.php?op=mostrar_clave",{idusuario : idusuario},
		function(data,status)
		{
			data=JSON.parse(data);
            $("#idusuarioc").val(data.idusuario);
		});
}

//funcion para desactivar
function desactivar(idusuario){
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
			$.post("../ajax/a_usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}

function activar(idusuario){
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
			$.post("../ajax/a_usuario.php?op=activar", {idusuario : idusuario}, function(e){
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}

function generar(longitud)
{
  long=parseInt(longitud);
  var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ2346789";
  var contraseña = "";
  for (i=0; i<long; i++) contraseña += caracteres.charAt(Math.floor(Math.random()*caracteres.length));
    $("#codigo_persona").val(contraseña);
}

function cargaSedes(){
	$.post("../ajax/a_sedes.php?op=selectSede",
		function(data)
		{
			$("#fsede").html(data);			
		});
}

init();