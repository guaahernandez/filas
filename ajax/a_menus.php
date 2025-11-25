<?php

use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\AssignOp\Div;

require_once "../models/m_menus.php";
require_once "../models/m_videos.php";
require_once "../models/m_ticketenc.php";

if (strlen(session_id())<1) 
	session_start();

$model=new Menus();
$videos=new Videos();
$ticket=new TicketEnc;

$agenci=isset($_POST["agenci"])? limpiarCadena($_POST["agenci"]):$_SESSION["agenci"];
$ubmenu=isset($_POST["ubmenu"])? limpiarCadena($_POST["ubmenu"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$descri=isset($_POST["descri"])? limpiarCadena($_POST["descri"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$ordenm=isset($_POST["ordenm"])? limpiarCadena($_POST["ordenm"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";
$tipoco=isset($_POST["tipoco"])? limpiarCadena($_POST["tipoco"]):"";
$vended=isset($_POST["vended"])? limpiarCadena($_POST["vended"]):"";
$numfac=isset($_POST["numfac"])? limpiarCadena($_POST["numfac"]):"";

$prefer=isset($_POST["prefer"])? $_POST["prefer"]:"0";
$destin=isset($_POST["destin"])? $_POST["destin"]:"";
$factur=isset($_POST["factur"])? $_POST["factur"]:"";
$montof=isset($_POST["montof"])? $_POST["montof"]: 0;
$tidoc=isset($_POST["tidoc"])? $_POST["tidoc"]:"";
$estacion=isset($_POST["estacion"])? $_POST["estacion"]:"";

$segund=isset($_POST["prefer"])? $_POST["prefer"]:"0";
$ubicac=isset($_POST["ubicac"])? $_POST["ubicac"]:"";
$codigt=isset($_POST["codigt"])? $_POST["codigt"]:"0";
$prefac=isset($_POST["prefac"])? $_POST["prefac"]:"";

$android = (isset($_SESSION["android"])) ? $_SESSION["android"] : "0";
$turno = (isset($_POST["turno"])) ? $_POST["turno"] : "";

//echo "sql: " . $sql;
$cias=Array();
switch ($_GET["op"]) {
    
    case 'cargamenu':
        $rspta=$model->listar($ubmenu);
        $prefer = ($ubmenu=='prefer' || $ubmenu=='vendedores') ? 1 : 0;
        $_SESSION['prefer'] = ($ubmenu=='prefer')? 1: 0;
        //echo 'Prefer: '.$prefer;
        ?>
        <div class="row" style="padding: 0; margin: 0;">      
        
            <div class="col-8 titulo">
                Seleccione el trámite por realizar:
            </div>
            <div class="col-4">
            <?php if($prefer==1){ ?>
            <div  onclick="cargamenu('principal');"                
                style="border: solid 1px #1b468c;                  
                border-radius: 10px;                 
                font-weight: 900;
                font-size: 60px;
                background-color: #f6f7fb;
                color: #0f3e7f;">
                <i class="mdi mdi-arrow-left"></i>
            </div>
            <?php } ?>
            </div>
        <?php
        
        while ($reg=$rspta->fetch_object()) {
            if($reg->codigo=='A'){//si la opcion es vendedor
                $accion = "cargamenu_v('".$agenci."');";
            }else{
                $accion = "creaticket('".$agenci."','".$reg->codigo."', ".$prefer.");";
            }
            ?>
            <div class="col-md-3" style="margin: 0; padding: 0;">
                <div onclick="<?=$accion;?>" 
                    class="mCard">
                    <div class="mCardlogo"> 
                        <i class="mdi mdi-<?=$reg->logome;?>"></i>                       
                    </div>
                    <div class="texto"><?=$reg->descri?></div>
                    <div style="font-size: 35px; color: #0f3e7f;"><i class="mdi mdi-arrow-right"></i></div>
                </div>
            </div>
            <?php
        }
        
        echo '</div>';
        break;
    
    case 'cargamenu_v':
        $rspta=$model->listar_v($agenci);
        $pref = 1;
        ?>
        <div class="row" style="padding: 0; margin: 0;">
        <div class="col-8 titulo">
            Seleccione el vendedor:
        </div>
        <div class="col-4">        
            <div  onclick="cargamenu('principal');"                
                style="border: solid 1px #1b468c;                  
                border-radius: 10px;                 
                font-weight: 900;
                font-size: 60px;
                background-color: #f6f7fb;
                color: #0f3e7f;">
                <i class="mdi mdi-arrow-left"></i>
            </div>        
        </div>
        <?php
        while ($reg=$rspta->fetch_object()) {
            $nombre = $reg->nombc03;
            $accion = "creaticket('".$agenci."','V', '".$_SESSION["prefer"]."', '".$nombre."');";
            
            if(ValidaLogin($nombre)){
            //if(true){
            ?>
            <!-- se define la cantidad de columnas 3=4 4=3-->
            <div class="col-md-3" style="margin: 0; padding: 0;">
                <div onclick="<?=$accion;?>" 
                    class="mCard">
                    <div class="mCardlogo"> 
                        <i class="mdi mdi-account"></i>                       
                    </div>
                    <div class="texto" style="margin-top: 15px; height: 60px;"><?=$reg->nombl03?></div>
                    <div style="font-size: 35px; color: #0f3e7f;"><i class="mdi mdi-arrow-right"></i></div>
                </div>
            </div>
            <?php
            }
        }
        
        echo '</div>';
        break;
    
    case 'cargavideos':     
        $rspta=$videos->getdirecc2($codigo);        
        if($rspta){
            echo json_encode($rspta);
        }else{
            $rspta=$videos->getdirecc2(0);
            echo json_encode($rspta);
        }    
        break;
    
    case 'getconsec':     
        $rspta=$ticket->getconsec($agenci, $tipoco);        
        if($rspta){
            echo $rspta;
        }else{
            echo 'Fallo consec';
        }    
        break;

    case 'getpromo':     
        $rspta=$model->getpromo();        
        if($rspta){
            echo $rspta["link"];
        }else{
            echo 'Fallo promo';
        }    
        break;

    case 'creaticket':     
        $rspta=$ticket->getconsec($agenci, $tipoco);        
        if($rspta){
            echo $ticket->insertar($agenci, $tipoco.'-'.$rspta, $vended, $prefer, $destin, $tipoco, $factur, $tidoc, $montof).'|'.$tipoco.'-'.$rspta;
            $_SESSION["impticket"] = $tipoco.'-'.trim($rspta);
            $_SESSION["impdestino"] = $destin;
        }
        break;
    case 'creaticket_det':   
        echo $ticket->insertardet($agenci, $_SESSION["impticket"], $segund, $ubicac, $destin, $codigt, $prefac, $factur, $montof, $estacion);
        break;

    case 'guarda_fact_ticket':    
        echo $ticket->guarda_fact_ticket($codigt, $agenci, $turno, $factur);
        break;

    case 'obtener_imprimir':            
        $rspta=$ticket->obtener_imprimir($codigo);
        echo json_encode($rspta);
        break;

    case 'opmenu_enc':

        if($ubmenu=='principal')
            $_SESSION['prefer'] = '0';
        if($ubmenu=='preferencial'){
            $ubmenu='principal';
            $_SESSION['prefer'] = '1';
        }
        //echo 'prefer: '.$_SESSION['prefer'];

        if($ubmenu=='codigoqr'){
            menuqr();
            break;
        }

        if($ubmenu=='codigobr'){
            menubr($agenci, 'E', $_SESSION['prefer'], $vended, $android);
            break;
        }

        if($ubmenu=='retirartic'){
            retirartic();
            break;
        }

        if($ubmenu=='pasaentreg'){
            pasaentreg();
            break;
        }
        

        $rowenc=$model->opmenu_enc($ubmenu);        
        ?>
        
        <div align="left" class="col-12" style="padding: 0;">
            <div class="titulo"> <?=$rowenc["titulo"]?> </div>
            <div class="subtitulo"> <?=$rowenc["subtitulo"]?> </div>

            <?php if($rowenc["tipobtn"]==0){ //menu de botones grandes ?>
            <!-- <div class="row" style="height: 44vh; max-height: 44vh; overflow-x: hidden; overflow-y: scroll;"> -->
            <div class="row" style="height: 43vh; overflow-x: hidden; overflow-y: scroll;">
                <?php
                $cont=0;
                $rspta=$model->opmenu_det($ubmenu);
                // inicia crear botones
                while ($rowdet=$rspta->fetch_object()) { $cont++; 
                    if($rowdet->submen!=''){//si la opcion es vendedor
                        if($android==1 && $ubmenu=='entregas'){
                            $accion = "opmenu_enc('principal')";
                            $acc_a = "-T";
                        }else{
                            $accion = "opmenu_enc('".$rowdet->submen."');";
                            $acc_a = "#";
                        }
                        
                    }else{

                        $acc_a = "-";
                        $acc_a .= ($_SESSION['prefer']=='1')?'P':'';
                        $acc_a .= $rowdet->codigo;

                        if($android=="1"){
                            /*Si es android crea el ticket con js en a_menus.php*/
                            //ejemplo creaticket('S-03','V', ,'','','1')
                            $accion = "creaticket('".$agenci."','".$rowdet->codigo."', '".$_SESSION["prefer"]."','','',1);";
                            $acc_a = "#";
                        }else{
                            /*si es windows crea el ticket en vb, aplicacion pantalla.exe */
                            /**aqui solo muestra la pantalla de retirartic */
                            $accion = "opmenu_enc('retirartic');";
                        }                       
                        
                    }
                ?>
                
                <!-- definicion de cantidad de iconos en kioscoh a lo ancho 6=2 4=3-->
                <div class="col-4"> 
                    <a href="<?=$acc_a?>" onclick="<?=$accion?>">
                    <div class="Mask" style="padding: 0">
                        <div class="Oval">
                            <img src="../assets/images/logos/<?=$rowdet->logome?>" class="imgOval">
                        </div>
                        <div class="tituloMask"><?=$rowdet->descri?></div>
                        <div class="select">SELECCIONAR</div>
                    </div> 
                    </a>                   
                </div>
                <?php } 
                //rellena los botones faltantes en blanco
                for ($i=0; $i < 4-$cont; $i++) {
                    echo '<div class="col-4"><div class="Masknull" style="padding: 0"></div></div>';
                } 
                // finaliza crear botones
                ?>
            </div>
            
            <?php } ?>


            <?php if($rowenc["tipobtn"]==1){ //menu de botones pequeños ?>
                <!-- <div class="row" style="height: 44vh; max-height: 44vh; max-height: 44vh; overflow-x: hidden; overflow-y: scroll;"> -->
                <div class="row" style="max-height: 54vh; overflow-x: hidden; overflow-y: scroll;">
                <?php   
                $cont=0;
                $rspta=$model->listar_v($agenci, $rowenc["nombre"]);
                // inicia crear botones
                while ($rowdet=$rspta->fetch_object()) { $cont++; 
                    $nombre = $rowdet->nombc03;

                    if($android=="1"){
                        /*Si es android crea el ticket con js en a_menus.php*/
                        //ejemplor creaticket('S-03','V', ,'','','1')
                        $accion = "creaticket('".$agenci."','".$rowenc["codigo"]."', '".$_SESSION["prefer"]."', '".$nombre."','',1);";
                        //$acc_a = "-V=".$nombre;// comentado el 07/02/2025 para obtener la letra incial del comprobante
                        //$acc_a = "-".$rowenc["codigo"]."=".$nombre; //comentado el 04/04/2025 porque no imprime el ticket en android
                        $acc_a = "#";
                    }else{
                        $accion = "creaticket('".$agenci."','".$rowenc["codigo"]."', '".$_SESSION["prefer"]."', '".$nombre."','',0);";
                        $acc_a = "-".$rowenc["codigo"]."=".$nombre;                        
                    }
                    //if(ValidaLogin($nombre, $rowdet->local)){
                    if(true){
                    ?>                
                    <div class="col-4">    
                        <a href="<?=$acc_a?>" onclick="<?=$accion?>">                
                            <div class="MaskP" style="padding: 0">
                                <div class="OvalP">
                                    <div class="circular--landscapeP">
                                        <img src="../assets/vendedores/<?=$rowdet->imagen?>" >
                                    </div>
                                </div>
                                <div class="tituloMaskP"><?=$rowdet->nombl03?></div>
                                <div class="selectP">SELECCIONAR</div>
                            </div>                    
                        </a>
                    </div>
                    <?php 
                    }
                }

                //rellena los botones faltantes en blanco
                for ($i=0; $i < 6-$cont; $i++) {
                    echo '<div class="col-3"><div class="MasknullP" style="padding: 0"></div></div>';
                } 
                // finaliza crear botones
                ?>
            </div>
            
            <?php } ?>
            <div class="row anchoa">
                <?php if($_SESSION["prefer"]=='1'){ ?>
                    <div class="col-8"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-3 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p class="titbtnfoot">Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-8"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-3 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        break;

    }   
     
    function menuqr(){
        $model=new Menus();
        $rowenc=$model->opmenu_enc('codigoqr'); 
        //print_r($rowenc);
        $rspta=$model->opmenu_det('codigoqr');
        // inicia crear botones
        while ($rowdet=$rspta->fetch_object()) { ?>
        <div align="center">        
            <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
            <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
            <img class="arrowqr" src="../assets/images/logos/arrow.png">        
        </div>
        <?php }    
        ?>
        
        <div class="row">
                <?php if($_SESSION["prefer"]=='1'){ ?>
                    <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p class="titbtnfoot">Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
                    </div>
                <?php } ?>
            </div>
        <?php return false;
    }

    function menubr($agenci, $tipoco, $prefer, $vended, $android="0"){
        $model=new Menus();
        $rowenc=$model->opmenu_enc('codigobr'); 
        $rspta=$model->opmenu_det('codigobr');
        //print_r($rowenc);
        // inicia crear botones
        while ($rowdet=$rspta->fetch_object()) { ?>
        <div align="center">        
            <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
            <input class="numfac" type="text" autocomplete="off" autofocus id="numfac" name="numfac" onkeyup="onKeyUp(event, '<?=$prefer?>','<?=$android?>','<?=$agenci?>')" />
            <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
            <img class="arrowbr" src="../assets/images/logos/arrow.png">        
        </div>
        

        <?php }    
        ?>
        <!-- </div> -->
        <div class="row">
                <?php if($_SESSION["prefer"]=='1'){ ?>
                    <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p class="titbtnfoot">Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
                    </div>
                <?php } ?>
            </div>
        <?php return false;
    }

    function retirartic(){
        $model=new Menus();
        $rowenc=$model->opmenu_enc('retirartic');
        $rspta=$model->opmenu_det('retirartic');
        // inicia crear botones
        while ($rowdet=$rspta->fetch_object()) { ?>
        <div align="center">        
            <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
            <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
            <img class="arrowqr" src="../assets/images/logos/arrow.png">        
        </div>
        <?php }    
        ?>
        <!-- </div> -->
        <div class="row">
                <?php if($_SESSION["prefer"]=='1'){ ?>
                    <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p class="titbtnfoot">Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
                    </div>
                <?php } ?>
            </div>
        <?php return false;
    }

    function pasaentreg(){
        $model=new Menus();
        $rowenc=$model->opmenu_enc('pasaentreg');
        $rspta=$model->opmenu_det('pasaentreg');
        // inicia crear botones
        while ($rowdet=$rspta->fetch_object()) { ?>
        <div align="center">        
            <img class="logoqr" src="../assets/images/logos/<?=$rowdet->logome?>">    
            <div class="tituloqr"> <?=$rowenc["subtitulo"]?> </div>        
            <div class="arrowqr"></div>        
        </div>
        <?php }    
        ?>
        <!-- </div> -->
        <div class="row">
                <?php if($_SESSION["prefer"]=='1'){ ?>
                    <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p class="titbtnfoot">Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p class="titbtnfoot"><?=$rowenc["bottxt"]?></p>
                    </div>
                <?php } ?>
            </div>
        <?php return false;
    }

    function ValidaLogin($nombre, $local){
        if($local==1){
            return true;
        }
        $ch = curl_init("http://10.0.1.151:84/ws/usractivo.prg?usr=".$nombre);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //obtenemos la respuesta
        $json = curl_exec($ch);
        //echo intval($json);
        curl_close($ch);
        return intval($json)>0;
    }  

 ?>



