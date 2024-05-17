<?php
/**
 * Copyright (C) 2019-2024 Paladin Business Solutions
 *
 */

require_once "sub_functions.php" ;

// create watching subscription for handling SMS messages
$sdk = mySDK();

$webhook_token = "026d127e-2ae6-4aed-bfc4-215a1b17f2b9" ;
try {
    $sdk->platform()->delete("/subscription/{$webhook_token}");
    echo "<p style='color: green;'>Subscription [$webhook_token] deleted successfully!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Subscription [$webhook_token] could not be deleted at this time.</p>";
}


