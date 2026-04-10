<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ConfidentialTagDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ConfidentialTag records.
 *
 * @extends AbstractResource<ConfidentialTagDTO>
 */
final class ConfidentialTagResource extends AbstractResource
{
    protected string $endpoint = 'v1/confidentialTag';
    protected string $dtoClass = ConfidentialTagDTO::class;
    protected string $listKey  = 'confidentialTag';
}