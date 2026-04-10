<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AwayReasonDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee AwayReason records.
 *
 * @extends AbstractResource<AwayReasonDTO>
 */
final class AwayReasonResource extends AbstractResource
{
    protected string $endpoint = 'v1/awayReason';
    protected string $dtoClass = AwayReasonDTO::class;
    protected string $listKey  = 'awayReason';
}