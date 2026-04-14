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

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
