<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Priority;

// Generate conig with token
$config = new Config(APITOKEN);

$priorities = new Priority($config);

$result = $priorities->get();
//$result = $priorities->get(5, 1, '*'); // Get 5 prioritys from the 1. entry with all fields
//$result = [$priorities->getPriorityId('Normal')]; // Get the priority with the ID
//$result = $priorities->getPriorityFromName('Normal'); // Get the priority from name


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
