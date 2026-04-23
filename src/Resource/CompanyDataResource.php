<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CompanyDataDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CompanyData records.
 *
 * @extends AbstractResource<CompanyDataDTO>
 */
final class CompanyDataResource extends AbstractResource
{
    protected string $endpoint = 'companyData';
    protected string $dtoClass = CompanyDataDTO::class;
    protected string $listKey  = 'companyData';
}