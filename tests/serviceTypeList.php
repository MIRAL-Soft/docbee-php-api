<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\ServiceType;

// Generate conig with token
$config = new Config(APITOKEN);

$serviceTypes = new ServiceType($config);

//$result = $serviceTypes->get();
//$result = $serviceTypes->get(5, 10, '*'); // Get 5 serviceTypes from the 10. entry with all fields
//$result = [$serviceTypes->getServiceTypeId('Fehlerdiagnose')]; // Get the serviceType with the ID
//$result = $serviceTypes->getServiceTypeFromName('Fehlerdiagnose'); // Get the serviceType from name
$result = $serviceTypes->getServiceTypeFromName('', '1111'); // Get the serviceType from number


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
