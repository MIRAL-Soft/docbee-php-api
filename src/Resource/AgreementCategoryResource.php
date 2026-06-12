<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AgreementCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee AgreementCategory records.
 *
 * @extends AbstractResource<AgreementCategoryDTO>
 */
final class AgreementCategoryResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'agreementCategory';
    protected string $dtoClass = AgreementCategoryDTO::class;
    protected string $listKey  = 'agreementCategory';
}