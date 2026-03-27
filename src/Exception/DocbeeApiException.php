<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Exception;

use RuntimeException;

/**
 * Base exception for all Docbee API errors.
 *
 * Catch this single type to handle any failure from the library uniformly:
 *
 * ```php
 * try {
 *     $ticket = $client->tickets()->find(42);
 * } catch (DocbeeApiException $e) {
 *     echo $e->getStatusCode();   // e.g. 404
 *     echo $e->getRequestUrl();   // full URL that was called
 *     echo $e->getResponseBody(); // raw JSON response
 * }
 * ```
 */
class DocbeeApiException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $statusCode = 0,
        private readonly string $requestUrl = '',
        private readonly string $responseBody = '',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    /** HTTP status code returned by the Docbee API. */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /** Full URL of the request that triggered this exception. */
    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    /** Raw response body returned by the Docbee API. */
    public function getResponseBody(): string
    {
        return $this->responseBody;
    }
}
