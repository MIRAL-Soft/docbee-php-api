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
 */
final class CustomerContactResource extends AbstractResource
{
    protected string $endpoint = 'customercontact';
    protected string $dtoClass = CustomerContactDTO::class;
    protected string $listKey  = 'customerContact';

    /**
     * Returns all contacts belonging to a customer.
     *
     * @return list<CustomerContactDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('customer', $customerId));
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
}
