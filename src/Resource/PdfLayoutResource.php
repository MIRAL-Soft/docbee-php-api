<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\PdfLayoutDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee PdfLayout records.
 *
 * @extends AbstractResource<PdfLayoutDTO>
 */
final class PdfLayoutResource extends AbstractResource
{
    protected string $endpoint = 'pdfLayout';
    protected string $dtoClass = PdfLayoutDTO::class;
    protected string $listKey  = 'pdfLayout';
}