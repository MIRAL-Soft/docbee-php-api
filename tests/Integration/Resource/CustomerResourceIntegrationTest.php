<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\CustomerDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for CustomerResource — read-only.
 *
 * Optional env var:
 *   DOCBEE_TEST_CUSTOMER_ID  — when set, the find($id) test is executed.
 */
final class CustomerResourceIntegrationTest extends IntegrationTestCase
{
    /**
     * list() should return an array (may be empty on a fresh tenant).
     */
    public function testListReturnsArray(): void
    {
        $result = $this->client->customers()->list();

        $this->assertIsArray($result);
    }

    /**
     * Every item returned by list() must be a CustomerDTO instance.
     */
    public function testListItemsAreCustomerDTOs(): void
    {
        $result = $this->client->customers()->list();

        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerDTO::class, $item);
        }
    }

    /**
     * Each CustomerDTO from list() must have a positive integer ID.
     */
    public function testListItemsHavePositiveId(): void
    {
        $result = $this->client->customers()->list();

        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerDTO::class, $item);
            $this->assertIsInt($item->getId());
            $this->assertGreaterThan(0, $item->getId());
        }
    }

    /**
     * find($id) must return a CustomerDTO with the requested ID.
     * Skipped when DOCBEE_TEST_CUSTOMER_ID is not configured.
     */
    public function testFindByIdReturnsCorrectDTO(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_CUSTOMER_ID');

        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_ID in tests/.env.test to enable this test.');
        }

        $customer = $this->client->customers()->find($id);

        $this->assertInstanceOf(CustomerDTO::class, $customer);
        $this->assertSame($id, $customer->getId());
    }
}
