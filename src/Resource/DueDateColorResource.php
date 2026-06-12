<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DueDateColorDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

use miralsoft\docbee\api\Resource\Concerns\NotSearchable;
/**
 * Provides access to Docbee DueDateColor records.
 *
 * @extends AbstractResource<DueDateColorDTO>
 */
final class DueDateColorResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'dueDateColor';
    protected string $dtoClass = DueDateColorDTO::class;
    protected string $listKey  = 'dueDateColor';
}