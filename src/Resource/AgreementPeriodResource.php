<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AgreementPeriodDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee AgreementPeriod records (sub-resource).
 *
 * @extends AbstractResource<AgreementPeriodDTO>
 */
final class AgreementPeriodResource extends AbstractResource
{
    protected string $dtoClass = AgreementPeriodDTO::class;
    protected string $listKey  = 'agreementPeriod';

    public function __construct(HttpClientInterface $http, int $agreementId)
    {
        $this->endpoint = "agreement/{$agreementId}/period";
        parent::__construct($http);
    }
}