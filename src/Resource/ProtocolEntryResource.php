<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolEntryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ProtocolEntry records (sub-resource).
 *
 * @extends AbstractResource<ProtocolEntryDTO>
 */
final class ProtocolEntryResource extends AbstractResource
{
    protected string $dtoClass = ProtocolEntryDTO::class;
    protected string $listKey  = 'protocolEntry';

    public function __construct(HttpClientInterface $http, int $protocolId)
    {
        $this->endpoint = "v1/protocol/{$protocolId}/entry";
        parent::__construct($http);
    }
}