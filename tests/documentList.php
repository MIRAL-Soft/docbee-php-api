<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\DocBeeDocument;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$document = new DocBeeDocument($config);

//$result = $document->get(); // Get all documents
$result = $document->get(5, 20, '*'); // Get 5 documents from the 20. entry with all fields
//$result = [$document->getDocument('261856')]; // Get the document with the ID
//$result = $document->getDocumentsFromCustomerNumber('11954'); // Get the document from Customer
//$result = $document->getDocumentsFromOrderId('11954', '4179'); // Get the document with the orderID from Customer

if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
echo '<br><br>';

foreach ($result as $res) {
    echo json_encode($res) . '<br><br>';
}
