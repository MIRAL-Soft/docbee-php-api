<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Exception;

/**
 * Thrown when the API rejects the request due to invalid input (HTTP 400 / 422).
 */
class ValidationException extends DocbeeApiException {}
