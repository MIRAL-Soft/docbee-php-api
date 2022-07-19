<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Tag;

// Generate conig with token
$config = new Config(APITOKEN);

$tags = new Tag($config);

$result = $tags->get();
//$result = $tags->get(5, 1, '*'); // Get 5 tags from the 1. entry with all fields
//$result = [$tags->getTagId('WeclappOrder')]; // Get the tag with the ID
//$result = $tags->getTagFromName('WeclappOrder'); // Get the tag from name


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
