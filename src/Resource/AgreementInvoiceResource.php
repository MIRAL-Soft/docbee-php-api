<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AgreementInvoiceDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee AgreementInvoice records (sub-resource).
 *
 * @extends AbstractResource<AgreementInvoiceDTO>
 */
final class AgreementInvoiceResource extends AbstractResource
{
    protected string $dtoClass = AgreementInvoiceDTO::class;
    protected string $listKey  = 'agreementInvoice';

    public function __construct(HttpClientInterface $http, int $agreementId)
    {
        $this->endpoint = "agreement/{$agreementId}/invoice";
        parent::__construct($http);
    }
}