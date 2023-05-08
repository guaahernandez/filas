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
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/a_videos.php?op=guardaryeditar",
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

function mostrar(codigo){
	$.post("../ajax/a_videos.php?op=mostrar",{codigo : codigo},
		function(data,status)
		{
			tabla.column(1).visible(true);
			tabla.column(3).visible(true);
			data=JSON.parse(data);
			mostrarform(true);
			
            $("#descri").val(data.descri);    
			$("#agenci").val(data.agenci);
			$("#videoa").val(data.direcc);
			$("#estado").val(data.estado);
            $("#codigo").val(data.codigo);
		})
}


//funcion para desactivar
function desactivar(codigo){
	bootbox.confirm({ message : '¿Esta seguro(a) de desactivar este dato?', closeButton : false, callback : function(result){
		if (result) {
			$.post("../ajax/a_videos.php?op=desactivar", {codigo : codigo}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	}});
}

function activar(codigo){
	bootbox.confirm({message : "¿Esta seguro(a) de activar este dato?", closeButton : false , callback : function(result){
		if (result) {
			$.post("../ajax/a_videos.php?op=activar" , {codigo : codigo}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	}});
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