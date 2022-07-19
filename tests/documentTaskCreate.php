<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;
use miralsoft\docbee\api\Ticket;
use miralsoft\docbee\api\Priority;
use miralsoft\docbee\api\TicketStatus;
use miralsoft\docbee\api\DocBeeDocument;
use miralsoft\docbee\api\DocBeeDocumentTask;
use miralsoft\docbee\api\ServiceType;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the API object
$customer = new Customer($config);
$ticket = new Ticket($config);
$priority = new Priority($config);
$ticketStatus = new TicketStatus($config);
$document = new DocBeeDocument($config);
$documentTask = new DocBeeDocumentTask($config);
$serviceType = new ServiceType($config);

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

    // Create testticket
    $result = $ticket->create($data);

    // if ticket is successfull
    if (is_array($result) && count($result) > 0 && isset($result['id']) && $result['id'] > 0) {
        $ticketID = $result['id'];

        // Add document for ticket
        $data = [
            'customer' => $customerId,
            'ticket' => $result['id'],
            'priority' => $priority->getPriorityId('Normal'),
            'erpReferenceNumber' => 'Testdocument-' . $customerNumber
        ];

        // Create document
        $result = $document->create($data);

        // if ticket is successfull
        if (is_array($result) && count($result) > 0 && isset($result['id']) && $result['id'] > 0) {
            $documentID = $result['id'];

            // Sets the document for future calls
            $documentTask->setDocumentId($documentID);

            $data = [
                'name' => 'Testdocumenttask-' . $customerNumber,
                'description' => 'A description for this task',
                'internalDescription' => 'Internal description for this task',
                'serviceType' => $serviceType->getServiceTypeId('', '1111')
            ];

            $result = $documentTask->create($data);
        }
    }

    if (is_array($result)) {
        echo 'Count results: ' . count($result);
    }
    echo '<br><br>';
    print_r($result);
} else {
    echo 'No customer found';
}

