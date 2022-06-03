<?php

require_once '../vendor/autoload.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\APICall;

// I can't test it correct - I don't delete it for future reasons if we need it

$config = new Config();
$config->setUser('XXX');
$config->setPassword('XXX');

$result = APICall::login($config);

if (is_array($result)) {
    echo 'Login results: ' . count($result);
}
echo '<br><br>';
print_r($result);
