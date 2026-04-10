<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DueDateColorDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DueDateColor records.
 *
 * @extends AbstractResource<DueDateColorDTO>
 */
final class DueDateColorResource extends AbstractResource
{
    protected string $endpoint = 'v1/dueDateColor';
    protected string $dtoClass = DueDateColorDTO::class;
    protected string $listKey  = 'dueDateColor';
}