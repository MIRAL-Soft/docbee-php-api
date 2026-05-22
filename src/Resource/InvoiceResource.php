<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\InvoiceDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Invoice records.
 *
 * In Docbee, each approved+billable document has exactly one Invoice record.
 * The Invoice record is the authoritative place for the **billing number** ("Abrechnungsnummer")
 * and for marking a document as fully invoiced.
 *
 * **Billing workflow (confirmed by live probe):**
 *
 * ```php
 * // 1. Find the invoice record for a document
 * $invoice = $client->invoices()->findByDocument($docId);
 *
 * // 2. Set the billing number + mark as INVOICED in one call
 * $client->invoices()->update($invoice->getId(), ['invoiceNumber' => 'RE-2024-001']);
 * // → invoice status changes from OPEN → INVOICED automatically
 *
 * // 3. Export billing PDF
 * $pdf = $client->invoices()->exportOverviewPdfByIds($layoutId, [$invoice->getId()]);
 * ```
 *
 * **Field mapping (live-verified):**
 * - `invoiceNumber` on InvoiceDTO  = "Abrechnungsnummer" in Docbee UI and CSV exports
 * - `erpReferenceNumber` on DocBeeDocumentDTO = "Vorgangs-Referenznummer" in CSV
 *   (an ERP integration field set at document creation; silently ignored on `update()`)
 *
 * @extends AbstractResource<InvoiceDTO>
 */
final class InvoiceResource extends AbstractResource
{
    protected string $endpoint = 'invoice';
    protected string $dtoClass = InvoiceDTO::class;
    protected string $listKey  = 'invoice';

    /**
     * Fields requested automatically by {@see find()} when no explicit fields are passed.
     *
     * `docBeeDocument` and `status` are absent from the default invoice response.
     *
     * @var array<string>
     */
    protected array $findFields = ['id', 'docBeeDocument', 'agreementInvoice', 'status', 'invoiceNumber', 'billable'];

    /**
     * Returns the Invoice record for a given document ID, or null when none exists.
     *
     * Because the Docbee API provides no server-side filter for `docBeeDocument`,
     * this method performs a cursor scan with explicit field selection.
     *
     * ```php
     * $invoice = $client->invoices()->findByDocument($docId);
     * if ($invoice) {
     *     $client->invoices()->update($invoice->getId(), ['invoiceNumber' => 'RE-2024-001']);
     * }
     * ```
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByDocument(int $docId): ?InvoiceDTO
    {
        foreach ($this->cursor(QueryBuilder::new()->fields(['id', 'docBeeDocument', 'status', 'invoiceNumber', 'billable'])) as $invoice) {
            if ($invoice->getDocBeeDocument() === $docId) {
                return $invoice;
            }
        }
        return null;
    }

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