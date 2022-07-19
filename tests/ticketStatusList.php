<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\TicketStatus;

// Generate conig with token
$config = new Config(APITOKEN);

$ticketStatus = new TicketStatus($config);

//$result = $ticketStatus->get();
//$result = $ticketStatus->get(5, 1, '*'); // Get 5 ticketStatus from the 1. entry with all fields
//$result = [$ticketStatus->getTicketStatusId('Offen')]; // Get the ticketStatus with the ID
$result = $ticketStatus->getTicketStatusFromName('Offen'); // Get the ticketStatus from name


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
