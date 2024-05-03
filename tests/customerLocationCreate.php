<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\CustomerLocation;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$customer = new Customer($config);
$erpId = '1';

$customerData = $customer->getCustomerFromCustomerId($erpId);

if (is_array($customerData) && isset($customerData['id']) && $customerData['id'] > 0) {
    $customerLocation = new CustomerLocation($config, $customerData['id']);

    $actCity = 'Musterstadt';
    $actStreet = 'Musterstr. 1';
    $actZipcode = '12345';

    $data = [
        'name' => 'Primary Address',
        'city' => 'Musterstadt',
        'street' => 'Musterstr. 2',
        'zipcode' => '12345',
        // synctoapp have to be activated in configuration
        //'syncToApp' => false
    ];

    // Create a new location
    //$result = $customerLocation->create($data);

    // Edit a location with the given data
    $result = $customerLocation->edit($data, '', $actCity, $actStreet, $actZipcode);

    if (is_array($result)) {
        echo 'Count results: ' . count($result);
    }
    echo '<br><br>';
    print_r($result);
} else {
    echo 'Customer with ERP-ID ' . $erpId . ' not found!';
}



