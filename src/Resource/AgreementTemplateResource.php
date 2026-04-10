<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\AgreementTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee AgreementTemplate records.
 *
 * @extends AbstractResource<AgreementTemplateDTO>
 */
final class AgreementTemplateResource extends AbstractResource
{
    protected string $endpoint = 'v1/agreementTemplate';
    protected string $dtoClass = AgreementTemplateDTO::class;
    protected string $listKey  = 'agreementTemplate';
}