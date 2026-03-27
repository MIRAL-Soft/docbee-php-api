<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Exception;

/**
 * Thrown when the API token is missing, invalid, or expired (HTTP 401 / 403).
 */
class AuthenticationException extends DocbeeApiException {}
