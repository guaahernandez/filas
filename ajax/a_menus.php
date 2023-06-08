<?php 
require_once "../models/m_menus.php";
require_once "../models/m_videos.php";
require_once "../models/m_ticketenc.php";

if (strlen(session_id())<1) 
	session_start();

$model=new Menus();
$videos=new Videos();
$ticket=new TicketEnc;

$agenci=isset($_POST["agenci"])? limpiarCadena($_POST["agenci"]):"";
$ubmenu=isset($_POST["ubmenu"])? limpiarCadena($_POST["ubmenu"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$descri=isset($_POST["descri"])? limpiarCadena($_POST["descri"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$ordenm=isset($_POST["ordenm"])? limpiarCadena($_POST["ordenm"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";
$tipoco=isset($_POST["tipoco"])? limpiarCadena($_POST["tipoco"]):"";
$vended=isset($_POST["vended"])? limpiarCadena($_POST["vended"]):"";

$prefer=isset($_POST["prefer"])? limpiarCadena($_POST["prefer"]):"";
$destin=isset($_POST["destin"])? limpiarCadena($_POST["destin"]):"";

$cias=Array();
switch ($_GET["op"]) {
    
    case 'cargamenu':
        $rspta=$model->listar($ubmenu);
        //echo $ubmenu;
        $prefer = ($ubmenu=='prefer') ? 1 : 0;
        while ($reg=$rspta->fetch_object()) {
            ?>
            <div class="col-md-3 col-lg-3 col-xlg-2">
                <div class="card card-hover" onclick="mnopcion('<?=$agenci;?>','<?=$reg->codigo;?>', <?=$prefer;?>);">
                    <div class="box bg-<?=$reg->colorm?> text-center" style="height: 21vh;">
                    <h1 class="text-white" style="padding-top: 15px; padding-bottom: 5px; font-size: 4.5em;">
                        <i class="mdi mdi-<?=$reg->logome;?>"></i>
                    </h1>
                    <h2 class="text-white"><?=$reg->descri?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
        if($ubmenu=='prefer'){ ?>
            <div class="col-md-3 col-lg-3 col-xlg-2">
                <div class="card card-hover" onclick="cargamenu('principal');">
                    <div class="box bg-info text-center" style="height: 21vh;">
                    <h1 class="text-white" style="padding-top: 15px; padding-bottom: 5px; font-size: 4.5em;">
                        <i class="mdi mdi-keyboard-return"></i>
                    </h1>
                    <h2 class="text-white">Regresar</h2>
                    </div>
                </div>
            </div>
        <?php }
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

    case 'creaticket':     
        $rspta=$ticket->getconsec($agenci, $tipoco);        
        if($rspta){
            $ticket->insertar($agenci, $tipoco.$rspta, $vended, $prefer, $destin, $tipoco);
            //echo $rspta;
        }
    break;

    }   
     

    
 ?>

