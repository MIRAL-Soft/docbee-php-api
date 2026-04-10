<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TimerDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Timer records.
 *
 * @extends AbstractResource<TimerDTO>
 */
final class TimerResource extends AbstractResource
{
    protected string $endpoint = 'v1/timer';
    protected string $dtoClass = TimerDTO::class;
    protected string $listKey  = 'timer';
}