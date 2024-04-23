<?php
/**
 * Copyright (C) 2019-2024 Paladin Business Solutions
 *
 */
function show_errors() {
    error_reporting();
//     error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    ini_set('display_errors', 1);
}

/* ================================== */
/* ====== SDK Function ============== */
/* ================================== */
function mySDK () {
    // Include Libraries
    require('includes/vendor/autoload.php');

    // Use Production platform
    $server = 'https://platform.ringcentral.com';

    $jwt_key = "eyJraWQiOiI4NzYyZjU5OGQwNTk0NGRiODZiZjVjYTk3ODA0NzYwOCIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0.eyJhdWQiOiJodHRwczovL3BsYXRmb3JtLnJpbmdjZW50cmFsLmNvbS9yZXN0YXBpL29hdXRoL3Rva2VuIiwic3ViIjoiNjIxOTk0ODYwMTYiLCJpc3MiOiJodHRwczovL3BsYXRmb3JtLnJpbmdjZW50cmFsLmNvbSIsImV4cCI6Mzg2MDc4MjQyNCwiaWF0IjoxNzEzMjk4Nzc3LCJqdGkiOiJIakVKMzl3T1RWTzR3eERaUm9Gc1BBIn0.fOZiP51xW8uhN3gdGN8h30ar9rVJnGJwlIEuKwqjVXLahaL8gGGwyNz5g8BIVomYFdmX5dFOEo7_AwFz9EkylOSqCXofUr15poqhnUjMssDWSgEr4GAt-1IA6KUm28FuQ4SRiyND5rxXY8Dn7cFfmIOT5PbhmE03Lijy1QS6W9Glzbtby2hd0Xeq1XttDA0YNBf_k8SRn7HWvMvZj0VbHXCpDScJn2mwmv-2Z-fmxy1NAQGI56Xel6O4WtS_zcDCdhKFQo1bOeHwvetRG8cSyjkTANhmqU7SFhlRWCYmGxitsdZpj1jQa5Rk4meJ5jdmWDizPkjFHCtdWA2v0Leojw" ;
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
            exit("<br/><br/>Unable to authenticate to platform. Check your RingCentral credentials. <br/><br/>") ;
        }
    }
    return $sdk;
}

function get_dictionary_data ($word) {
    $msg = "";
    $url = 'https://api.dictionaryapi.dev/api/v2/entries/en/' . $word;
    $response = file_get_contents($url);
    if ($response === false) {
        // Handle error
        $msg = false;
    } else {
        // turn the JSON response into an associative array
        $data = json_decode($response, true);
        $msg = $data['chart']['result'][0]['meta']['regularMarketPrice'];
    }
    return $msg;
}

