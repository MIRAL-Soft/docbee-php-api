<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ContingentItemRecurrenceDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ContingentItemRecurrence records (sub-resource).
 *
 * @extends AbstractResource<ContingentItemRecurrenceDTO>
 */
final class ContingentItemRecurrenceResource extends AbstractResource
{
    protected string $dtoClass = ContingentItemRecurrenceDTO::class;
    protected string $listKey  = 'contingentItemRecurrence';

    public function __construct(HttpClientInterface $http, int $contingentId)
    {
        $this->endpoint = "v1/contingent/{$contingentId}/itemRecurrence";
        parent::__construct($http);
    }
}