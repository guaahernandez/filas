var tabla;

//funcion que se ejecuta al inicio
function init(){   
    listar_det_pant();    
    cargavideos();
    //text_a_voz('F, 303, pasar a ventas 2');
}

function listar_det_pant(agenci, ubicac){
    $.post("../ajax/a_pantalla.php?op=listar_det_pant", function(e){
        document.getElementById("pizarra").innerHTML = e;
    });   
    var refreshid = setInterval(function(){
        $.post("../ajax/a_pantalla.php?op=listar_det_pant", function(e){
            document.getElementById("pizarra").innerHTML = e;
        }); 
    },3000);
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
            // si el video se ha acabado cambia el atributo src
            $.post("../ajax/a_menus.php?op=cargavideos", { codigo : n}, function(e){
                data=JSON.parse(e);
                vid.setAttribute("src", "http://ws.laguacamaya.cr:13565/filas/assets/videos/"+data["direcc"]);
                n = data["codigo"];                
            }); 
        });        
    });
}

function text_a_voz(text){
    const synth = window.speechSynthesis
    //let text = "V1, pasar a ventas 1"
    const utterThis = new SpeechSynthesisUtterance(text);
    synth.speak(utterThis)
}

init();