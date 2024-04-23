<?php
require_once('includes/ringcentral-functions.inc');
require_once('includes/ringcentral-php-functions.inc');
require('includes/vendor/autoload.php');

show_errors();

$from_mobile = "+18084652470";
$to_mobile = "+19029405827";

//echo_spaces("testing code tester 6","", 1);

//$response = $sdk->platform()->get('/account/~/extension/~/business-hours');
//$biz_hours = $response->jsonArray();
//
//foreach ($biz_hours['schedule']['weeklyRanges'] as $key => $value) {
//    echo $key . " => " . $value[0]['from'] . "am to: " . ($value[0]['to'] - 12) . "pm <br/>";
//}

/* ======================================= */
/* ====== Call SDK Function ============== */
/* ======================================= */

//create_finserv_webhook_subscription();

$sdk = mySDK();
$response = $sdk->platform()->get("/subscription");
$subscriptions = $response->json()->records;

foreach ($subscriptions as $subscription) {
//    echo "<br/><br/> Subscription ID: " . $subscription->id . "<br/>";
//    echo "Subscription Creation Time: " . date("M d, Y g:i:s a", strtotime($subscription->creationTime)) . "<br/>";
//    echo "Subscription Experation Time: " . date("M d, Y g:i:s a", strtotime($subscription->expirationTime)) . "<br/>";
//    echo "Subscription Called URI: " . $subscription->deliveryMode->address . "<br/>";
//    echo "Subscription Transport Type: " . $subscription->deliveryMode->transportType ;
//    if ($subscription->id == "e0c39395-f798-410c-8605-1c1c861c980e") {
        echo_spaces("Subscription", $subscription->id);
        echo_spaces("Subscription Status", $subscription->status);
        echo_spaces("Subscription Creation Time", date("M d, Y g:i:s a", strtotime($subscription->creationTime)));
        echo_spaces("Subscription Experation Time", date("M d, Y g:i:s a", strtotime($subscription->expirationTime)));
        echo_spaces("Subscription URI", $subscription->deliveryMode->address);
        echo_spaces("Subscription URI", $subscription->deliveryMode->transportType, 1);
//    }
}

/* ================================== */
/* ====== SDK Function ============== */
/* ================================== */
function mySDK () {
    // Include Libraries
    require('includes/vendor/autoload.php');

    $jwt_key = "eyJraWQiOiI4NzYyZjU5OGQwNTk0NGRiODZiZjVjYTk3ODA0NzYwOCIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0.eyJhdWQiOiJodHRwczovL3BsYXRmb3JtLnJpbmdjZW50cmFsLmNvbS9yZXN0YXBpL29hdXRoL3Rva2VuIiwic3ViIjoiNjIxOTk0ODYwMTYiLCJpc3MiOiJodHRwczovL3BsYXRmb3JtLnJpbmdjZW50cmFsLmNvbSIsImV4cCI6Mzg2MDc4MjQyNCwiaWF0IjoxNzEzMjk4Nzc3LCJqdGkiOiJIakVKMzl3T1RWTzR3eERaUm9Gc1BBIn0.fOZiP51xW8uhN3gdGN8h30ar9rVJnGJwlIEuKwqjVXLahaL8gGGwyNz5g8BIVomYFdmX5dFOEo7_AwFz9EkylOSqCXofUr15poqhnUjMssDWSgEr4GAt-1IA6KUm28FuQ4SRiyND5rxXY8Dn7cFfmIOT5PbhmE03Lijy1QS6W9Glzbtby2hd0Xeq1XttDA0YNBf_k8SRn7HWvMvZj0VbHXCpDScJn2mwmv-2Z-fmxy1NAQGI56Xel6O4WtS_zcDCdhKFQo1bOeHwvetRG8cSyjkTANhmqU7SFhlRWCYmGxitsdZpj1jQa5Rk4meJ5jdmWDizPkjFHCtdWA2v0Leojw";

    // Use Production platform
    $server = 'https://platform.ringcentral.com';
    $client_id = "ey5YmR27By0bcwttw2hAzu";
    $client_secret = "dHO92FNjbu7bDLeDks18c4VmPNOdlHi9lahyxki86Lqm";

    $sdk = new RingCentral\SDK\SDK($client_id, $client_secret, $server);

    // Login via API
    if (!$sdk->platform()->loggedIn()) {
        try {
            $sdk->platform()->login(["jwt" => $jwt_key]);
        }
        catch (\RingCentral\SDK\Http\ApiException $e) {
            $sdk = 0;
            // exit("<br/><br/>Unable to authenticate to platform. Check your RingCentral credentials. <br/><br/>") ;
        }
    }
    return $sdk;
}

function create_finserv_webhook_subscription () {
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
                    "address" => "https://paladin-bs.com/shopifyapp/finserv_webhook.php",
                )
            )
        );
        $webhook_id = $api_call->json()->id;
    }
    catch (\RingCentral\SDK\Http\ApiException $e) {
        exit();
    }
    return $webhook_id;
}