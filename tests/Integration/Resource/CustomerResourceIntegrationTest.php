<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\CustomerContactDTO;
use miralsoft\docbee\api\DTO\CustomerDTO;
use miralsoft\docbee\api\DTO\CustomerLocationDTO;
use miralsoft\docbee\api\DTO\CustomerObjectDTO;
use miralsoft\docbee\api\DTO\CustomerProfileDTO;
use miralsoft\docbee\api\DTO\CustomerStatusDTO;
use miralsoft\docbee\api\DTO\CustomerUserDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for customer-related resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_CUSTOMER_ID — enables find($id) test for Customer
 */
final class CustomerResourceIntegrationTest extends IntegrationTestCase
{
    // ── Customer ──────────────────────────────────────────────────────────────

    public function testCustomerListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customers()->list());
    }

    public function testCustomerListItemsAreCustomerDTOs(): void
    {
        foreach ($this->client->customers()->list() as $item) {
            $this->assertInstanceOf(CustomerDTO::class, $item);
        }
    }

    public function testCustomerFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_CUSTOMER_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->customers()->find($id);
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── CustomerContact ───────────────────────────────────────────────────────

    public function testCustomerContactListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customerContacts()->list());
    }

    public function testCustomerContactListItemsAreCustomerContactDTOs(): void
    {
        foreach ($this->client->customerContacts()->list() as $item) {
            $this->assertInstanceOf(CustomerContactDTO::class, $item);
        }
    }

    // ── CustomerLocation ──────────────────────────────────────────────────────

    public function testCustomerLocationListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customerLocations()->list());
    }

    public function testCustomerLocationListItemsAreCustomerLocationDTOs(): void
    {
        foreach ($this->client->customerLocations()->list() as $item) {
            $this->assertInstanceOf(CustomerLocationDTO::class, $item);
        }
    }

    // ── CustomerObject ────────────────────────────────────────────────────────

    public function testCustomerObjectListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customerObjects()->list());
    }

    public function testCustomerObjectListItemsAreCustomerObjectDTOs(): void
    {
        foreach ($this->client->customerObjects()->list() as $item) {
            $this->assertInstanceOf(CustomerObjectDTO::class, $item);
        }
    }

    // ── CustomerProfile ───────────────────────────────────────────────────────

    public function testCustomerProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customerProfiles()->list());
    }

    public function testCustomerProfileListItemsAreCustomerProfileDTOs(): void
    {
        foreach ($this->client->customerProfiles()->list() as $item) {
            $this->assertInstanceOf(CustomerProfileDTO::class, $item);
        }
    }

    // ── CustomerStatus ────────────────────────────────────────────────────────

    public function testCustomerStatusListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customerStatuses()->list());
    }

    public function testCustomerStatusListItemsAreCustomerStatusDTOs(): void
    {
        foreach ($this->client->customerStatuses()->list() as $item) {
            $this->assertInstanceOf(CustomerStatusDTO::class, $item);
        }
    }

    // ── CustomerUser ──────────────────────────────────────────────────────────

    public function testCustomerUserListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customerUsers()->list());
    }

    public function testCustomerUserListItemsAreCustomerUserDTOs(): void
    {
        foreach ($this->client->customerUsers()->list() as $item) {
            $this->assertInstanceOf(CustomerUserDTO::class, $item);
        }
    }
}
