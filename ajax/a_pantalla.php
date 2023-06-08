<?php 

require_once "../models/m_videos.php";
require_once "../models/m_ticketenc.php";

if (strlen(session_id())<1) 
	session_start();

$videos=new Videos();
$ticket=new TicketEnc();

$agenci=isset($_POST["agenci"])? limpiarCadena($_POST["agenci"]):"";
$ubicac=isset($_POST["ubicac"])? limpiarCadena($_POST["ubicac"]):"";

$cias=Array();
switch ($_GET["op"]) {
    
    case 'listar_det_pant':
        ?>
            <div class="col-5 titulo">Turno</div>
            <div class="col-7 titulo">Puesto</div>
        <?php
        $fondo = "fondorojo";
        $cont = 5;
        $rspta=$ticket->listar_det_pant();
        while ($reg=$rspta->fetch_object()) {
            if($cont==5){
                $_SESSION["agenci"] = $reg->agencia;
            }
            $cont--;
            ?>
            <div class="col-5 num <?=$fondo;?>">
                <?=$reg->codigo;?>        
            </div>
            <div class="col-7 num <?=$fondo;?>">
                <?=$reg->destin;?>        
            </div>
            
            <?php
            $fondo = "";
            
        }
        
        for ($i=0; $i < $cont; $i++) { ?>
            <div class="col-12 num">                        
            </div>
        <?php } ?>
            
        <?php
        break;
    
    case 'cargavideos':     
        $rspta=$videos->getdirecc2($agenci, $codigo);        
        if($rspta){
            echo json_encode($rspta);
        }else{
            $rspta=$videos->getdirecc2($agenci, 0);
            echo json_encode($rspta);
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