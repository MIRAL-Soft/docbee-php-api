<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CompanyDataDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

use miralsoft\docbee\api\Resource\Concerns\NotSearchable;
/**
 * Provides access to Docbee CompanyData records.
 *
 * @extends AbstractResource<CompanyDataDTO>
 */
final class CompanyDataResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'companyData';
    protected string $dtoClass = CompanyDataDTO::class;
    protected string $listKey  = 'companyData';
}