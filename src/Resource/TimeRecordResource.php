<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TimeRecordDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TimeRecord records.
 *
 * @extends AbstractResource<TimeRecordDTO>
 */
final class TimeRecordResource extends AbstractResource
{
    protected string $endpoint = 'v1/timeRecord';
    protected string $dtoClass = TimeRecordDTO::class;
    protected string $listKey  = 'timeRecord';
}