<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomerProfile records.
 *
 * @extends AbstractResource<CustomerProfileDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class CustomerProfileResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'customerProfile';
    protected string $dtoClass = CustomerProfileDTO::class;
    protected string $listKey  = 'customerProfile';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}