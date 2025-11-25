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
$txtllamar = "";

function saveSound($text, $cia)
{
    //$cia = '01';
    if(!is_dir('../assets/audios/'.$cia)){ 
        if(!mkdir('../assets/audios/'.$cia)){
            die('Error al crear directorio');
        }
    }

    $speech_data = getSound($text);//see method upper

    if($speech_data) {                
        $file_name = strtolower(md5(uniqid($text)) . '.mp3');
        //$file_name = 'sonidoact.mp3';
        $path = '../assets/audios/'.$cia.'/';//just return directory path
        if(file_put_contents($path.$file_name, base64_decode($speech_data))){
        //if(!borrar_directorio('audios/'.$cia)) die("Error al borrar direcctorio");
        return $path.$file_name;
        
    }else{
        die('Error al crear el archivo');
    }
        }
    
    return null;     
    
}

function getSound($text)
{            
    try {
        //code...
    
    $text = trim($text);

    if($text == '') return false;
    
    $params = [
        "audioConfig"=>[
            "audioEncoding"=>"LINEAR16",
            "pitch"=> "-1",
            "speakingRate"=> "0.9",
            "effectsProfileId"=> [
                "large-automotive-class-device"
                ]
        ],
        "input"=>[
            "text"=>$text
        ],
        "voice"=>[
            "languageCode"=> "es-US",
            "name" =>"es-US-Standard-A"
        ]
    ];

    $data_string = json_encode($params);
    //$speech_api_key = "AIzaSyCYTqTGAepFytywoTCdIoj2aE6D3dz5jRM";
    $speech_api_key = "AIzaSyCJDRYlpEWIQ_wOl7zgWxTxq2tQNhjoj4Y"; //Api en cuenta de la guaca, generada por Cristian el 22/05/2024
    //$speech_api_key = "";

    $url = 'https://texttospeech.googleapis.com/v1/text:synthesize?fields=audioContent&key=' . $speech_api_key;
    $handle = curl_init($url);
    
    curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($handle, CURLOPT_POSTFIELDS, $data_string);  
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, [                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string)
        ]                                                                       
    );
    $response = curl_exec($handle);              
    $responseDecoded = json_decode($response, true);  
    curl_close($handle);
    if($responseDecoded['audioContent']){
        return $responseDecoded['audioContent'];                
    } 

    return false; 
} catch (Exception $e) {
    echo "Error: ". $e->getMessage();
} 
}


switch ($_GET["op"]) {
    
    case 'listar_det_pant':
        ?>
            <div class="col-5 titulo">TURNO</div>
            <div class="col-7 titulo">PUESTO</div>
        <?php
        $fondo = "fondorojo";
        $cont = 5;
        $rspta=$ticket->listar_det_pant();
        while ($reg=$rspta->fetch_object()) {
            if($cont==5){
                $_SESSION["agenci"] = $reg->agencia;
            }
            $cont--;

            if($reg->llamar==1){
                //$txtllamar = ', v, 10, pasar a ventas 1'; // ejemplo
                $txtllamar = ', '.str_replace('-',',',$reg->ticket).', pasar a '.$reg->destin;
                if($reg->ver_numero > 0) $txtllamar .= ' - '.$reg->estacion;
                
                //saveSound($txtllamar, $reg->agencia);
                //$ticket->desactivarllamar($reg->consec);
                //echo $txtllamar;
            }

            ?>
            <div class="col-5 num <?=$fondo;?>">
                <!-- <?=str_replace('-','',$reg->ticket);?> -->
                <?=$reg->ticket;?>        
            </div>
            <div class="col-7 num <?=$fondo;?>">
                <?php  
                    $estacion = ($reg->ver_numero > 0) ? $reg->estacion : '';
                    echo $reg->destin.' '.$estacion;
                ?>        
            </div>

            <?php
            $fondo = "";
            
        }
        
        for ($i=0; $i < $cont; $i++) { ?>
            <div class="col-12 num"></div>
        <?php } ?>
            
        <?php
        break;

    case 'l_pant_entreg':
        ?>
            <div class="col-6 titulo">FACTURA</div>
            <div class="col-6 titulo">ESTADO</div>
            <!-- <div class="col-4 titulo">ESTAD0</div> -->
        <?php
        $fondo = "fondorojo";
        $cont = 8;
        $rspta=$ticket->l_pant_entreg();
        while ($reg=$rspta->fetch_object()) {
            if($cont==5){
                $_SESSION["agenci"] = $reg->agencia;
            }
            $cont--;

            // if($reg->llamar==1){
            //     //$txtllamar = ', v, 10, pasar a ventas 1'; // ejemplo
            //     $txtllamar = ', '.str_replace('-',',',$reg->ticket).', pasar a '.$reg->destin;
            //     saveSound($txtllamar, $reg->agencia);
            //     $ticket->desactivarllamar($reg->consec);
            //     //echo $txtllamar;
            // }

            ?>
            <div class="col-5 num <?=$fondo;?>">
                <?=substr(str_pad($reg->factura,10,' ',STR_PAD_LEFT),6);?>        
            </div>
            <div class="col-7 num <?=$fondo;?>">
                <?=$reg->estadof;?>        
            </div>

            <?php
            $fondo = "";
            
        }
        
        for ($i=0; $i < $cont; $i++) { ?>
            <div class="col-12 num"></div>
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