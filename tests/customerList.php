<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\CustomerStatus;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$customer = new Customer($config);

// Gets the customer status ID with the name
$customerStatus = new CustomerStatus($config);

$result = $customer->get(); // Get all customers
//$result = $customer->get(5, 100, '*'); // Get 5 customers from the 100. entry with all fields
//$result = $customer->getCustomer('205048'); // Get the customer with the ID
$result = $customer->getCustomerFromCustomerId('9999'); // Get customer from customerId from erp

if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
