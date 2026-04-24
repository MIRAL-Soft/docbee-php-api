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
 * Optional env vars (all accept the customer number displayed in the Docbee UI,
 * NOT the internal database ID — the tests resolve the display number to the
 * internal ID automatically via CustomerResource::search()):
 *
 *   DOCBEE_TEST_CUSTOMER_ID              — customer number; enables find() test for Customer
 *   DOCBEE_TEST_CUSTOMER_FILTER_ID       — customer number; enables findByCustomer() filter
 *                                          tests for CustomerContact and CustomerLocation
 *   DOCBEE_TEST_CUSTOMER_LOCATION_FILTER_ID — internal location ID; enables
 *                                          findByCustomerLocation() filter test
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
        $customerNumber = $this->optionalStringEnv('DOCBEE_TEST_CUSTOMER_ID');
        if ($customerNumber === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_ID (customer number) in tests/.env.test to enable.');
        }
        $internalId = $this->resolveCustomerInternalId($customerNumber);
        $dto = $this->callApi(fn() => $this->client->customers()->find($internalId));
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame($internalId, $dto->getId());
    }

    /**
     * Self-validating search test — no env var needed.
     *
     * Picks the first customer from the list, then searches by name to verify
     * that search() returns at least that customer (and only CustomerDTOs).
     */
    public function testCustomerSearchByNameReturnsMatchingDTOs(): void
    {
        $page = $this->callApi(fn() => $this->client->customers()->list());
        $this->assertIsArray($page);
        if (empty($page)) {
            $this->markTestSkipped('No customers in the system — cannot verify search.');
        }

        $name = $page[0]->getName();
        if ($name === null) {
            $this->markTestSkipped('First customer has no name — cannot verify search.');
        }

        $results = $this->callApi(fn() => $this->client->customers()->search($name));
        $this->assertIsArray($results);
        $this->assertNotEmpty($results, "search('{$name}') returned nothing, but we know this customer exists.");

        foreach ($results as $item) {
            $this->assertInstanceOf(CustomerDTO::class, $item);
        }

        // The searched customer must be among the results
        $ids = array_map(fn($c) => $c->getId(), $results);
        $this->assertContains(
            $page[0]->getId(),
            $ids,
            "search('{$name}') did not include the customer we searched for (ID {$page[0]->getId()})."
        );
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
     * Verifies findByCustomer() for a specific configured customer number.
     * Set DOCBEE_TEST_CUSTOMER_FILTER_ID to the customer number shown in the Docbee
     * UI (e.g. "12355"). The test resolves it to the internal ID automatically.
     * Skipped if the customer has no contacts.
     */
    public function testCustomerContactFindByCustomerWithConfiguredId(): void
    {
        $customerNumber = $this->optionalStringEnv('DOCBEE_TEST_CUSTOMER_FILTER_ID');
        if ($customerNumber === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_FILTER_ID (customer number) in tests/.env.test to enable.');
        }
        $internalId = $this->resolveCustomerInternalId($customerNumber);
        $result = $this->callApi(fn() => $this->client->customerContacts()->findByCustomer($internalId));
        $this->assertIsArray($result);
        if (empty($result)) {
            $this->markTestSkipped("Customer number {$customerNumber} (ID {$internalId}) has no contacts — pick a different DOCBEE_TEST_CUSTOMER_FILTER_ID.");
        }
        foreach ($result as $contact) {
            $this->assertInstanceOf(CustomerContactDTO::class, $contact);
            $this->assertSame(
                $internalId,
                $contact->getCustomer(),
                "Contact ID {$contact->getId()} belongs to customer {$contact->getCustomer()}, expected {$internalId} (customer number {$customerNumber})."
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
     * Set DOCBEE_TEST_CUSTOMER_FILTER_ID to the customer number shown in the Docbee UI.
     */
    public function testCustomerLocationFindByCustomerReturnsOnlyMatchingLocations(): void
    {
        $customerNumber = $this->optionalStringEnv('DOCBEE_TEST_CUSTOMER_FILTER_ID');
        if ($customerNumber === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CUSTOMER_FILTER_ID (customer number) in tests/.env.test to enable.');
        }
        $internalId = $this->resolveCustomerInternalId($customerNumber);
        $result = $this->callApi(fn() => $this->client->customerLocations()->findByCustomer($internalId));
        $this->assertIsArray($result);
        if (empty($result)) {
            $this->markTestSkipped("Customer number {$customerNumber} (ID {$internalId}) has no locations — pick a different DOCBEE_TEST_CUSTOMER_FILTER_ID.");
        }
        foreach ($result as $location) {
            $this->assertInstanceOf(CustomerLocationDTO::class, $location);
            $this->assertSame(
                $internalId,
                $location->getCustomer(),
                "Location ID {$location->getId()} belongs to customer {$location->getCustomer()}, expected {$internalId} (customer number {$customerNumber})."
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

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Resolves a customer number (the display number shown in the Docbee UI, e.g.
     * "12355") to the customer's internal database ID via CustomerResource::search().
     *
     * Marks the calling test as skipped when the customer number yields no result.
     */
    private function resolveCustomerInternalId(string $customerNumber): int
    {
        $matches = $this->callApi(fn() => $this->client->customers()->search($customerNumber));

        if (empty($matches)) {
            $this->markTestSkipped(
                "Customer number \"{$customerNumber}\" not found — check DOCBEE_TEST_CUSTOMER_FILTER_ID in tests/.env.test."
            );
        }

        return $matches[0]->getId();
    }
}
