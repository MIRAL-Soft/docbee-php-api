<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PriceCalculationBehaviorDTO;

/**
 * Provides access to Docbee PriceCalculationBehavior records (sub-resource of paymentProfile price).
 *
 * @extends AbstractResource<PriceCalculationBehaviorDTO>
 */
final class PriceCalculationBehaviorResource extends AbstractResource
{
    protected string $dtoClass = PriceCalculationBehaviorDTO::class;
    protected string $listKey  = 'priceCalculationBehavior';

    public function __construct(HttpClientInterface $http, int $paymentProfileId, int $priceId)
    {
        $this->endpoint = "v1/paymentProfile/{$paymentProfileId}/price/{$priceId}/priceCalculationBehavior";
        parent::__construct($http);
    }
}
