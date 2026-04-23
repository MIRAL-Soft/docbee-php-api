<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\EnvVariableDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee EnvVariable records.
 *
 * @extends AbstractResource<EnvVariableDTO>
 */
final class EnvVariableResource extends AbstractResource
{
    protected string $endpoint = 'envVariable';
    protected string $dtoClass = EnvVariableDTO::class;
    protected string $listKey  = 'envVariable';
}