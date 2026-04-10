<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\InvoiceDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Invoice records.
 *
 * @extends AbstractResource<InvoiceDTO>
 */
final class InvoiceResource extends AbstractResource
{
    protected string $endpoint = 'v1/invoice';
    protected string $dtoClass = InvoiceDTO::class;
    protected string $listKey  = 'invoice';
}