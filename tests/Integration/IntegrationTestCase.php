<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration;

use miralsoft\docbee\api\Client\DocbeeClient;
use miralsoft\docbee\api\Config\DocbeeConfig;
use miralsoft\docbee\api\Exception\AuthenticationException;
use miralsoft\docbee\api\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Base class for all integration tests.
 *
 * Reads credentials from environment variables (loaded from tests/.env.test
 * by the PHPUnit bootstrap). If DOCBEE_TENANT or DOCBEE_TOKEN are not set,
 * every test in the subclass is automatically skipped.
 *
 * HTTP 403 responses indicate that the feature exists but the API token does
 * not have sufficient permissions on this tenant → test is skipped.
 * HTTP 404 responses indicate that the endpoint does not exist on this tenant
 * (optional Docbee module not licensed) → test is skipped.
 *
 * Only read-only API calls (GET) should be made from integration tests so
 * that live data is never modified accidentally.
 */
abstract class IntegrationTestCase extends TestCase
{
    protected DocbeeClient $client;

    /**
     * Wraps an API call so that 403 and 404 responses convert to skipped tests
     * instead of failures. Use this for every live API call in subclass tests.
     *
     * - HTTP 403 = feature not licensed or API token lacks permissions on this tenant.
     * - HTTP 404 = endpoint does not exist on this tenant (optional Docbee module).
     */
    protected function callApi(callable $fn): mixed
    {
        try {
            return $fn();
        } catch (AuthenticationException) {
            $this->markTestSkipped(
                'API returned HTTP 403 — feature not available or insufficient permissions on this tenant.'
            );
        } catch (NotFoundException) {
            $this->markTestSkipped(
                'API returned HTTP 404 — endpoint not available on this tenant (optional module not licensed).'
            );
        }
    }

    protected function setUp(): void
    {
        $tenant = (string) (getenv('DOCBEE_TENANT') ?: '');
        $token  = (string) (getenv('DOCBEE_TOKEN')  ?: '');

        if ($tenant === '' || $token === '') {
            $this->markTestSkipped(
                'Integration tests require tests/.env.test with DOCBEE_TENANT and DOCBEE_TOKEN. ' .
                'Copy tests/.env.test.example to tests/.env.test and fill in your credentials.'
            );
        }

        $config       = new DocbeeConfig(tenant: $tenant, token: $token);
        $this->client = new DocbeeClient($config);
    }

    /**
     * Returns the value of an optional env var, or null if not set / empty.
     * Used by subclasses to conditionally run find($id) assertions.
     */
    protected function optionalIntEnv(string $name): ?int
    {
        $value = getenv($name);

        return ($value !== false && $value !== '') ? (int) $value : null;
    }

    /**
     * Calls $callable and returns the result.
     * If the API responds with 403 (feature not licensed / no permission on this tenant),
     * the test is skipped instead of failing.
     * If the API responds with 404 (endpoint does not exist on this tenant),
     * the test is also skipped — some Docbee modules are optional.
     */
    protected function apiCall(callable $callable): mixed
    {
        try {
            return $callable();
        } catch (AuthenticationException $e) {
            $this->markTestSkipped('API returned 403 — feature not available or insufficient permissions on this tenant.');
        } catch (NotFoundException $e) {
            $this->markTestSkipped('API returned 404 — endpoint not available on this tenant (optional module).');
        }
    }
}
