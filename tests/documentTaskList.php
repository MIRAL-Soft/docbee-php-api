<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\DocBeeDocument;
use miralsoft\docbee\api\DocBeeDocumentTask;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the API object
$document = new DocBeeDocument($config);
$documentTask = new DocBeeDocumentTask($config);

$result = $document->get(1); // Get all documents

// found a docbeedocument
if (is_array($result) && count($result) > 0 && isset($result[0]['id'])) {
    // First set the document ID for calls
    $documentTask->setDocumentId($result[0]['id']);

    //$result = $documentTask->get();
    $result = $documentTask->get(5, 0, '*'); // Get 5 documents from the first entry with all fields
    //$result = [$documentTask->getTask('67367')]; // Get the document with the ID
}


if (is_array($result)) {
    echo 'Count results: ' . count($result);
}
echo '<br><br>';
print_r($result);
echo '<br><br>';

foreach ($result as $res) {
    echo json_encode($res) . '<br><br>';
}
