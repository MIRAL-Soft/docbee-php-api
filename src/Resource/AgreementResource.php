<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AgreementDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Agreement records.
 *
 * @extends AbstractResource<AgreementDTO>
 */
final class AgreementResource extends AbstractResource
{
    protected string $endpoint = 'v1/agreement';
    protected string $dtoClass = AgreementDTO::class;
    protected string $listKey  = 'agreement';
}