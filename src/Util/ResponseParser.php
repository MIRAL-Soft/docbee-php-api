<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Util;

use miralsoft\docbee\api\Exception\AuthenticationException;
use miralsoft\docbee\api\Exception\DocbeeApiException;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Exception\RateLimitException;
use miralsoft\docbee\api\Exception\ServerException;
use miralsoft\docbee\api\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Converts raw Guzzle responses into decoded PHP arrays or throws typed exceptions.
 *
 * This class is an internal utility and should not be used directly by consumers
 * of the library. Resource classes call it via {@see HttpClient}.
 */
final class ResponseParser
{
    /**
     * Parses a successful response body into an associative array.
     *
     * @return array<string, mixed>
     * @throws DocbeeApiException if the body cannot be decoded.
     */
    public static function parse(ResponseInterface $response, string $requestUrl = ''): array
    {
        $body = (string) $response->getBody();

        if ($body === '' || $body === 'null') {
            return [];
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DocbeeApiException(
                message:      'Failed to decode Docbee API response: ' . json_last_error_msg(),
                statusCode:   $response->getStatusCode(),
                requestUrl:   $requestUrl,
                responseBody: $body,
            );
        }

        if (!is_array($decoded)) {
            throw new DocbeeApiException(
                message:      'Docbee API returned an unexpected response type (expected JSON object).',
                statusCode:   $response->getStatusCode(),
                requestUrl:   $requestUrl,
                responseBody: $body,
            );
        }

        return $decoded;
    }

    /**
     * Maps an HTTP error response to the appropriate typed exception.
     *
     * @param array<string, string[]> $responseHeaders
     * @throws DocbeeApiException always.
     */
    public static function throw(
        int    $statusCode,
        string $responseBody,
        string $requestUrl,
        array  $responseHeaders = [],
    ): never {
        $message = self::extractMessage($responseBody) ?? "HTTP {$statusCode} from Docbee API.";

        $args = [
            'message'      => $message,
            'statusCode'   => $statusCode,
            'requestUrl'   => $requestUrl,
            'responseBody' => $responseBody,
        ];

        throw match (true) {
            $statusCode === 401,
            $statusCode === 403 => new AuthenticationException(...$args),
            $statusCode === 404 => new NotFoundException(...$args),
            $statusCode === 429 => new RateLimitException(...$args),
            $statusCode >= 400 && $statusCode < 500 => new ValidationException(...$args),
            $statusCode >= 500 => new ServerException(...$args),
            default => new DocbeeApiException(...$args),
        };
    }

    /**
     * Attempts to extract a human-readable message from a JSON error body.
     */
    private static function extractMessage(string $body): ?string
    {
        if ($body === '') {
            return null;
        }
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            return null;
        }
        // Only cast scalar values; avoid producing "Array" when the field is an object/array.
        if (isset($decoded['message']) && is_scalar($decoded['message'])) {
            return (string) $decoded['message'];
        }
        if (isset($decoded['error']) && is_scalar($decoded['error'])) {
            return (string) $decoded['error'];
        }
        return null;
    }
}
