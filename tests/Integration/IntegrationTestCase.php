<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration;

use miralsoft\docbee\api\Client\DocbeeClient;
use miralsoft\docbee\api\Config\DocbeeConfig;
use PHPUnit\Framework\TestCase;

/**
 * Base class for all integration tests.
 *
 * Reads credentials from environment variables (loaded from tests/.env.test
 * by the PHPUnit bootstrap). If DOCBEE_TENANT or DOCBEE_TOKEN are not set,
 * every test in the subclass is automatically skipped.
 *
 * Only read-only API calls (GET) should be made from integration tests so
 * that live data is never modified accidentally.
 */
abstract class IntegrationTestCase extends TestCase
{
    protected DocbeeClient $client;

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
}
