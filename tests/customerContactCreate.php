<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\CustomerContact;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$customer = new Customer($config);
$erpId = '10022';

$customerData = $customer->getCustomerFromCustomerId($erpId);

if (is_array($customerData) && isset($customerData['id']) && $customerData['id'] > 0) {
    $customerContact = new CustomerContact($config, $customerData['id']);

    $actMail = $erpId . '@xyz.de';

    $data = [
        'name' => 'Max Mustermann',
        'email' => $actMail,
        'mobile' => '0176234567455',
        'telephone' => '0123456789'
    ];

    // Create a new contact
    //$result = $customerContact->create($data);

    // Edit a contact with the given mail adress
    $result = $customerContact->edit($data, '', $actMail);

    if (is_array($result)) {
        echo 'Count results: ' . count($result);
    }
    echo '<br><br>';
    print_r($result);
} else {
    echo 'Customer with ERP-ID ' . $erpId . ' not found!';
}



