<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use miralsoft\docbee\api\Config\DocbeeConfig;
use miralsoft\docbee\api\Exception\DocbeeApiException;
use miralsoft\docbee\api\Util\ResponseParser;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Low-level HTTP client wrapping Guzzle for Docbee API communication.
 *
 * Handles authentication headers, request/response logging, and delegates
 * rate-limit / server-error retries to {@see RateLimiter}.
 *
 * This class is internal – consumers should use resource classes instead.
 */
final class HttpClient implements HttpClientInterface
{
    private readonly GuzzleClient $guzzle;
    private readonly LoggerInterface $logger;

    public function __construct(
        private readonly DocbeeConfig $config,
        ?GuzzleClient    $guzzle = null,
        ?LoggerInterface $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger();
        $this->guzzle = $guzzle ?? new GuzzleClient([
            'timeout'         => $config->getTimeout(),
            'connect_timeout' => $config->getConnectTimeout(),
            'http_errors'     => false,  // We handle errors ourselves.
            'verify'          => true,   // Enforce TLS certificate verification (explicit, never disable).
            'allow_redirects' => [
                'max'       => 5,
                'strict'    => true,     // Use original method on redirect (no GET downgrade).
                'protocols' => ['https'], // Never follow a redirect to plain HTTP.
            ],
        ]);
    }

    // -------------------------------------------------------------------------
    // Public HTTP verbs
    // -------------------------------------------------------------------------

    /**
     * Performs a GET request and returns the decoded response array.
     *
     * @return array<string, mixed>
     * @throws DocbeeApiException
     */
    public function get(string $path): array
    {
        $url      = $this->buildUrl($path);
        $start    = hrtime(true);
        $response = $this->withRetry(fn() => $this->guzzle->get($url, $this->headers()));
        $ms       = $this->elapsed($start);

        $this->logger->info("GET {$url} → {$response->getStatusCode()} ({$ms} ms)");
        $this->throwIfError($response, $url);

        return ResponseParser::parse($response, $url);
    }

    /**
     * Performs a POST request with a JSON body and returns the decoded response.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws DocbeeApiException
     */
    public function post(string $path, array $data = []): array
    {
        $url      = $this->buildUrl($path);
        $start    = hrtime(true);
        // POST is not idempotent: a 5xx may occur AFTER the server processed the
        // request (e.g. record created, then timeout) — retrying risks duplicates.
        // 429 is still retried (a rate-limited request was rejected unprocessed).
        $response = $this->withRetry(
            fn() => $this->guzzle->post($url, $this->headers(['json' => $data])),
            retryServerErrors: false,
        );
        $ms = $this->elapsed($start);

        $this->logger->info("POST {$url} → {$response->getStatusCode()} ({$ms} ms)");
        $this->throwIfError($response, $url);

        return ResponseParser::parse($response, $url);
    }

    /**
     * Performs a PUT request with a JSON body and returns the decoded response.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws DocbeeApiException
     */
    public function put(string $path, array $data = []): array
    {
        $url      = $this->buildUrl($path);
        $start    = hrtime(true);
        $response = $this->withRetry(
            fn() => $this->guzzle->put($url, $this->headers(['json' => $data]))
        );
        $ms = $this->elapsed($start);

        $this->logger->info("PUT {$url} → {$response->getStatusCode()} ({$ms} ms)");
        $this->throwIfError($response, $url);

        return ResponseParser::parse($response, $url);
    }

    /**
     * Performs a DELETE request.
     *
     * @throws DocbeeApiException
     */
    public function delete(string $path): void
    {
        $url      = $this->buildUrl($path);
        $start    = hrtime(true);
        $response = $this->withRetry(fn() => $this->guzzle->delete($url, $this->headers()));
        $ms       = $this->elapsed($start);

        $this->logger->info("DELETE {$url} → {$response->getStatusCode()} ({$ms} ms)");
        $this->throwIfError($response, $url);
    }

    /**
     * Performs a GET request and returns the raw (binary) response body.
     *
     * Use this for endpoints that return file data (PDF, CSV, …) rather than JSON.
     *
     * @return string Raw response bytes.
     * @throws DocbeeApiException
     */
    public function getRaw(string $path): string
    {
        $url      = $this->buildUrl($path);
        $start    = hrtime(true);
        $response = $this->withRetry(fn() => $this->guzzle->get($url, $this->headersRaw()));
        $ms       = $this->elapsed($start);

        $this->logger->info("GET (raw) {$url} → {$response->getStatusCode()} ({$ms} ms)");
        $this->throwIfError($response, $url);

        return (string) $response->getBody();
    }

    /**
     * Performs a POST request with a JSON body and returns the raw (binary) response body.
     *
     * Use this for export endpoints that return file data (PDF, CSV, …) rather than JSON.
     *
     * @param array<string, mixed> $data
     * @return string Raw response bytes.
     * @throws DocbeeApiException
     */
    public function postRaw(string $path, array $data = []): string
    {
        $url      = $this->buildUrl($path);
        $start    = hrtime(true);
        // See post(): POST is not idempotent, 5xx is not retried.
        $response = $this->withRetry(
            fn() => $this->guzzle->post($url, $this->headersRaw(['json' => $data])),
            retryServerErrors: false,
        );
        $ms = $this->elapsed($start);

        $this->logger->info("POST (raw) {$url} → {$response->getStatusCode()} ({$ms} ms)");
        $this->throwIfError($response, $url);

        return (string) $response->getBody();
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Throws a typed exception if the response status indicates an error.
     *
     * Extracted to avoid duplicating the same 6-line block across every HTTP verb.
     *
     * @throws DocbeeApiException
     */
    private function throwIfError(\Psr\Http\Message\ResponseInterface $response, string $url): void
    {
        if ($response->getStatusCode() >= 400) {
            ResponseParser::throw(
                statusCode:      $response->getStatusCode(),
                responseBody:    (string) $response->getBody(),
                requestUrl:      $url,
                responseHeaders: $response->getHeaders(),
            );
        }
    }

    /** Builds the full URL from a relative path. */
    private function buildUrl(string $path): string
    {
        return rtrim($this->config->getBaseUrl(), '/') . '/' . ltrim($path, '/');
    }

    /**
     * Returns Guzzle request options including the Authorization header.
     *
     * @param array<string, mixed> $extra
     * @return array<string, mixed>
     */
    private function headers(array $extra = []): array
    {
        return array_merge([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config->getToken(),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ], $extra);
    }

    /**
     * Returns Guzzle request options for raw (binary) responses.
     *
     * Omits `Accept: application/json` so the server can return any content type
     * (PDF, CSV, …) without triggering JSON parsing.
     *
     * @param array<string, mixed> $extra
     * @return array<string, mixed>
     */
    private function headersRaw(array $extra = []): array
    {
        return array_merge([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->config->getToken(),
                'Content-Type'  => 'application/json',
            ],
        ], $extra);
    }

    /**
     * Wraps a Guzzle request with rate-limit / server-error retry logic.
     *
     * @param callable $callable
     * @param bool     $retryServerErrors Whether 5xx may be retried — pass false
     *                                    for non-idempotent requests (POST).
     */
    private function withRetry(callable $callable, bool $retryServerErrors = true): \Psr\Http\Message\ResponseInterface
    {
        $limiter = new RateLimiter($this->config->getMaxRetries());

        try {
            return $limiter->execute($callable, $retryServerErrors);
            // PHPStan cannot see through the $callable closure that Guzzle throws these,
            // so it reports the catches as dead — they are not. Guzzle raises
            // ConnectException on network failure and RequestException on transport
            // errors at runtime, even with http_errors disabled.
        } catch (ConnectException $e) { // @phpstan-ignore catch.neverThrown
            // Do not include $e->getMessage() directly — it can contain the full URL
            // (including path segments that may reveal internal tenant/token info).
            throw new DocbeeApiException(
                message:  'Connection to Docbee API failed. Check network connectivity and tenant configuration.',
                previous: $e,
            );
        } catch (RequestException $e) { // @phpstan-ignore catch.neverThrown
            throw new DocbeeApiException(
                message:  'HTTP request to Docbee API failed.',
                previous: $e,
            );
        }
    }

    /** Returns elapsed milliseconds since a hrtime() snapshot. */
    private function elapsed(int $start): int
    {
        return (int) ((hrtime(true) - $start) / 1_000_000);
    }
}
