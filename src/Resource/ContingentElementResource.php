<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ContingentElementDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ContingentElement records (sub-resource).
 *
 * @extends AbstractResource<ContingentElementDTO>
 */
final class ContingentElementResource extends AbstractResource
{
    protected string $dtoClass = ContingentElementDTO::class;
    protected string $listKey  = 'contingentElement';

    public function __construct(HttpClientInterface $http, int $contingentId)
    {
        $this->endpoint = "contingent/{$contingentId}/element";
        parent::__construct($http);
    }
}