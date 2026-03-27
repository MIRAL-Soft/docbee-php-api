<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Client;

use miralsoft\docbee\api\Exception\RateLimitException;
use miralsoft\docbee\api\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * Wraps API callables with automatic retry logic.
 *
 * Retries on HTTP 429 (rate limit) and 5xx (server error) responses using
 * exponential backoff. Respects the `Retry-After` header when present.
 *
 * ```php
 * $limiter = new RateLimiter(maxRetries: 3, baseDelayMs: 1000);
 * $response = $limiter->execute(fn() => $guzzle->get('/ticket'));
 * ```
 */
final class RateLimiter
{
    /** Maximum delay cap in milliseconds (5 minutes). */
    private const MAX_DELAY_MS = 300_000;

    public function __construct(
        private readonly int $maxRetries  = 3,
        private readonly int $baseDelayMs = 1_000,
    ) {}

    /**
     * Executes a callable that returns a {@see ResponseInterface} with automatic retries.
     *
     * @param callable(): ResponseInterface $callable
     * @throws RateLimitException when 429 persists after all retries.
     * @throws ServerException    when 5xx persists after all retries.
     */
    public function execute(callable $callable): ResponseInterface
    {
        $attempt = 0;

        while (true) {
            /** @var ResponseInterface $response */
            $response   = $callable();
            $statusCode = $response->getStatusCode();

            if ($statusCode !== 429 && $statusCode < 500) {
                // Success or a non-retryable client error – return immediately.
                return $response;
            }

            if ($attempt >= $this->maxRetries) {
                if ($statusCode === 429) {
                    throw new RateLimitException(
                        message:    "Rate limit exceeded after {$this->maxRetries} retries.",
                        statusCode: $statusCode,
                    );
                }
                throw new ServerException(
                    message:    "Server error {$statusCode} after {$this->maxRetries} retries.",
                    statusCode: $statusCode,
                );
            }

            $delayMs = $this->resolveDelay($response, $attempt);
            usleep($delayMs * 1_000);
            $attempt++;
        }
    }

    /**
     * Determines the delay in milliseconds before the next retry.
     *
     * Uses the server-supplied `Retry-After` value (in seconds) if present,
     * otherwise applies exponential backoff.
     */
    private function resolveDelay(ResponseInterface $response, int $attempt): int
    {
        $retryAfter = $response->getHeaderLine('Retry-After');

        if ($retryAfter !== '' && is_numeric($retryAfter)) {
            // Server-supplied delay takes precedence, but is capped.
            $serverDelayMs = (int) $retryAfter * 1_000;
            $backoffMs     = $this->baseDelayMs * (2 ** $attempt);
            return min(max($serverDelayMs, $backoffMs), self::MAX_DELAY_MS);
        }

        // Pure exponential backoff: 1 s → 2 s → 4 s …
        return min($this->baseDelayMs * (2 ** $attempt), self::MAX_DELAY_MS);
    }
}
