<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee customer records.
 *
 * ```php
 * $resource = $client->customers();
 *
 * // Search by name OR customer number (the display number shown in the Docbee UI header)
 * $customers = $resource->search('Testfirma');
 * $customers = $resource->search('12355');  // finds customer with display number 12355
 *
 * // Find a customer by their ERP customer ID (throws NotFoundException if absent)
 * $customer = $resource->findByCustomerId('K-10042');
 *
 * // Check whether a customer already exists in Docbee (returns null if absent)
 * $customer = $resource->findOneByCustomerId('K-10042');
 * if ($customer !== null) {
 *     // already imported — use $customer->getId() for API relations
 * }
 *
 * // Delta-sync: all customers changed in the last hour
 * $changed = $resource->findModifiedSince(new DateTimeImmutable('-1 hour'));
 * ```
 *
 * @note The customer number displayed in the Docbee UI (e.g. "12355" next to the customer
 *       name) is NOT the internal database ID used by the REST API.  Use {@see search()}
 *       to resolve a customer number to the internal ID, or to find a customer by name.
 *
 * @extends AbstractResource<CustomerDTO>
 */
final class CustomerResource extends AbstractResource
{
    protected string $endpoint = 'customer';
    protected string $dtoClass = CustomerDTO::class;
    protected string $listKey  = 'customer';

    /**
     * Finds a customer by their ERP customer ID (e.g. "K-10042").
     *
     * Uses the plain `customerId=<id>` query parameter — the standard
     * `customerId-eq=<id>` operator form is silently ignored by the Docbee API
     * even for scalar fields on this endpoint.
     *
     * @throws NotFoundException when no match is found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomerId(string $customerId): CustomerDTO
    {
        $results = $this->list(QueryBuilder::new()->param('customerId', $customerId)->limit(1));

        if (empty($results)) {
            throw new NotFoundException(
                message:    "Customer with customerId '{$customerId}' not found.",
                statusCode: 404,
                requestUrl: $this->endpoint,
            );
        }

        return $results[0];
    }

    /**
     * Finds a customer by their ERP customer ID, returning null if not found.
     *
     * Use this instead of {@see findByCustomerId()} when checking whether a
     * customer already exists in Docbee — for example, during an import from an
     * external ERP system where the customer may or may not have been created yet.
     *
     * Uses the plain `customerId=<id>` query parameter — the standard
     * `customerId-eq=<id>` operator form is silently ignored by the Docbee API
     * even for scalar fields on this endpoint.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findOneByCustomerId(string $customerId): ?CustomerDTO
    {
        $results = $this->list(QueryBuilder::new()->param('customerId', $customerId)->limit(1));

        return $results[0] ?? null;
    }

    /**
     * Finds customers whose name contains the given string (case-insensitive).
     *
     * @deprecated Use {@see search()} instead — it matches both name and customer number
     *             and uses the native Docbee `search` parameter.
     * @return list<CustomerDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): array
    {
        return $this->search($name);
    }

    /**
     * Finds customers by their customerStatus ID.
     *
     * @return list<CustomerDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomerStatus(int $customerStatusId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('customerStatus', $customerStatusId));
    }

    /** Get all custom fields for customers. */
    public function getCustomFields(): array
    {
        return $this->http->get("{$this->endpoint}/customFields");
    }

    /** Update custom field configuration. */
    public function updateCustomFields(array $data): array
    {
        return $this->http->put("{$this->endpoint}/customFields", $data);
    }

    /** Guess/match customers. */
    public function guess(array $data): array
    {
        return $this->http->post("{$this->endpoint}/guess", $data);
    }

    /** Export customers for the given export profile. */
    public function export(int $exportProfileId): array
    {
        return $this->http->get("{$this->endpoint}/export/{$exportProfileId}");
    }

    /** Export specific customers (by IDs) for the given export profile. */
    public function exportByIds(int $exportProfileId, array $ids): array
    {
        return $this->http->post("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids);
    }
}
