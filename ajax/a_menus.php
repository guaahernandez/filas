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

$prefer=isset($_POST["prefer"])? $_POST["prefer"]:"";
$destin=isset($_POST["destin"])? $_POST["destin"]:"";

$cias=Array();
switch ($_GET["op"]) {
    
    case 'cargamenu':
        $rspta=$model->listar($ubmenu);
        $prefer = ($ubmenu=='prefer' || $ubmenu=='vendedores') ? 1 : 0;
        $_SESSION['prefer'] = ($ubmenu=='prefer')? 1: 0;
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
        // $rspta=$ticket->getconsec($agenci, $tipoco);        
        // if($rspta){
        //     $ticket->insertar($agenci, $tipoco.'-'.$rspta, $vended, $prefer, $destin, $tipoco);
        //     $_SESSION["impticket"] = $tipoco.'-'.$rspta;
        //     $_SESSION["impdestino"] = $destin;
        // }
        break;

    case 'opmenu_enc':
        if($ubmenu=='codigoqr'){
            menuqr();
            break;
        }
        if($ubmenu=='retirartic'){
            retirartic();
            break;
        }
        
        if($ubmenu=='principal')
            $_SESSION['prefer'] = '0';
        if($ubmenu=='preferencial'){
            $ubmenu='principal';
            $_SESSION['prefer'] = '1';
        }

        $rowenc=$model->opmenu_enc($ubmenu);        
        ?>
        
        <div align="left" class="col-12" style="padding: 0;">
            <div class="titulo"> <?=$rowenc["titulo"]?> </div>
            <div class="subtitulo"> <?=$rowenc["subtitulo"]?> </div>

            <?php if($rowenc["tipobtn"]==0){ //menu de botones grandes ?>
            <div class="row anchoa">
                <?php
                $cont=0;
                $rspta=$model->opmenu_det($ubmenu);
                // inicia crear botones
                while ($rowdet=$rspta->fetch_object()) { $cont++; 
                    if($rowdet->submen!=''){//si la opcion es vendedor
                        $accion = "opmenu_enc('".$rowdet->submen."');";
                        $acc_a = "#";
                    }else{
                        // $accion = "creaticket('".$agenci."','".$rowdet->codigo."', ".$prefer.");";
                        $accion = "opmenu_enc('retirartic');";
                        $acc_a = "-".$rowdet->codigo;
                    }
                ?>
                
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
                for ($i=0; $i < 9-$cont; $i++) {
                    echo '<div class="col-4"><div class="Masknull" style="padding: 0"></div></div>';
                } 
                // finaliza crear botones
                ?>
            </div>
            
            <?php } ?>


            <?php if($rowenc["tipobtn"]==1){ //menu de botones pequeños ?>
                <div class="row anchoa"><!-- <div class="row" style="width: 95%;"> -->
                <?php   
                $cont=0;
                $rspta=$model->listar_v($agenci, $rowenc["nombre"]);
                // inicia crear botones
                while ($rowdet=$rspta->fetch_object()) { $cont++; 
                    $nombre = $rowdet->nombc03;
                    $accion = "creaticket('".$agenci."','V', '".$_SESSION["prefer"]."', '".$nombre."');";
                    $acc_a = "-V=".$nombre;
                    //if(ValidaLogin($nombre, $rowdet->local)){
                    if(true){
                    ?>                
                    <div class="col-3">    
                        <a href="<?=$acc_a?>" onclick="<?=$accion?>">                
                            <div class="MaskP" onclick="<?=$accion;?>" style="padding: 0">
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
                for ($i=0; $i < 12-$cont; $i++) {
                    echo '<div class="col-3"><div class="MasknullP" style="padding: 0"></div></div>';
                } 
                // finaliza crear botones
                ?>
            </div>
            
            <?php } ?>
            <div class="row anchoa">
                <?php if($_SESSION["prefer"]=='1'){ ?>
                    <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p>Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p><?=$rowenc["bottxt"]?></p>
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
        <!-- </div> -->
        <div class="row">
                <?php if($_SESSION["prefer"]=='1'){ ?>
                    <div class="col-10"><div class="menupref" style="display: block;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('principal')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/regresar.png">
                        <p>Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p><?=$rowenc["bottxt"]?></p>
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
                        <p>Regresar</p>
                    </div>
                <?php }else{ ?>
                    <div class="col-10"><div class="menupref" style="display: none;">MENU PREFERENCIAL</div></div>                
                    <div align="center" onclick="opmenu_enc('<?=$rowenc['botsubmen']?>')" class="col-2 titulofoot">
                        <img class="imgfoot" src="../assets/images/logos/<?=$rowenc["botimg"]?>">
                        <p><?=$rowenc["bottxt"]?></p>
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



