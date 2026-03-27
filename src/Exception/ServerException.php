<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Exception;

/**
 * Thrown when the Docbee server returns a 5xx error and all retry attempts
 * have been exhausted.
 */
class ServerException extends DocbeeApiException {}
