<?php
/**
 * Copyright (C) 2019-2024 Paladin Business Solutions
 *
 */

require_once "sub_functions.php" ;

$sdk = mySDK();
$response = $sdk->platform()->get("/subscription");
$subscriptions = $response->json()->records;

foreach ($subscriptions as $subscription) {
    echo "<br/><br/><p style='color: red; display: inline'>Subscription ID:</p> " . $subscription->id . "<br/>";
    echo "<p style='color: red; display: inline'>Subscription Creation Time:</p> " . date("M d, Y g:i:s a", strtotime($subscription->creationTime)) . "<br/>";
    echo "<p style='color: red; display: inline'>Subscription Experation Time:</p> " . date("M d, Y g:i:s a", strtotime($subscription->expirationTime)) . "<br/>";
    echo "<p style='color: red; display: inline'>Subscription Called URI:</p> " . $subscription->deliveryMode->address . "<br/>";
    echo "<p style='color: red; display: inline'>Subscription Transport Type:</p> " . $subscription->deliveryMode->transportType;
}


