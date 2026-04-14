<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PriceDTO;

/**
 * Provides access to Docbee Price records (sub-resource of paymentProfile).
 *
 * @extends AbstractResource<PriceDTO>
 */
final class PriceResource extends AbstractResource
{
    protected string $dtoClass = PriceDTO::class;
    protected string $listKey  = 'price';

    public function __construct(HttpClientInterface $http, int $paymentProfileId)
    {
        $this->endpoint = "v1/paymentProfile/{$paymentProfileId}/price";
        parent::__construct($http);
    }
}
