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
 * // Find a customer by ID
 * $customer = $resource->find(42);
 *
 * // Find by ERP customer number
 * $customer = $resource->findByCustomerNumber('K-10042');
 *
 * // Delta-sync: get all customers changed in the last hour
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
     * Finds a customer by their ERP customer number (e.g. "K-10042").
     *
     * @throws NotFoundException when no match is found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomerNumber(string $customerNumber): CustomerDTO
    {
        $query    = QueryBuilder::new()->filterEq('customerId', $customerNumber)->limit(1);
        $results  = $this->list($query);

        if (empty($results)) {
            throw new NotFoundException(
                message:    "Customer with number '{$customerNumber}' not found.",
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
        $query = QueryBuilder::new()->filterIlike('name', "%{$name}%");
        return $this->list($query);
    }

    /**
     * Finds customers by exact email address.
     *
     * @return list<CustomerDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByEmail(string $email): array
    {
        $query = QueryBuilder::new()->filterEq('email', $email);
        return $this->list($query);
    }

    /**
     * Returns only active customers.
     *
     * @return list<CustomerDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findActive(): array
    {
        return $this->list(QueryBuilder::new()->filterEq('active', true));
    }
}
