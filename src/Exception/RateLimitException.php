<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Exception;

/**
 * Thrown when the Docbee API rate limit is exceeded (HTTP 429) and all
 * retry attempts have been exhausted.
 */
class RateLimitException extends DocbeeApiException {}
