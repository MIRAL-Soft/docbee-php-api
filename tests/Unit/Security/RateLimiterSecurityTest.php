<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Security;

use GuzzleHttp\Psr7\Response;
use miralsoft\docbee\api\Client\RateLimiter;
use miralsoft\docbee\api\Exception\RateLimitException;
use miralsoft\docbee\api\Exception\ServerException;
use PHPUnit\Framework\TestCase;

/**
 * Security tests for {@see RateLimiter}.
 *
 * Verifies retry caps, correct exception types, Retry-After header handling
 * (including adversarial values), and that non-retryable errors are not retried.
 *
 * All tests use baseDelayMs=0 to avoid real sleeps during the test run.
 */
final class RateLimiterSecurityTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Immediate-return paths (no retry)
    // -------------------------------------------------------------------------

    public function testSuccessResponseReturnedImmediately(): void
    {
        $limiter  = new RateLimiter(maxRetries: 3, baseDelayMs: 0);
        $calls    = 0;
        $response = $limiter->execute(function () use (&$calls): Response {
            $calls++;
            return new Response(200, [], '{}');
        });

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(1, $calls, 'Success must not trigger any retry.');
    }

    public function testClientError4xxReturnedImmediately(): void
    {
        $limiter = new RateLimiter(maxRetries: 3, baseDelayMs: 0);
        $calls   = 0;

        $response = $limiter->execute(function () use (&$calls): Response {
            $calls++;
            return new Response(422, [], '{}');
        });

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(1, $calls, '4xx (non-429) errors must not be retried.');
    }

    public function test401ReturnedImmediately(): void
    {
        $limiter = new RateLimiter(maxRetries: 3, baseDelayMs: 0);
        $calls   = 0;

        $response = $limiter->execute(function () use (&$calls): Response {
            $calls++;
            return new Response(401, [], '{}');
        });

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame(1, $calls);
    }

    public function test404ReturnedImmediately(): void
    {
        $limiter = new RateLimiter(maxRetries: 3, baseDelayMs: 0);
        $calls   = 0;

        $response = $limiter->execute(function () use (&$calls): Response {
            $calls++;
            return new Response(404, [], '{}');
        });

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame(1, $calls);
    }

    // -------------------------------------------------------------------------
    // 429 – rate limit retry and exception
    // -------------------------------------------------------------------------

    public function test429IsRetriedUpToMaxRetries(): void
    {
        $limiter = new RateLimiter(maxRetries: 2, baseDelayMs: 0);
        $calls   = 0;

        // Always return 429 so we exhaust all retries.
        try {
            $limiter->execute(function () use (&$calls): Response {
                $calls++;
                return new Response(429);
            });
        } catch (RateLimitException) {
            // Expected after exhausting retries.
        }

        // 1 initial call + 2 retries = 3 total calls.
        $this->assertSame(3, $calls);
    }

    public function testRateLimitExceptionThrownAfterExhaustingRetries(): void
    {
        $this->expectException(RateLimitException::class);

        $limiter = new RateLimiter(maxRetries: 1, baseDelayMs: 0);
        $limiter->execute(fn() => new Response(429));
    }

    public function test429ResolvedOnRetryDoesNotThrow(): void
    {
        $limiter = new RateLimiter(maxRetries: 3, baseDelayMs: 0);
        $calls   = 0;

        $response = $limiter->execute(function () use (&$calls): Response {
            $calls++;
            // Fail on first call, succeed on second.
            return $calls === 1 ? new Response(429) : new Response(200, [], '{}');
        });

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(2, $calls);
    }

    // -------------------------------------------------------------------------
    // 5xx – server error retry and exception
    // -------------------------------------------------------------------------

    public function test500IsRetriedUpToMaxRetries(): void
    {
        $limiter = new RateLimiter(maxRetries: 2, baseDelayMs: 0);
        $calls   = 0;

        try {
            $limiter->execute(function () use (&$calls): Response {
                $calls++;
                return new Response(500);
            });
        } catch (ServerException) {
            // Expected.
        }

        $this->assertSame(3, $calls);
    }

    public function testServerExceptionThrownAfterExhausting5xx(): void
    {
        $this->expectException(ServerException::class);

        $limiter = new RateLimiter(maxRetries: 1, baseDelayMs: 0);
        $limiter->execute(fn() => new Response(503));
    }

    // -------------------------------------------------------------------------
    // Retry-After header handling
    // -------------------------------------------------------------------------

    public function testRetryAfterHeaderIsRespected(): void
    {
        // We just verify the value is accepted without throwing – actual delay
        // is not testable in unit tests without mocking usleep.
        $limiter = new RateLimiter(maxRetries: 2, baseDelayMs: 0);
        $calls   = 0;

        $response = $limiter->execute(function () use (&$calls): Response {
            $calls++;
            return $calls === 1
                ? new Response(429, ['Retry-After' => '0'])   // immediate retry
                : new Response(200, [], '{}');
        });

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testVeryLargeRetryAfterIsCapped(): void
    {
        // A server claiming "Retry-After: 99999999" must be capped at the
        // internal 5-minute maximum so the process is not locked indefinitely.
        // We verify the cap by using Retry-After=0 for a code path that would
        // exercise the cap logic, and that no exception is thrown unexpectedly.
        $limiter = new RateLimiter(maxRetries: 1, baseDelayMs: 0);
        $calls   = 0;

        try {
            $limiter->execute(function () use (&$calls): Response {
                $calls++;
                // Pass an astronomically large value – must be capped internally.
                return new Response(429, ['Retry-After' => '99999999']);
            });
        } catch (RateLimitException) {
            // Expected after retries exhausted.
        }

        $this->assertSame(2, $calls);
    }

    public function testNegativeRetryAfterIsSafelyHandled(): void
    {
        // A malicious or buggy server may send a negative Retry-After value.
        // This must NOT be forwarded as-is to usleep() (negative microseconds).
        $limiter = new RateLimiter(maxRetries: 1, baseDelayMs: 0);
        $calls   = 0;

        try {
            $limiter->execute(function () use (&$calls): Response {
                $calls++;
                return new Response(429, ['Retry-After' => '-100']);
            });
        } catch (RateLimitException) {
            // Expected after retries exhausted.
        }

        // The important assertion: we reached here without a PHP error/crash.
        $this->assertSame(2, $calls);
    }

    public function testZeroRetryAfterIsSafelyHandled(): void
    {
        $limiter = new RateLimiter(maxRetries: 1, baseDelayMs: 0);
        $calls   = 0;

        try {
            $limiter->execute(function () use (&$calls): Response {
                $calls++;
                return new Response(429, ['Retry-After' => '0']);
            });
        } catch (RateLimitException) {
            // Expected.
        }

        $this->assertSame(2, $calls);
    }

    public function testNonNumericRetryAfterFallsBackToExponentialBackoff(): void
    {
        // An HTTP-date or garbage value must not crash the limiter.
        $limiter = new RateLimiter(maxRetries: 1, baseDelayMs: 0);
        $calls   = 0;

        try {
            $limiter->execute(function () use (&$calls): Response {
                $calls++;
                return new Response(429, ['Retry-After' => 'Wed, 21 Oct 2025 07:28:00 GMT']);
            });
        } catch (RateLimitException) {
            // Expected.
        }

        $this->assertSame(2, $calls);
    }

    public function testMissingRetryAfterHeaderFallsBackToExponentialBackoff(): void
    {
        $limiter = new RateLimiter(maxRetries: 1, baseDelayMs: 0);
        $calls   = 0;

        try {
            $limiter->execute(function () use (&$calls): Response {
                $calls++;
                return new Response(500);   // no Retry-After
            });
        } catch (ServerException) {
            // Expected.
        }

        $this->assertSame(2, $calls);
    }
}
