var tabla;

//funcion que se ejecuta al inicio
function init(){   
    opmenu_enc('principal');
    //opmenu_enc('vendedores');  
    cargavideos();
    //document.documentElement.requestFullscreen();
}

function opmenu_enc(ubmenu, prefer){
	//$(".preloader").fadeIn();
    $.post("../ajax/a_menus.php?op=opmenu_enc", { ubmenu : ubmenu, prefer: prefer }, function(e){
        document.getElementById("botones").innerHTML = e;
		if(ubmenu=='retirartic'){
			//var refreshid = setInterval(function(){
				//opmenu_enc('principal');
			//},5000);
		}
    });    
}

function cargavideos(){
    var n = 0;
    var data;
    $.post("../ajax/a_menus.php?op=cargavideos", { codigo : n}, function(e){
        data=JSON.parse(e);
        vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/"+data["direcc"]);
        n = data["codigo"];
        //alert(n);
        vid.addEventListener("ended",()=>{
            // si el video se ha acabado se actualiza el atributo src
            $.post("../ajax/a_menus.php?op=cargavideos", { codigo : n}, function(e){
                data=JSON.parse(e);
                vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/"+data["direcc"]);
                n = data["codigo"];                
            }); 
        });        
    });
}


init();