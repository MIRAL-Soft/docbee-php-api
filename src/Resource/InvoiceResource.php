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
     * Same as `$findFields` — ensures list/cursor/findModifiedSince/findCreatedSince
     * return DTOs that are as fully populated as a `find($id)` call.
     *
     * @var array<string>
     */
    protected array $defaultListFields = ['id', 'docBeeDocument', 'agreementInvoice', 'status', 'invoiceNumber', 'billable'];

    /**
     * Returns the Invoice record for a given document ID, or null when none exists.
     *
     * **Performance note:** The Docbee API has no server-side filter for `docBeeDocument`,
     * so this method performs a full cursor scan of all invoice records (O(n) where n is
     * the total number of invoices in the tenant).  On large tenants this can take 20–30 s.
     *
     * When you need to look up invoices for **multiple** documents, call
     * {@see findByDocuments()} instead — it performs a single scan and returns all
     * requested mappings at once:
     *
     * ```php
     * // ✓ Fast: one scan for any number of documents
     * $map = $client->invoices()->findByDocuments([$docId1, $docId2]);
     *
     * // ✗ Slow on large tenants: full scan per document
     * $inv1 = $client->invoices()->findByDocument($docId1); // ~25 s
     * $inv2 = $client->invoices()->findByDocument($docId2); // ~25 s again
     * ```
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByDocument(int $docId): ?InvoiceDTO
    {
        // Stop the cursor scan as soon as the document is found (short-circuit).
        // pageSize(100) = maximum safe value for the /invoice endpoint
        // (limit > 100 returns silently empty, live-verified 2026-05-23).
        foreach ($this->cursor(
            QueryBuilder::new()
                ->fields(['id', 'docBeeDocument', 'status', 'invoiceNumber', 'billable'])
                ->pageSize(100),
        ) as $invoice) {
            if ($invoice->getDocBeeDocument() === $docId) {
                return $invoice;
            }
        }
        return null;
    }

    /**
     * Returns a `docId → InvoiceDTO` map for all given document IDs.
     *
     * Scans all invoice records **once** and returns all requested mappings in a single
     * pass — far more efficient than calling {@see findByDocument()} N times:
     *
     * ```php
     * // Build the map for all documents that need billing numbers
     * $map = $client->invoices()->findByDocuments([$docId1, $docId2, $docId3]);
     * foreach ($map as $docId => $invoice) {
     *     $client->invoices()->update($invoice->getId(), ['invoiceNumber' => "RE-{$docId}"]);
     * }
     * ```
     *
     * Documents without a matching Invoice record are absent from the returned map
     * (they will not have an `InvoiceDTO` entry).  Check with `isset($map[$docId])`.
     *
     * Use `QueryBuilder::pageSize()` to tune the scan speed:
     * ```php
     * $map = $client->invoices()->findByDocuments($ids, QueryBuilder::new()->pageSize(500));
     * ```
     *
     * @param  int[]            $docIds Document IDs to look up.
     * @param  QueryBuilder|null $query  Optional: override fields or page size for the scan.
     * @return array<int, InvoiceDTO>   Map of docId → InvoiceDTO.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByDocuments(array $docIds, ?QueryBuilder $query = null): array
    {
        if (empty($docIds)) {
            return [];
        }

        $target  = array_flip($docIds); // O(1) lookup
        $results = [];
        // /invoice endpoint silently returns 0 items for limit > 100 (live-verified 2026-05-23).
        // Always cap at 100 regardless of what the caller requested.
        $requestedPageSize = $query?->getPageSize() ?? 100;
        $safePageSize      = min($requestedPageSize, 100);

        $q = ($query ?? QueryBuilder::new())
            ->fields(['id', 'docBeeDocument', 'status', 'invoiceNumber', 'billable', 'agreementInvoice'])
            ->pageSize($safePageSize);

        foreach ($this->cursor($q) as $invoice) {
            $docId = $invoice->getDocBeeDocument();
            if ($docId !== null && isset($target[$docId])) {
                $results[$docId] = $invoice;
                // Short-circuit: if we have found all requested documents, stop scanning.
                if (count($results) === count($target)) {
                    break;
                }
            }
        }

        return $results;
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