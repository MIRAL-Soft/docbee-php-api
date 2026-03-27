<?php

/**
 * Test configuration for Docbee API integration tests.
 *
 * Set the environment variables before running integration tests:
 *
 *   export DOCBEE_TENANT=mycompany
 *   export DOCBEE_TOKEN=your-api-token
 *
 * NEVER commit a real API token here.
 */

define('DOCBEE_TEST_TENANT', getenv('DOCBEE_TENANT') ?: '');
define('DOCBEE_TEST_TOKEN',  getenv('DOCBEE_TOKEN')  ?: '');
