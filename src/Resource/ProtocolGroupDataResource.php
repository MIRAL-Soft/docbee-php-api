<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolGroupDataDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ProtocolGroupData records (sub-resource).
 *
 * @extends AbstractResource<ProtocolGroupDataDTO>
 */
final class ProtocolGroupDataResource extends AbstractResource
{
    protected string $dtoClass = ProtocolGroupDataDTO::class;
    protected string $listKey  = 'protocolGroupData';

    public function __construct(HttpClientInterface $http, int $protocolId)
    {
        $this->endpoint = "protocol/{$protocolId}/groupData";
        parent::__construct($http);
    }

    public function markFinish(): void { $this->http->put("{$this->endpoint}/markFinish", []); }
}