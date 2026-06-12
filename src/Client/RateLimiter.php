<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Client;

use miralsoft\docbee\api\Exception\RateLimitException;
use miralsoft\docbee\api\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

/**
 * Wraps API callables with automatic retry logic.
 *
 * Retries on HTTP 429 (rate limit) and — for idempotent requests — on 5xx
 * (server error) responses using exponential backoff. Respects the
 * `Retry-After` header when present (both delta-seconds and HTTP-date forms).
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

    /**
     * @param (\Closure(int): void)|null $sleeper Optional sleep implementation, used to make
     *        retries instant in tests.  Defaults to a real sleep — production behaviour is
     *        unchanged whether or not this is supplied.
     */
    public function __construct(
        private readonly int $maxRetries  = 3,
        private readonly int $baseDelayMs = 1_000,
        private readonly ?\Closure $sleeper = null,
    ) {}

    /**
     * Executes a callable that returns a {@see ResponseInterface} with automatic retries.
     *
     * 429 responses are always retried — a rate-limited request was rejected before
     * processing, so retrying is safe for any HTTP method.
     *
     * 5xx responses are only retried when `$retryServerErrors` is true.  Pass `false`
     * for non-idempotent requests (POST): a 5xx does not guarantee the server did NOT
     * process the request — e.g. a timeout after the record was created — and an
     * automatic retry could create duplicates.
     *
     * @param callable(): ResponseInterface $callable
     * @param bool $retryServerErrors Whether 5xx responses may be retried (idempotent requests only).
     * @throws RateLimitException when 429 persists after all retries.
     * @throws ServerException    when a 5xx occurs (immediately for non-idempotent requests).
     */
    public function execute(callable $callable, bool $retryServerErrors = true): ResponseInterface
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

            if ($statusCode >= 500 && !$retryServerErrors) {
                // Non-idempotent request: the server may have partially processed it
                // (e.g. created the record before failing). Retrying risks duplicates.
                throw new ServerException(
                    message:    "Server error {$statusCode} (request not retried: non-idempotent).",
                    statusCode: $statusCode,
                );
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

            $this->sleep($this->resolveDelay($response, $attempt));
            $attempt++;
        }
    }

    /**
     * Determines the delay in milliseconds before the next retry.
     *
     * When the server provides a `Retry-After` header, that value is used directly
     * (capped at MAX_DELAY_MS) as per RFC 7231 — both the delta-seconds form
     * ("120") and the HTTP-date form ("Wed, 21 Oct 2026 07:28:00 GMT") are
     * supported. Without the header, pure exponential backoff is applied:
     * 1 s → 2 s → 4 s …
     */
    private function resolveDelay(ResponseInterface $response, int $attempt): int
    {
        $retryAfter = $response->getHeaderLine('Retry-After');

        if ($retryAfter !== '' && is_numeric($retryAfter)) {
            // max(0, …) guards against a malicious/buggy server sending a negative
            // value, which would otherwise produce a negative sleep duration.
            return max(0, min((int) $retryAfter * 1_000, self::MAX_DELAY_MS));
        }

        if ($retryAfter !== '') {
            // RFC 7231 also allows an HTTP-date. strtotime() returns false for
            // garbage, in which case we fall through to exponential backoff.
            $until = strtotime($retryAfter);
            if ($until !== false) {
                return max(0, min(($until - time()) * 1_000, self::MAX_DELAY_MS));
            }
        }

        // Pure exponential backoff: 1 s → 2 s → 4 s …
        // (int) — 2 ** $attempt is typed as float by PHPStan; the delay is always whole ms.
        return (int) min($this->baseDelayMs * (2 ** $attempt), self::MAX_DELAY_MS);
    }

    /**
     * Sleeps for the given number of milliseconds.
     *
     * Split into sleep() + usleep(): per the PHP documentation, usleep() with values
     * above one second is not guaranteed to work on all operating systems.
     */
    private function sleep(int $delayMs): void
    {
        if ($this->sleeper !== null) {
            ($this->sleeper)($delayMs);
            return;
        }

        $seconds = intdiv($delayMs, 1_000);
        $microseconds = ($delayMs % 1_000) * 1_000;

        if ($seconds > 0) {
            sleep($seconds);
        }
        if ($microseconds > 0) {
            usleep($microseconds);
        }
    }
}
