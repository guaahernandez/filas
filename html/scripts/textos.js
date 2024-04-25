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
	$("#texto").val("");
    $("#estado").val(""); 
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
	//tabla.column(1).visible(false);
	// tabla.column(3).visible(false);
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
			messageTop: 'Listado de Textos'
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
			url:'../ajax/a_textos.php?op=listar',
			type: "post",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[4,"desc"]]//ordenar (columna, orden)
	}).DataTable();
	tabla.column(1).visible(false);
	//tabla.column(3).visible(false);
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/a_textos.php?op=guardaryeditar",
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

     limpiar();
}

function mostrar(id){
	
	$.post("../ajax/a_textos.php?op=mostrar",{id : id},
		function(data,status)
		{
			//tabla.column(1).visible(true);
			// tabla.column(3).visible(true);
			data=JSON.parse(data);
			mostrarform(true);    	
			$("#estado").val(data.estado);
            $("#tipo").val(data.tipo);
			$("#nombre").val(data.nombre);
		})
}


//funcion para desactivar
function desactivar(id){
	bootbox.confirm({ message : '¿Esta seguro(a) de desactivar este dato?', closeButton : false, callback : function(result){
		if (result) {
			$.post("../ajax/a_textos.php?op=desactivar", {id : id}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	}});
}

function activar(id){
	bootbox.confirm({message : "¿Esta seguro(a) de activar este dato?", closeButton : false , callback : function(result){
		if (result) {
			$.post("../ajax/a_textos.php?op=activar" , {id : id}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	}});
}

async function getcias(){
    await $.post("../ajax/a_textos.php?op=getcias", function(e){
        //alert(e);
        $("#agenci").html(e);
    });   
}

init();