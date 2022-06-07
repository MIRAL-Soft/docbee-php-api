<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\APICall;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\CustomerStatus;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$customer = new Customer($config);

$customerId = 10026;
$data = [
    'customerId' => $customerId,
    'name' => 'Testuser API - ' . $customerId
];

// Gets the customer status ID with the name
$customerStatus = new CustomerStatus($config);
$data['customerStatus'] = $customerStatus->getStatusID('Aktiv');

// Create Testuser
$result = $customer->create($data);

// if user is successfull
if(is_array($result) && count($result) > 0 && isset($result['id']) && $result['id'] > 0){
    $userID =  $result['id'];
}


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
