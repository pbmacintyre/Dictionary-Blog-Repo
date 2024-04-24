<?php
/**
 * Copyright (C) 2019-2024 Paladin Business Solutions
 *
 */

/* ================================== */
/* ====== SDK Function ============== */
/* ================================== */
function mySDK () {
    // Include Libraries
    require('includes/vendor/autoload.php');

    // Use Production platform
    $server = 'https://platform.ringcentral.com';

    $jwt_key = "Provide JWT Token"; ;
    $client_id = "Provide Client ID";
    $client_secret = "Provide Client Secret";

    $sdk = new RingCentral\SDK\SDK($client_id, $client_secret, $server);

    // Login via API
    if (!$sdk->platform()->loggedIn()) {
        try {
            $sdk->platform()->login(["jwt" => $jwt_key]);
        }
        catch (\RingCentral\SDK\Http\ApiException $e) {
            $sdk = 0;
            exit("<br/><br/>Unable to authenticate to platform. Check your RingCentral credentials. <br/><br/>") ;
        }
    }
    return $sdk;
}

/* ======================================= */
/* ====== Get Definition Function ======== */
/* ======================================= */
function get_dictionary_data ($word) {
    $msg = array();
    $url = 'https://api.dictionaryapi.dev/api/v2/entries/en/' . $word;
    $response = file_get_contents($url);
    if ($response === false) {
        // Handle error
        $msg = false;
    } else {
        // turn the JSON response into an associative array
        $data = json_decode($response, true);

        $msg['def']  = $data[0]['meanings'][0]['definitions'][0]['definition'];
        $msg['pos']  = $data[0]['meanings'][0]['partOfSpeech'];
        $msg['ex1']  = $data[0]['meanings'][0]['definitions'][0]['example'];
    }
    return $msg;
}

