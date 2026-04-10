<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AgreementCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee AgreementCategory records.
 *
 * @extends AbstractResource<AgreementCategoryDTO>
 */
final class AgreementCategoryResource extends AbstractResource
{
    protected string $endpoint = 'v1/agreementCategory';
    protected string $dtoClass = AgreementCategoryDTO::class;
    protected string $listKey  = 'agreementCategory';
}