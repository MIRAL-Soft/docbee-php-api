<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\User;

// Generate conig with token
$config = new Config(APITOKEN);

$user = new User($config);

$result = $user->get();
//$result = $user->get(5, 10, '*'); // Get 5 users from the 10. entry with all fields
//$result = [$user->getUserId('Michael Tosch', 'm.tosch@miralsoft.com')]; // Get the user with the ID
//$result = $user->getUserFromName('Michael Tosch'); // Get the user from name
//$result = $user->getUserFromName('', 'm.tosch@miralsoft.com'); // Get the user from number


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
