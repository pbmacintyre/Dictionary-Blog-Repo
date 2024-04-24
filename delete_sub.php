<?php
/**
 * Copyright (C) 2019-2024 Paladin Business Solutions
 *
 */

require_once "sub_functions.php" ;

// create watching subscription for handling SMS messages
$sdk = mySDK();

$webhook_token = "Provide webhook token"; ;
try {
    $sdk->platform()->delete("/subscription/{$webhook_token}");
    echo "<p style='color: green;'>Subscription [$webhook_token] deleted successfully!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Subscription [$webhook_token] could not be deleted at this time.</p>";
}


