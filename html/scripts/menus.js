var tabla;

//funcion que se ejecuta al inicio
function init(){   
  cargamenu('principal');    
  cargavideos();
}

//funcion limpiar
function limpiar(){
	// $("#agenci").val("");
	// $("#codigo").val("");
	// $("#direcc").val("");
    // $("#estado").val(""); 
    // $("#videoa").val(""); 
    // $("#descri").val(""); 
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
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
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
			data=JSON.parse(data);
			mostrarform(true);
            // $("#descri").val(data.descri);    
			// $("#agenci").val(data.agenci);
			// $("#videoa").val(data.direcc);
			// $("#estado").val(data.estado);
            // $("#codigo").val(data.codigo);
		})
}


//funcion para desactivar
function desactivar(codigo){
	result = confirm("¿Esta seguro(a) de desactivar este dato?");
		if (result) {
			$.post("../ajax/a_videos.php?op=desactivar", {codigo : codigo}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	
}

function activar(codigo){
	result = confirm("¿Esta seguro(a) de activar este dato?");
		if (result) {
			$.post("../ajax/a_videos.php?op=activar" , {codigo : codigo}, function(e){
				//bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	
}

function cargamenu(ubmenu){
    $.post("../ajax/a_menus.php?op=cargamenu", { ubmenu : ubmenu }, function(e){
        document.getElementById("botones").innerHTML = e;
    });    
}

function cargavideos(){
    var n = 0;
    var data;
    $.post("../ajax/a_menus.php?op=cargavideos", { codigo : n}, function(e){
        data=JSON.parse(e);
        vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/"+data["direcc"]);
        n = data["codigo"];
		
        vid.addEventListener("ended",()=>{
            // si el video se ha acabado cambia el atributo src
            $.post("../ajax/a_menus.php?op=cargavideos", { codigo : n}, function(e){
                data=JSON.parse(e);
                vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/"+data["direcc"]);
                n = data["codigo"];                
            }); 
        });        
    });
    
    //vid.setAttribute("src", "http://localhost/filas/assets/videos/"+data["direcc"]);
    
    vid.addEventListener("ended",()=>{
    // si el video se ha acabado cambia el atributo src
    //vid.setAttribute("src", vids[n%leng]);
    //n++;
    }
    )
}

async function getcias(){
    await $.post("../ajax/a_videos.php?op=getcias", function(e){
        //alert(e);
        $("#agenci").html(e);
    });   
}

// async function getconsec(agenci, tipoco){
//     await $.post("../ajax/a_menus.php?op=getconsec", { agenci : agenci, tipoco : tipoco }, function(e){
//         alert(e);
//         //$("#agenci").html(e);
//     });   
// }

async function mnopcion(agenci,tipoco,prefer){
    if(tipoco=='P'){
        cargamenu('prefer');
    }else{
        await $.post("../ajax/a_menus.php?op=creaticket", { agenci : agenci, tipoco : tipoco, prefer : prefer }, function(e){
			// alert(e);
			//$("#agenci").html(e);
		});   
    }
}
    

init();