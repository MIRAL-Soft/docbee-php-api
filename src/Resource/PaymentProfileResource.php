<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\PaymentProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee PaymentProfile records.
 *
 * @extends AbstractResource<PaymentProfileDTO>
 */
final class PaymentProfileResource extends AbstractResource
{
    protected string $endpoint = 'v1/paymentProfile';
    protected string $dtoClass = PaymentProfileDTO::class;
    protected string $listKey  = 'paymentProfile';
}