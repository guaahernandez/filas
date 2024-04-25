var tabla;
var tablaVend;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   listar();

$("#formulario").on("submit",function(e){
	guardaryeditar(e);
})

   //cargamos los items al select departamento
   $.post("../ajax/a_vendedor.php?op=selectagencia", function(r){
   	$("#direc").html(r);
   	$('#direc').selectpicker('refresh'); 
   });

}

//funcion limpiar
function limpiar(){
	$("#agent").val("");
    $("#nombl").val("");
	$("#nombc").val("");
	$("#direc").selectpicker('refresh');
	$("#apart").val("");
	$("#local").val("");
	$("#activ").val("");
	$("#imagenactual").val("");
	$("#imagen").val("");
    $("#gc").val("");
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
	limpiar();
	mostrarform(false);
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
                messageTop: 'Listado de Vendedores'
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
			url:'../ajax/a_vendedor.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[1,"asc"]]//ordenar (columna, orden)
	}).DataTable();
}

function listarVend(){
	tablaVend=$('#tbllistadoVend').dataTable({
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
                messageTop: 'Listado de Vendedores'
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
			url:'../ajax/a_vendedor.php?op=listarVend',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[1,"asc"]]//ordenar (columna, orden)
	}).DataTable();
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/a_vendedor.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal({
				title: "Proceso de guardado",
				text: datos,
				timer: 3000,
				});
     		//bootbox.alert(datos);
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });
     limpiar();
}


function mostrarVend(nombc){
	$.post("../ajax/a_vendedor.php?op=mostrarVend",{nombc : nombc},
    function(data,status)
    {
        data=JSON.parse(data);
        mostrarform(true);
        
        $("#agent").val(data.agent03);
        $("#direc").val(data.direc03.replace(' ','-'));
        $("#direc").selectpicker('refresh');
        $("#nombl").val(data.nombl03);
        $("#nombc").val(data.nombc03);
        $("#apart").val(data.apart03);
        $("#local").val(0);
        $("#activo").val(1);
        $("#imagenactual").val('');
        $("#gc").val(0);

		$("#nombc").prop("readonly",true);
		
		$("#agent").prop("readonly",true);
		$("#direc").prop("readonly",true);			
		
    });	
}

function mostrar(nombc){
	$.post("../ajax/a_vendedor.php?op=mostrar",{nombc : nombc},
    function(data,status)
    {
        data=JSON.parse(data);
        mostrarform(true);
        
        $("#agent").val(data.agent03);
        $("#direc").val(data.direc03.replace(' ','-'));
        $("#direc").selectpicker('refresh');
        $("#nombl").val(data.nombl03);
        $("#nombc").val(data.nombc03);
        $("#apart").val(data.apart03);
        $("#local").val(data.local);
        $("#activo").val(data.activo);
        $("#imagenactual").val(data.imagen);
        $("#gc").val(data.gc);

		$("#nombc").prop("readonly",true);
		if(data.local==0){
			$("#agent").prop("readonly",true);
			$("#direc").prop("readonly",true);			
		}else{
			$("#agent").prop("readonly",false);
			$("#direc").prop("readonly",false);	
		}
    });	
}

//funcion para desactivar
function desactivar(nombc03){
	swal({
		title: "Este proceso desactiva el vendedor seleccionado",
		text: "¿Desea continuar?",
		icon: "warning",
		buttons: ["Cancelar", "Aceptar"],
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			$.post("../ajax/a_vendedor.php?op=desactivar", {nombc : nombc03}, function(e){
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}

function activar(nombc03){
	swal({
		title: "Este proceso activa el vendedor seleccionado",
		text: "¿Desea continuar?",
		icon: "warning",
		buttons: ["Cancelar", "Aceptar"],
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			$.post("../ajax/a_vendedor.php?op=activar", {nombc : nombc03}, function(e){
				tabla.ajax.reload();
			});
		} else {
			//swal("Proceso terminado", "Usuario", "success");
		}
	});
}


// function Imprimir(){
// 	var mywindow = window.open('', 'PRINT', 'height=400,width=600');
  
// 	mywindow.document.write('<html><head><title>' + document.title  + '</title>');
// 	mywindow.document.write('</head><body >');
// 	mywindow.document.write('<h3>' + 'Impresion de documento' + '</h3>');
// 	//mywindow.document.write(document.getElementById('divContenido').innerHTML);
// 	mywindow.document.write('</body></html>');
  
// 	mywindow.document.close(); // necessary for IE >= 10
// 	mywindow.focus(); // necessary for IE >= 10*/
  
// 	mywindow.print();
// 	mywindow.close();
// }

init();