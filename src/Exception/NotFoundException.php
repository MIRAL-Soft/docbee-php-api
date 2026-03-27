<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Exception;

/**
 * Thrown when the requested resource does not exist (HTTP 404).
 */
class NotFoundException extends DocbeeApiException {}
