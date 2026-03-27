<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerLocationDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee customer location (branch/site) records.
 *
 * @extends AbstractResource<CustomerLocationDTO>
 */
final class CustomerLocationResource extends AbstractResource
{
    protected string $endpoint = 'customerlocation';
    protected string $dtoClass = CustomerLocationDTO::class;
    protected string $listKey  = 'customerLocation';

    /**
     * Returns all locations belonging to a customer.
     *
     * @return list<CustomerLocationDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('customer', $customerId));
    }
}
