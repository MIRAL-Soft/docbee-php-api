<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AgreementComponentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee AgreementComponent records (sub-resource).
 *
 * @extends AbstractResource<AgreementComponentDTO>
 */
final class AgreementComponentResource extends AbstractResource
{
    protected string $dtoClass = AgreementComponentDTO::class;
    protected string $listKey  = 'agreementComponent';

    public function __construct(HttpClientInterface $http, int $agreementId)
    {
        $this->endpoint = "v1/agreement/{$agreementId}/component";
        parent::__construct($http);
    }
}