<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerContactDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee customer contact records.
 *
 * ```php
 * $contacts = $client->customerContacts()->findByCustomer(42);
 * ```
 *
 * @extends AbstractResource<CustomerContactDTO>
 *
 * @note The Docbee API accepts `customer=<id>` (plain parameter) for this endpoint,
 *       NOT the standard `customer-eq=<id>` operator form.  Using filterEq('customer', ...)
 *       via QueryBuilder silently returns all records.  Use findByCustomer() or
 *       QueryBuilder::param('customer', $id) instead.
 * @note The Docbee API interprets `id-eq` as a foreign-key (customer ID) filter
 *       for this resource, not as a primary-key filter. To fetch a single record
 *       by its own ID, use find(int $id) instead of list(filterEq('id', ...)).
 */
final class CustomerContactResource extends AbstractResource
{
    protected string $endpoint = 'customerContact';
    protected string $dtoClass = CustomerContactDTO::class;
    protected string $listKey  = 'customerContact';

    /**
     * Returns all contacts belonging to a customer.
     *
     * Uses the plain `customer=<id>` query parameter required by this endpoint
     * (the standard `customer-eq=<id>` operator form is silently ignored by the API).
     *
     * @return list<CustomerContactDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->listAll(QueryBuilder::new()->param('customer', $customerId));
    }

    /**
     * Returns all contacts belonging to a specific customer location.
     *
     * Uses the plain `customerLocation=<id>` query parameter required by this endpoint.
     *
     * @return list<CustomerContactDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomerLocation(int $customerLocationId): array
    {
        return $this->listAll(QueryBuilder::new()->param('customerLocation', $customerLocationId));
    }

    /**
     * Finds a contact by email address.
     *
     * @return list<CustomerContactDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByEmail(string $email): array
    {
        return $this->list(QueryBuilder::new()->filterEq('email', $email));
    }

    /**
     * Finds contacts whose name contains the given string (case-insensitive).
     *
     * @return list<CustomerContactDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): array
    {
        return $this->list(QueryBuilder::new()->filterIlike('name', "%{$name}%"));
    }

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
    public function move(int $id, array $data): CustomerContactDTO { return CustomerContactDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/move", $data)); }
    public function export(int $exportProfileId): string { return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): string { return $this->http->postRaw("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids); }
}
