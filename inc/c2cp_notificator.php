<?php
defined('ABSPATH') || exit;

function notificator_send_message_c2cp_plugin_active( $c2cp_message ){
    $postArgs           = array();
    $postArgs['to']     = '5p0AUSrPZpEPz6vZ6YXHS1H3cySbBSzltOA2Z5ZG';
    $postArgs['text']   = $c2cp_message;

    $ch = curl_init( 'https://notificator.ir/api/v1/send' );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postArgs );

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5 );

    // execute!
    $response = curl_exec($ch);

    // close the connection, release resources used
    curl_close($ch);

    return json_decode( $response );
    
}