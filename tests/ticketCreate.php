<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\Ticket;
use miralsoft\docbee\api\Priority;
use miralsoft\docbee\api\TicketStatus;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the API object
$customer = new Customer($config);
$ticket = new Ticket($config);
$priority = new Priority($config);
$ticketStatus = new TicketStatus($config);

$customerNumber = 1;
$customerId = $customer->getCustomerFromCustomerId($customerNumber);
if (is_array($customerId) && isset($customerId['id']) && $customerId['id'] > 0) {
    $customerId = $customerId['id'];

    $data = [
        'customer' => $customerId,
        'priority' => $priority->getPriorityId('Normal'),
        'ticketStatus' => $ticketStatus->getTicketStatusId('Offen'),
        'description' => 'Testticket - ' . $customerNumber,
        'erpReferenceNumber' => 'Test-' . $customerNumber
    ];

    // Create Testuser
    $result = $ticket->create($data);

    // if user is successfull
    if (is_array($result) && count($result) > 0 && isset($result['id']) && $result['id'] > 0) {
        $ticketID = $result['id'];
    }

    if (is_array($result)) {
        echo 'Count results: ' . count($result);
    }
    echo '<br><br>';
    print_r($result);
} else {
    echo 'No customer found';
}

