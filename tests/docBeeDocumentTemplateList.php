<?php

require_once '../vendor/autoload.php';
require_once 'testConfig.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\DocBeeDocumentTemplate;
use miralsoft\docbee\api\DocBeeDocumentTemplateTaskTemplate;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$template = new DocBeeDocumentTemplate($config);


$templateData = $template->get(-1, -1, '', '', 'Neuinstallation');
if(is_array($templateData) && count($templateData) > 0) $templateData = $templateData[0];

if (is_array($templateData) && isset($templateData['id']) && $templateData['id'] > 0) {
    $taskTemplate = new DocBeeDocumentTemplateTaskTemplate($config, $templateData['id']);

    $result = $taskTemplate->get();

    if (is_array($result)) {
        echo 'Count results: ' . count($result);
    }
    echo '<br><br>';
    print_r($result);
} else {
    echo 'No template found!';
}



