<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ErrorLogDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ErrorLog records.
 *
 * @extends AbstractResource<ErrorLogDTO>
 */
final class ErrorLogResource extends AbstractResource
{
    protected string $endpoint = 'v1/errorLog';
    protected string $dtoClass = ErrorLogDTO::class;
    protected string $listKey  = 'errorLog';
}