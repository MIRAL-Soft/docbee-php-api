<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Protocol records.
 *
 * @extends AbstractResource<ProtocolDTO>
 */
final class ProtocolResource extends AbstractResource
{
    protected string $endpoint = 'v1/protocol';
    protected string $dtoClass = ProtocolDTO::class;
    protected string $listKey  = 'protocol';
}