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
    protected string $endpoint = 'invoice';
    protected string $dtoClass = InvoiceDTO::class;
    protected string $listKey  = 'invoice';

    /**
     * Exports all invoices matching a given export profile and returns raw file bytes.
     *
     * @return string Raw file bytes (typically PDF or CSV).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function export(int $exportProfileId): string
    {
        return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}");
    }

    /**
     * Exports a specific set of invoices by ID and returns raw file bytes.
     *
     * @param  int[]  $ids Invoice IDs to include.
     * @return string Raw file bytes.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function exportByIds(int $exportProfileId, array $ids): string
    {
        return $this->http->postRaw("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]);
    }

    /**
     * Generates a combined overview PDF for the given invoice IDs.
     *
     * This corresponds to the "Sammelreport" (combined billing report) in the
     * Docbee UI under Abrechnung → Abrechnungsreport erstellen.
     *
     * ```php
     * $pdf = $client->invoices()->exportOverviewPdfByIds($layoutId, $invoiceIds);
     * file_put_contents('sammelreport.pdf', $pdf);
     * ```
     *
     * @param  int   $pdfLayoutId  PDF layout ID from {@see pdfLayouts()}.
     * @param  int[] $ids          Invoice IDs to include.
     * @return string Raw PDF bytes.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function exportOverviewPdfByIds(int $pdfLayoutId, array $ids): string
    {
        return $this->http->postRaw("{$this->endpoint}/exportOverviewPdfByIds/{$pdfLayoutId}", ['ids' => $ids]);
    }

    /**
     * Generates a combined overview PDF with prices for the given invoice IDs.
     *
     * @param  int   $pdfLayoutId  PDF layout ID from {@see pdfLayouts()}.
     * @param  int[] $ids          Invoice IDs to include.
     * @return string Raw PDF bytes.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function exportOverviewPricePdfByIds(int $pdfLayoutId, array $ids): string
    {
        return $this->http->postRaw("{$this->endpoint}/exportOverviewPricePdfByIds/{$pdfLayoutId}", ['ids' => $ids]);
    }

    /**
     * Generates individual PDFs for the given invoice IDs, merged into one file.
     *
     * @param  int   $pdfLayoutId  PDF layout ID from {@see pdfLayouts()}.
     * @param  int[] $ids          Invoice IDs to include.
     * @return string Raw PDF bytes.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function exportPdfByIds(int $pdfLayoutId, array $ids): string
    {
        return $this->http->postRaw("{$this->endpoint}/exportPdfByIds/{$pdfLayoutId}", ['ids' => $ids]);
    }
}