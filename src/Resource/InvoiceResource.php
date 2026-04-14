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

    public function export(int $exportProfileId): array { return $this->http->get("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): array { return $this->http->post("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]); }
    public function exportOverviewPdfByIds(int $pdfLayoutId, array $ids): array { return $this->http->post("{$this->endpoint}/exportOverviewPdfByIds/{$pdfLayoutId}", ['ids' => $ids]); }
    public function exportOverviewPricePdfByIds(int $pdfLayoutId, array $ids): array { return $this->http->post("{$this->endpoint}/exportOverviewPricePdfByIds/{$pdfLayoutId}", ['ids' => $ids]); }
    public function exportPdfByIds(int $pdfLayoutId, array $ids): array { return $this->http->post("{$this->endpoint}/exportPdfByIds/{$pdfLayoutId}", ['ids' => $ids]); }
}