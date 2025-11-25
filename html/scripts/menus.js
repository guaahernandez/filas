var tabla;
var _numfac;

async function onKeyUp(event, prefer, android, agenci){ {
    var keycode = event.keyCode;
    var link;
    var codigo;
    if(keycode == '13'){
        _numfac = $('#numfac').val();
        //alert(_numfac + ' ' + android);
        opmenu_enc("pasaentreg", _numfac);
        
        if(android=="1"){
            
            // //crea el tiquete y obtiene el codigo
            await $.post("../ajax/a_menus.php?op=creaticket", { agenci: agenci, tipoco : "E", prefer : 0, vended : "", factur : _numfac }, function(e){
                dato = e.split("|");
                //alert(e);
            });     
            //crea el detalle
            // await $.post("../ajax/a_menus.php?op=creaticket_det", { agenci : agenci, segund : 0, ubicac : "E", destin : "ENTREGAS", codigt: codigo, prefac:"", factur:_numfac, montof:0, estacion:0 }, function(e){
            //     //alert(e);
            // });  
            
        //   data="&sucursal="+agenci+"&destino=E&estado=3&tiquete="+dato[1]+"&factura="+_numfac+"&monto=0&tipodoc=V&codtrans="+dato[0]

        //   fetch('http://localhost/wsecom/index.php?key=test2021&metodo=filas_entreg_add'+data)
        //   .then(res => res.json()) // se obtiene respuesta y se parsea a JSON.
        //   .then(res => console.log('Respuesta: ', res))
        //   .catch(err => console.error('Error:', err));
            codigt = dato[0];
            turno = dato[1];
            await $.post("../ajax/a_menus.php?op=guarda_fact_ticket", { codigt: codigt, agenci: agenci, turno: turno, factur: _numfac }, function(e){
                //alert(e);
            });    
            
        }else{
            setTimeout(function(){ 
                link = '-';
                link += (prefer=='1' ? 'PF=' : 'F=') + _numfac;
                window.location.href = link;
            },100);    
        }
            
    }
  }
}

function pasar(){
    alert("Pasar");
    $('#pasar')[0].click();
}

function cargamenu(ubmenu){
	//$(".preloader").fadeIn();
    //alert("Cargando menu: " + ubmenu);
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

function opmenu_enc(ubmenu, numfac = ""){
	//$(".preloader").fadeIn();
    //alert("Cargando menu: " + ubmenu + " con numfac: " + numfac);
    $.post("../ajax/a_menus.php?op=opmenu_enc", { ubmenu : ubmenu, numfac : numfac }, function(e){
        document.getElementById("botones").innerHTML = e;
		if(ubmenu=='retirartic'){            
            setTimeout(function(){ 
                opmenu_enc('principal');
            },4000);
		}
        if(ubmenu=='pasaentreg'){            
            setTimeout(function(){ 
                opmenu_enc('principal');
            },4000);
		}
        if(ubmenu=='codigobr'){            
            document.getElementById("numfac").focus();
        }
    });    
}

async function getcias(){
    await $.post("../ajax/a_videos.php?op=getcias", function(e){
        //alert(e);
        $("#agenci").html(e);
    });   
}


async function creaticket(agenci,tipoco,prefer,vended, numfac = "", pAndroid = false){
    if(tipoco=='P'){
        cargamenu('prefer');
    }else{
        if(pAndroid){
            console.log("prefer: " + prefer);
            console.log("tipoco: " + tipoco);
            if(prefer=='1' && tipoco=='V'){
                tipoco= 'P' + tipoco;
            }
            console.log("tipoco: " + tipoco);
            // //crea el tiquete y obtiene el codigo
            await $.post("../ajax/a_menus.php?op=creaticket", { agenci : agenci, tipoco : tipoco, prefer : prefer, vended : vended, numfac : numfac }, function(e){
                opmenu_enc('retirartic');
                
                $.post("../ajax/a_menus.php?op=obtener_imprimir", { codigo : e }, function(dat){
                    data=JSON.parse(dat);
                     //alert(dat);
                    var text = "";//<center><IMAGE>http://ws.laguacamaya.cr:13565/filas/assets/images/Guaca2.png<br><br>";
                    text += "Repuestos La Guaca";
                    text += "|BIENVENIDO(A)|"+data["agencia"]+"|Su turno es";
                    text += '|'+data["ticket"];
                    text += "|"+data["vengoa"];
                    if(data["vended"]!=null){
                        text += "|Vendedor: "+data["vended"];
                    }else{ 
                        text += "|" 
                    }
                    text += "|Emision: "+data["fechac"];
                    text += "|"+data["ticket"];
                    var textEncoded = encodeURI(text);
                    
                    window.location.href="-V=" + textEncoded;
                    console.log(text);
                });
                
            });   
        }else{    
            opmenu_enc('retirartic');
        }
        
    }
}
    

//init();