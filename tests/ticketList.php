<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\Ticket;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$ticket = new Ticket($config);

//$result = $ticket->get(); // Get all tickets
//$result = $ticket->get(5, 1, '*'); // Get 5 tickets from the 100. entry with all fields
//$result = [$ticket->getTicket('261856')]; // Get the ticket with the ID
//$result = $ticket->getTicketsFromCustomerNumber('11954'); // Get the ticket from Customer
$result = $ticket->getTicketsFromOrderId('11954', '4179'); // Get the ticket with the orderID from Customer

if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
echo '<br><br>';

foreach ($result as $res) {
    echo json_encode($res) . '<br><br>';
}
