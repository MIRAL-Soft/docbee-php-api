<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PaymentProfileMappingDTO;

/**
 * Provides access to Docbee PaymentProfileMapping records.
 * When $paymentProfileId is provided, uses the sub-resource endpoint
 * v1/paymentProfile/{id}/mapping; otherwise uses v1/paymentProfileMapping.
 *
 * @extends AbstractResource<PaymentProfileMappingDTO>
 */
final class PaymentProfileMappingResource extends AbstractResource
{
    protected string $dtoClass = PaymentProfileMappingDTO::class;
    protected string $listKey  = 'paymentProfileMapping';

    public function __construct(HttpClientInterface $http, ?int $paymentProfileId = null)
    {
        if ($paymentProfileId !== null && $paymentProfileId <= 0) {
            throw new \InvalidArgumentException('paymentProfileId must be a positive integer.');
        }
        $this->endpoint = $paymentProfileId !== null
            ? "v1/paymentProfile/{$paymentProfileId}/mapping"
            : 'v1/paymentProfileMapping';
        parent::__construct($http);
    }
}
