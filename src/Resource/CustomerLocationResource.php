<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerLocationDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee customer location (branch/site) records.
 *
 * @extends AbstractResource<CustomerLocationDTO>
 *
 * @note The Docbee API accepts `customer=<id>` (plain parameter) for this endpoint,
 *       NOT the standard `customer-eq=<id>` operator form.  Using filterEq('customer', ...)
 *       via QueryBuilder silently returns all records.  Use findByCustomer() or
 *       QueryBuilder::param('customer', $id) instead.
 */
final class CustomerLocationResource extends AbstractResource
{
    protected string $endpoint = 'customerLocation';
    protected string $dtoClass = CustomerLocationDTO::class;
    protected string $listKey  = 'customerLocation';

    /**
     * Returns all locations belonging to a customer, including address fields.
     *
     * Uses the plain `customer=<id>` query parameter required by this endpoint
     * (the standard `customer-eq=<id>` operator form is silently ignored by the API).
     *
     * Address fields (`street`, `city`, `zipcode`) are requested explicitly because
     * the Docbee API omits them from list responses unless `fields` is specified.
     *
     * @return list<CustomerLocationDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->listAll(
            QueryBuilder::new()
                ->param('customer', $customerId)
                ->fields(['id', 'customer', 'name', 'street', 'city', 'zipcode'])
        );
    }

    /** @return array<string, mixed> */
    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
