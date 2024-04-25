var tabla;

//funcion que se ejecuta al inicio
// function init(){   
//   //cargamenu('principal');
//   //opmenu_enc('principal');
//   //getpromo();    
//   //cargavideos();
// }

//funcion limpiar
// function limpiar(){
// 	// $("#agenci").val("");
// 	// $("#codigo").val("");
// 	// $("#direcc").val("");
//     // $("#estado").val(""); 
//     // $("#videoa").val(""); 
//     // $("#descri").val(""); 
// }
 
//funcion mostrar formulario
// function mostrarform(flag){
// 	limpiar();
// 	if(flag){
// 		$("#listadoregistros").hide();
// 		$("#formularioregistros").show();
// 		$("#btnGuardar").prop("disabled",false);
// 		$("#btnagregar").hide();
// 	}else{
// 		$("#listadoregistros").show();
// 		$("#formularioregistros").hide();
// 		$("#btnagregar").show();
// 	}
// }

//cancelar form
// function cancelarform(){
// 	limpiar();
// 	mostrarform(false);
// }

//funcion listar
// function listar(){    
    
// 	tabla=$('#tbllistado').dataTable({
// 		"aProcessing": true,//activamos el procedimiento del datatable
// 		"aServerSide": true,//paginacion y filrado realizados por el server
// 		dom: 'Bfrtip',//definimos los elementos del control de la tabla
// 		buttons: [
//                   'copyHtml5',
//                   'excelHtml5',
//                   'csvHtml5',
//                   'pdf'
// 		],
// 		"ajax":
// 		{
// 			url:'../ajax/a_videos.php?op=listar',
// 			type: "post",
// 			dataType : "json",
// 			error:function(e){
// 				console.log(e.responseText);
// 			}
// 		},
// 		"bDestroy":true,
// 		"iDisplayLength":10,//paginacion
// 		"order":[[0,"desc"]]//ordenar (columna, orden)
// 	}).DataTable();
// }
//funcion para guardaryeditar
// function guardaryeditar(e){
//      e.preventDefault();//no se activara la accion predeterminada 
//      $("#btnGuardar").prop("disabled",true);
//      var formData=new FormData($("#formulario")[0]);

//      $.ajax({
//      	url: "../ajax/a_videos.php?op=guardaryeditar",
//      	type: "POST",
//      	data: formData,
//      	contentType: false,
//      	processData: false,

//      	success: function(datos){
//      		//bootbox.alert(datos);
//      		mostrarform(false);
//      		tabla.ajax.reload();
//      	}
//      });

//      limpiar();
// }

// function mostrar(codigo){
// 	$.post("../ajax/a_videos.php?op=mostrar",{codigo : codigo},
// 		function(data,status)
// 		{
// 			data=JSON.parse(data);
// 			mostrarform(true);
//             // $("#descri").val(data.descri);    
// 			// $("#agenci").val(data.agenci);
// 			// $("#videoa").val(data.direcc);
// 			// $("#estado").val(data.estado);
//             // $("#codigo").val(data.codigo);
// 		})
// }


//funcion para desactivar
// function desactivar(codigo){
// 	result = confirm("¿Esta seguro(a) de desactivar este dato?");
// 		if (result) {
// 			$.post("../ajax/a_videos.php?op=desactivar", {codigo : codigo}, function(e){
// 				//bootbox.alert(e);
// 				tabla.ajax.reload();
// 			});
// 		}
	
// }

// function activar(codigo){
// 	result = confirm("¿Esta seguro(a) de activar este dato?");
// 		if (result) {
// 			$.post("../ajax/a_videos.php?op=activar" , {codigo : codigo}, function(e){
// 				//bootbox.alert(e);
// 				tabla.ajax.reload();
// 			});
// 		}
	
// }

function cargamenu(ubmenu){
	//$(".preloader").fadeIn();
    $.post("../ajax/a_menus.php?op=cargamenu", { ubmenu : ubmenu }, function(e){
        document.getElementById("botones").innerHTML = e;
	//	$(".preloader").fadeOut();
    });    
}

function cargamenu_v(agenci){
	//$(".preloader").fadeIn();
	document.getElementById("botones").innerHTML = '';
    $.post("../ajax/a_menus.php?op=cargamenu_v", { agenci : agenci }, function(e){
        document.getElementById("botones").innerHTML = e;
	//	$(".preloader").fadeOut();
    });    	
}

function opmenu_enc(ubmenu){
	//$(".preloader").fadeIn();
    $.post("../ajax/a_menus.php?op=opmenu_enc", { ubmenu : ubmenu }, function(e){
        document.getElementById("botones").innerHTML = e;
		if(ubmenu=='retirartic'){
			//var refreshid = setInterval(function(){
				//opmenu_enc('principal');
			//},5000);
		}
    });    
}

// function cargavideos(){
//     var n = 0;
//     var data;
//     $.post("../ajax/a_menus.php?op=cargavideos", { codigo : n}, function(e){
//         data=JSON.parse(e);
//         //vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/"+data["direcc"]);
//         n = data["codigo"];
		
//         // vid.addEventListener("ended",()=>{
//         //     // si el video se ha acabado cambia el atributo src
//         //     $.post("../ajax/a_menus.php?op=cargavideos", { codigo : n}, function(e){
//         //         data=JSON.parse(e);
//         //         //vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/"+data["direcc"]);
//         //         n = data["codigo"];                
//         //     }); 
//         // });        
//     });
    
//     //vid.setAttribute("src", "http://localhost/filas/assets/videos/"+data["direcc"]);
    
//     // vid.addEventListener("ended",()=>{
//     // // si el video se ha acabado cambia el atributo src
//     // //vid.setAttribute("src", vids[n%leng]);
//     // //n++;
//     // }
//     // )
// }

async function getcias(){
    await $.post("../ajax/a_videos.php?op=getcias", function(e){
        //alert(e);
        $("#agenci").html(e);
    });   
}

// function getpromo(){
//     $.post("../ajax/a_menus.php?op=getpromo", function(e){
//         //alert(e);
//         //promo.setAttribute("src", e);
//     });   
// }

async function creaticket(agenci,tipoco,prefer,vended){
    if(tipoco=='P'){
        cargamenu('prefer');
    }else{
        await $.post("../ajax/a_menus.php?op=creaticket", { agenci : agenci, tipoco : tipoco, prefer : prefer, vended : vended }, function(e){
			opmenu_enc('retirartic');
			// var refreshid = setInterval(function(){
			// 	opmenu_enc('principal');
			// },1000);
		});   
    }
}
    

//init();