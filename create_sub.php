<?php
/**
 * Copyright (C) 2019-2024 Paladin Business Solutions
 *
 */

require_once "sub_functions.php" ;

if (create_dictionary_webhook_subscription()) {
 echo "<p style='color: green;'>Subscription created successfully!</p>";
} else {
    echo "<p style='color: red;'>Subscription could not be created at this time.</p>";
}

/* ================================================== */
/* ====== Create Subscription Function ============== */
/* ================================================== */
function create_dictionary_webhook_subscription () {
    // create watching subscription for handling SMS messages
    $sdk = mySDK();
    try {
        $api_call = $sdk->platform()->post('/subscription',
            array(
                "eventFilters" => array(
                    "/restapi/v1.0/account/~/extension/~/message-store/instant?type=SMS",
                ),
                "expiresIn" => "315360000",
                "deliveryMode" => array(
                    "transportType" => "WebHook",
                    // need full URL for this to work as well
                    "address" => "https://paladin-bs.com/dictionary_blog/dictionary_webhook.php",
                )
            )
        );
        $webhook_id = $api_call->json()->id;
    }
    catch (\RingCentral\SDK\Http\ApiException $e) {
        return false;
    }
    return $webhook_id;
}



