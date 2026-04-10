<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AgreementComponentTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee AgreementComponentTemplate records (sub-resource).
 *
 * @extends AbstractResource<AgreementComponentTemplateDTO>
 */
final class AgreementComponentTemplateResource extends AbstractResource
{
    protected string $dtoClass = AgreementComponentTemplateDTO::class;
    protected string $listKey  = 'agreementComponentTemplate';

    public function __construct(HttpClientInterface $http, int $agreementTemplateId)
    {
        $this->endpoint = "v1/agreementTemplate/{$agreementTemplateId}/componentTemplate";
        parent::__construct($http);
    }
}