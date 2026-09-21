<?php
/**
 * ============================================================================
 * Sistema de Control de Filas - Cliente de Servicios Web Externos (ws.php)
 * ============================================================================
 * Contiene funciones para el consumo de APIs y servicios web externos
 * de la infraestructura corporativa de Repuestos La Guaca.
 * ============================================================================
 */

/**
 * Consulta la lista de agencias / compañías habilitadas en el Web Service corporativo
 * 
 * @return string Respuesta en formato JSON con el listado de agencias
 */
function getcias() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ws.laguacamaya.cr:13565/wsecom/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'key' => 'tester2024',
            'metodo' => 'cias_filas'
        )
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    
    return $response;
}
?>