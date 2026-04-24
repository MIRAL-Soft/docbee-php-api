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
 *   DOCBEE_TEST_CUSTOMER_ID              — enables find($id) test for Customer
 *   DOCBEE_TEST_CUSTOMER_FILTER_ID       — enables findByCustomer() filter tests for
 *                                          CustomerContact and CustomerLocation; verifies
 *                                          that only records belonging to this customer
 *                                          are returned (proves server-side filter works)
 *   DOCBEE_TEST_CUSTOMER_LOCATION_FILTER_ID — enables findByCustomerLocation() filter
 *                                          test for CustomerContact
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

    /**
     * Self-validating filter test — no env var needed.
     *
     * Fetches the first page of all contacts, picks the customer ID of the
     * first entry (so we know at least one contact exists for that customer),
     * then calls findByCustomer() and asserts every result belongs to that
     * customer.  This proves the server-side filter actually works.
     */
    public function testCustomerContactFindByCustomerFilterIsEffective(): void
    {
        $page = $this->callApi(fn() => $this->client->customerContacts()->list());
        $this->assertIsArray($page);
        if (empty($page)) {
            $this->markTestSkipped('No contacts in the system — cannot verify filter effectiveness.');
        }

        $customerId = $page[0]->getCustomer();
        if ($customerId === null) {
            $this->markTestSkipped('First contact has no customer ID — cannot verify filter.');
        }

        $filtered = $this->callApi(fn() => $this->client->customerContacts()->findByCustomer($customerId));
        $this->assertIsArray($filtered);
        $this->assertNotEmpty($filtered, "findByCustomer({$customerId}) returned nothing, but we know at least one contact exists.");

        foreach ($filtered as $contact) {
            $this->assertInstanceOf(CustomerContactDTO::class, $contact);
            $this->assertSame(
                $customerId,
                $contact->getCustomer(),
                "Filter broken: contact ID {$contact->getId()} belongs to customer {$contact->getCustomer()}, expected {$customerId}."
            );
        }

        // Filtered count must be ≤ total count (sanity check)
        $total = $this->callApi(fn() => $this->client->customerContacts()->count());
        $this->assertLessThanOrEqual($total, count($filtered));
    }

    /**
     * Verifies findByCustomer() for a specific configured customer ID.
     * Set DOCBEE_TEST_CUSTOMER_FILTER_ID to a customer that is known to have
     * at least one contact. If the customer has no contacts the test is skipped.
     */
    public function testCustomerContactFindByCustomerWithConfiguredId(): void
    {
        $customerId = $this->optionalIntEnv('DOCBEE_TEST_CUSTOMER_FILTER_ID');
        if ($customerId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_FILTER_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->customerContacts()->findByCustomer($customerId));
        $this->assertIsArray($result);
        if (empty($result)) {
            $this->markTestSkipped("Customer {$customerId} has no contacts — pick a different DOCBEE_TEST_CUSTOMER_FILTER_ID.");
        }
        foreach ($result as $contact) {
            $this->assertInstanceOf(CustomerContactDTO::class, $contact);
            $this->assertSame(
                $customerId,
                $contact->getCustomer(),
                "Contact ID {$contact->getId()} belongs to customer {$contact->getCustomer()}, expected {$customerId}."
            );
        }
    }

    /**
     * Verifies that findByCustomerLocation() returns only contacts for the given
     * customer location — i.e. the server-side filter is actually applied.
     */
    public function testCustomerContactFindByCustomerLocationReturnsOnlyMatchingContacts(): void
    {
        $locationId = $this->optionalIntEnv('DOCBEE_TEST_CUSTOMER_LOCATION_FILTER_ID');
        if ($locationId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_LOCATION_FILTER_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->customerContacts()->findByCustomerLocation($locationId));
        $this->assertIsArray($result);
        if (empty($result)) {
            $this->markTestSkipped("Location {$locationId} has no contacts — pick a different DOCBEE_TEST_CUSTOMER_LOCATION_FILTER_ID.");
        }
        foreach ($result as $contact) {
            $this->assertInstanceOf(CustomerContactDTO::class, $contact);
            $this->assertSame(
                $locationId,
                $contact->getCustomerLocation(),
                "Contact ID {$contact->getId()} belongs to location {$contact->getCustomerLocation()}, expected {$locationId}."
            );
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

    /**
     * Verifies that findByCustomer() returns only locations belonging to the given
     * customer — i.e. the server-side filter is actually applied.
     * Every returned DTO must have getCustomer() === $customerId.
     */
    public function testCustomerLocationFindByCustomerReturnsOnlyMatchingLocations(): void
    {
        $customerId = $this->optionalIntEnv('DOCBEE_TEST_CUSTOMER_FILTER_ID');
        if ($customerId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_FILTER_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->customerLocations()->findByCustomer($customerId));
        $this->assertIsArray($result);
        if (empty($result)) {
            $this->markTestSkipped("Customer {$customerId} has no locations — pick a different DOCBEE_TEST_CUSTOMER_FILTER_ID.");
        }
        foreach ($result as $location) {
            $this->assertInstanceOf(CustomerLocationDTO::class, $location);
            $this->assertSame(
                $customerId,
                $location->getCustomer(),
                "Location ID {$location->getId()} belongs to customer {$location->getCustomer()}, expected {$customerId}."
            );
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
