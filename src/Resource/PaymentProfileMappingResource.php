<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PaymentProfileMappingDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee PaymentProfileMapping records (sub-resource).
 *
 * @extends AbstractResource<PaymentProfileMappingDTO>
 */
final class PaymentProfileMappingResource extends AbstractResource
{
    protected string $dtoClass = PaymentProfileMappingDTO::class;
    protected string $listKey  = 'paymentProfileMapping';

    public function __construct(HttpClientInterface $http, int $paymentProfileId)
    {
        $this->endpoint = "v1/paymentProfile/{$paymentProfileId}/mapping";
        parent::__construct($http);
    }
}