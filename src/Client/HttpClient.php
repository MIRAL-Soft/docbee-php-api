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
            'http_errors'     => false, // We handle errors ourselves.
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

        if ($response->getStatusCode() >= 400) {
            ResponseParser::throw(
                statusCode:      $response->getStatusCode(),
                responseBody:    (string) $response->getBody(),
                requestUrl:      $url,
                responseHeaders: $response->getHeaders(),
            );
        }

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
        $response = $this->withRetry(
            fn() => $this->guzzle->post($url, $this->headers(['json' => $data]))
        );
        $ms = $this->elapsed($start);

        $this->logger->info("POST {$url} → {$response->getStatusCode()} ({$ms} ms)");

        if ($response->getStatusCode() >= 400) {
            ResponseParser::throw(
                statusCode:      $response->getStatusCode(),
                responseBody:    (string) $response->getBody(),
                requestUrl:      $url,
                responseHeaders: $response->getHeaders(),
            );
        }

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

        if ($response->getStatusCode() >= 400) {
            ResponseParser::throw(
                statusCode:      $response->getStatusCode(),
                responseBody:    (string) $response->getBody(),
                requestUrl:      $url,
                responseHeaders: $response->getHeaders(),
            );
        }

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

        if ($response->getStatusCode() >= 400) {
            ResponseParser::throw(
                statusCode:      $response->getStatusCode(),
                responseBody:    (string) $response->getBody(),
                requestUrl:      $url,
                responseHeaders: $response->getHeaders(),
            );
        }
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

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
     * Wraps a Guzzle request with rate-limit / server-error retry logic.
     *
     * @param callable $callable
     */
    private function withRetry(callable $callable): \Psr\Http\Message\ResponseInterface
    {
        $limiter = new RateLimiter($this->config->getMaxRetries());

        try {
            return $limiter->execute($callable);
        } catch (ConnectException $e) {
            throw new DocbeeApiException(
                message:  'Connection to Docbee API failed: ' . $e->getMessage(),
                previous: $e,
            );
        } catch (RequestException $e) {
            throw new DocbeeApiException(
                message:  'HTTP request to Docbee API failed: ' . $e->getMessage(),
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
