<?php

/**
 * Copyright (C) 2019-2024 Paladin Business Solutions
 *
 */

// runs when an SMS message comes in from a client for a specific shop
// to process STOP requests

$hvt = isset($_SERVER['HTTP_VALIDATION_TOKEN']) ? $_SERVER['HTTP_VALIDATION_TOKEN'] : '';
if (strlen($hvt) > 0) {
    header("Validation-Token: {$hvt}");
}

$incoming = file_get_contents("php://input");

if (empty($incoming)) {
    http_response_code(200);
    echo json_encode(array('responseType' => 'error', 'responseDescription' => 'No data provided Check SMS payload.'));
    exit();
}

// Log the data or perform any other actions
//file_put_contents("received_sms_layout.log", $incoming);

$incoming_data = json_decode($incoming);

if (!$incoming_data) {
    http_response_code(200);
    echo json_encode(array('responseType' => 'error', 'responseDescription' => 'Media type not supported.  Please use JSON.'));
    exit();
}

require_once "sub_functions.php" ;

// parse out the incoming information
$message = $incoming_data->body->subject;
// the shops mobile number
$incoming_target_mobile_number = $incoming_data->body->to[0]->phoneNumber;
// the customers mobile number
$incoming_client_mobile_number = $incoming_data->body->from->phoneNumber;
$outbound_message = "" ;
// use the shopify shop ringCentral SDK based on the shops JWT and therefore the shops SMS active number

if (preg_match('/^(STOP)$/', $message)) {
    $outbound_message = "You have been removed from our distribution list. Sorry to see you go.";
} elseif (preg_match('/^(DEFINE) [A-Z]+/', $message)) {
    $firstSpacePos = strpos($message, ' ');
    $action = substr($message, 0, $firstSpacePos);
    $word = trim(substr($message, $firstSpacePos + 1));
    $results = get_dictionary_data($word);
    if ($results == false) {
        $outbound_message = "Sorry but we could not locate a definition for $word. Please try a different word. ";
    } else {
        $answer = "[" . $results['pos'] . "] \"" . $results['def'] . "\"";
        $outbound_message = "We found this definition for: $word - ";
        $outbound_message .= $answer ;
        if ($results['ex1']) {
            $outbound_message .= " Here is an example sentence: \"" . $results['ex1'] . "\""; ;
        }
        $outbound_message .= " \nThanks for your inquiry.";
    }
} elseif (preg_match('/^(HELP)$/', $message)) {
    $outbound_message = 'Help Message for Dictionary app goes here...';
} else {
    $outbound_message = "We did not understand your request. Please check the format of your request and try again.";
}

// create watching subscription for handling SMS messages
$sdk = mySDK();

$sdk->platform()->post('/account/~/extension/~/sms',
    array('from' => array('phoneNumber' => $incoming_target_mobile_number),
        'to' => array(array('phoneNumber' => $incoming_client_mobile_number)),
        'text' => $outbound_message));



