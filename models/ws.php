<?php
function getcias(){
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://localhost/wsecom/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('key' => 'test2021','metodo' => 'cias_filas')
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}
?>