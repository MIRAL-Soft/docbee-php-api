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
 * // Find a customer by their ERP customer ID
 * $customer = $resource->findByCustomerId('K-10042');
 *
 * // Find by name (partial, case-insensitive)
 * $customers = $resource->findByName('Acme');
 *
 * // Delta-sync: all customers changed in the last hour
 * $changed = $resource->findModifiedSince(new DateTimeImmutable('-1 hour'));
 * ```
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
     * @throws NotFoundException when no match is found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomerId(string $customerId): CustomerDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('customerId', $customerId)->limit(1));

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
     * Finds customers whose name contains the given string (case-insensitive).
     *
     * @return list<CustomerDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): array
    {
        return $this->list(QueryBuilder::new()->filterIlike('name', "%{$name}%"));
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
