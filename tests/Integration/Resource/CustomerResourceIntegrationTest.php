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
        $result = $this->callApi(fn() => $this->client->customers()->list());
        $this->assertIsArray($result);
    }

    public function testCustomerListItemsAreCustomerDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customers()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerDTO::class, $item);
        }
    }

    public function testCustomerFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_CUSTOMER_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->customers()->find($id));
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── CustomerContact ───────────────────────────────────────────────────────

    public function testCustomerContactListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customerContacts()->list());
        $this->assertIsArray($result);
    }

    public function testCustomerContactListItemsAreCustomerContactDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customerContacts()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerContactDTO::class, $item);
        }
    }

    // ── CustomerLocation ──────────────────────────────────────────────────────

    public function testCustomerLocationListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customerLocations()->list());
        $this->assertIsArray($result);
    }

    public function testCustomerLocationListItemsAreCustomerLocationDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customerLocations()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerLocationDTO::class, $item);
        }
    }

    // ── CustomerObject ────────────────────────────────────────────────────────

    public function testCustomerObjectListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customerObjects()->list());
        $this->assertIsArray($result);
    }

    public function testCustomerObjectListItemsAreCustomerObjectDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customerObjects()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerObjectDTO::class, $item);
        }
    }

    // ── CustomerProfile ───────────────────────────────────────────────────────

    public function testCustomerProfileListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customerProfiles()->list());
        $this->assertIsArray($result);
    }

    public function testCustomerProfileListItemsAreCustomerProfileDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customerProfiles()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerProfileDTO::class, $item);
        }
    }

    // ── CustomerStatus ────────────────────────────────────────────────────────

    public function testCustomerStatusListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customerStatuses()->list());
        $this->assertIsArray($result);
    }

    public function testCustomerStatusListItemsAreCustomerStatusDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customerStatuses()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerStatusDTO::class, $item);
        }
    }

    // ── CustomerUser ──────────────────────────────────────────────────────────

    public function testCustomerUserListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customerUsers()->list());
        $this->assertIsArray($result);
    }

    public function testCustomerUserListItemsAreCustomerUserDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customerUsers()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomerUserDTO::class, $item);
        }
    }
}
