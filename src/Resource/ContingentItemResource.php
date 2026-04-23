<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ContingentItemDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ContingentItem records (sub-resource).
 *
 * @extends AbstractResource<ContingentItemDTO>
 */
final class ContingentItemResource extends AbstractResource
{
    protected string $dtoClass = ContingentItemDTO::class;
    protected string $listKey  = 'contingentItem';

    public function __construct(HttpClientInterface $http, int $contingentId)
    {
        $this->endpoint = "contingent/{$contingentId}/item";
        parent::__construct($http);
    }
}