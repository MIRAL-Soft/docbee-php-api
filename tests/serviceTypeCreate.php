<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\ServiceType;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the service type object
$serviceType = new ServiceType($config);

$articleNumber = 1111;
$articleName = 'IT-Service';
$data = [
    'number' => $articleNumber,
    'name' => $articleName
];

// Create Testuser
$result = $serviceType->create($data);

// if user is successfull
if(is_array($result) && count($result) > 0 && isset($result['id']) && $result['id'] > 0){
    $userID =  $result['id'];
}


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
