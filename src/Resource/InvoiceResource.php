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
 * **Fast document → invoice lookups (live-verified 2026-05-23):**
 *
 * The `/invoice` endpoint has **no** server-side filter for `docBeeDocument`, but it **does**
 * support the documented `ticketIds` filter.  Because every Invoice inherits its document's
 * ticket, an invoice can be located via the document's ticket without scanning the whole
 * collection.  {@see findByDocument()} and {@see findByDocuments()} use this internally:
 * they resolve each document's ticket, then query invoices by ticket server-side.
 *
 * Measured on the pcs tenant (≈ 3 829 invoices): resolving 3 documents dropped from
 * **13 504 ms** (full scan) to **150 ms** (ticket filter) — a 90× speed-up, identical result.
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
     * Returns all Invoice records linked to a given ticket (fast server-side filter).
     *
     * Uses the documented `ticketIds` query parameter — no full-collection scan.
     * Because each Invoice inherits its document's ticket, this returns one Invoice per
     * billable document on the ticket (typically a handful).  Live-measured at ~70 ms
     * regardless of tenant size.
     *
     * ```php
     * foreach ($client->invoices()->findByTicket($ticketId) as $invoice) {
     *     // $invoice->getDocBeeDocument(), $invoice->getInvoiceNumber(), …
     * }
     * ```
     *
     * @return list<InvoiceDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByTicket(int $ticketId): array
    {
        return $this->listAll(QueryBuilder::new()->param('ticketIds', $ticketId)->pageSize(100));
    }

    /**
     * Returns all Invoice records linked to any of the given tickets (server-side, batched).
     *
     * Sends the ticket IDs to the `ticketIds` filter in chunks, so a large ticket set still
     * resolves in a few requests instead of a full-collection scan.
     *
     * @param  int[] $ticketIds
     * @return list<InvoiceDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByTickets(array $ticketIds): array
    {
        $ticketIds = array_values(array_unique(array_filter($ticketIds, static fn($t) => $t !== null)));
        if (empty($ticketIds)) {
            return [];
        }

        $out = [];
        // Chunk to keep the query string a sane length; the /invoice endpoint caps pages at 100.
        foreach (array_chunk($ticketIds, 50) as $chunk) {
            foreach ($this->cursor(
                QueryBuilder::new()->param('ticketIds', implode(',', $chunk))->pageSize(100),
            ) as $invoice) {
                $out[] = $invoice;
            }
        }
        return $out;
    }

    /**
     * Returns the Invoice record for a given document ID, or null when none exists.
     *
     * **Fast path (default):** resolves the document's ticket, then locates the invoice
     * via the server-side `ticketIds` filter — no full scan.  Live-measured at ~150 ms
     * even on a tenant with thousands of invoices.
     *
     * Pass `$ticketId` directly when you already know the document's ticket to skip the
     * extra document fetch entirely (~70 ms total).
     *
     * **Fallback:** when the document has no ticket (a rare standalone Leistung), no
     * server-side filter applies and the method falls back to a full cursor scan.
     *
     * ```php
     * $invoice = $client->invoices()->findByDocument($docId);
     * if ($invoice) {
     *     $client->invoices()->update($invoice->getId(), ['invoiceNumber' => 'RE-2024-001']);
     * }
     *
     * // Even faster when the ticket is already known:
     * $invoice = $client->invoices()->findByDocument($docId, $doc->getTicket());
     * ```
     *
     * @param int      $docId    Document ID to look up.
     * @param int|null $ticketId Optional: the document's ticket, to skip the document fetch.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByDocument(int $docId, ?int $ticketId = null): ?InvoiceDTO
    {
        $ticketId ??= $this->resolveDocumentTicket($docId);

        if ($ticketId !== null) {
            // The invoice inherits its document's ticket, so the ticket filter is
            // authoritative: if no match here, no invoice exists for this document.
            foreach ($this->findByTicket($ticketId) as $invoice) {
                if ($invoice->getDocBeeDocument() === $docId) {
                    return $invoice;
                }
            }
            return null;
        }

        // Document has no ticket → no server-side filter available; fall back to a full scan.
        return $this->findByDocumentsViaScan([$docId])[$docId] ?? null;
    }

    /**
     * Returns a `docId → InvoiceDTO` map for all given document IDs.
     *
     * **Fast path (default):** batch-resolves the documents' tickets in one request, then
     * fetches all matching invoices via the server-side `ticketIds` filter.  This replaces
     * the previous full-collection scan.  Live-measured: 3 documents resolved in **150 ms**
     * vs **13 504 ms** for the old scan (90× faster, identical result).
     *
     * ```php
     * $map = $client->invoices()->findByDocuments([$docId1, $docId2, $docId3]);
     * foreach ($map as $docId => $invoice) {
     *     $client->invoices()->update($invoice->getId(), ['invoiceNumber' => "RE-{$docId}"]);
     * }
     * ```
     *
     * Documents without a matching Invoice record are absent from the returned map
     * (check with `isset($map[$docId])`).  Documents that have no ticket fall back to a
     * single legacy scan to preserve correctness.
     *
     * @param  int[]             $docIds Document IDs to look up.
     * @param  QueryBuilder|null $query  Optional: only used for the rare ticketless fallback scan.
     * @return array<int, InvoiceDTO>    Map of docId → InvoiceDTO.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByDocuments(array $docIds, ?QueryBuilder $query = null): array
    {
        $docIds = array_values(array_unique(array_filter($docIds, static fn($d) => $d !== null)));
        if (empty($docIds)) {
            return [];
        }

        // 1. Resolve docId → ticketId (one batched request, chunked at 100).
        $docToTicket = $this->resolveDocumentTickets($docIds);

        // 2. Fast path: fetch invoices for every involved ticket, then match docBeeDocument.
        $target  = array_flip($docIds);
        $results = [];
        $tickets = array_values(array_unique(array_filter(
            $docToTicket,
            static fn($t) => $t !== null,
        )));
        if (!empty($tickets)) {
            foreach ($this->findByTickets($tickets) as $invoice) {
                $docId = $invoice->getDocBeeDocument();
                if ($docId !== null && isset($target[$docId])) {
                    $results[$docId] = $invoice;
                }
            }
        }

        // 3. Fallback: documents with no ticket can't use the server-side filter.
        //    Resolve only those (rare) with a single legacy scan.
        $ticketless = array_values(array_filter(
            $docIds,
            static fn($id) => empty($docToTicket[$id]) && !isset($results[$id]),
        ));
        if (!empty($ticketless)) {
            $results += $this->findByDocumentsViaScan($ticketless, $query);
        }

        return $results;
    }

    /**
     * Resolves a single document's ticket ID, or null when it has none.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    private function resolveDocumentTicket(int $docId): ?int
    {
        $response = $this->http->get("docBeeDocument/{$docId}?fields=id,ticket");
        return isset($response['ticket']) ? (int) $response['ticket'] : null;
    }

    /**
     * Batch-resolves `docId → ticketId` (ticketId is null when the document has no ticket).
     *
     * Uses the `/docBeeDocument` `ids` filter in chunks of 100.
     *
     * @param  int[] $docIds
     * @return array<int, int|null>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    private function resolveDocumentTickets(array $docIds): array
    {
        $map = [];
        foreach (array_chunk($docIds, 100) as $chunk) {
            $qs = http_build_query([
                'ids'    => implode(',', $chunk),
                'fields' => 'id,ticket',
                'limit'  => count($chunk),
            ]);
            $response = $this->http->get("docBeeDocument?{$qs}");
            foreach (($response['docBeeDocument'] ?? []) as $doc) {
                if (isset($doc['id'])) {
                    $map[(int) $doc['id']] = isset($doc['ticket']) ? (int) $doc['ticket'] : null;
                }
            }
        }
        return $map;
    }

    /**
     * Legacy full-scan resolver for documents that have no ticket (fallback path only).
     *
     * @param  int[]             $docIds
     * @param  QueryBuilder|null $query Optional page-size/field override for the scan.
     * @return array<int, InvoiceDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    private function findByDocumentsViaScan(array $docIds, ?QueryBuilder $query = null): array
    {
        $target  = array_flip($docIds);
        $results = [];

        // /invoice silently returns 0 items for limit > 100 (live-verified 2026-05-23).
        $safePageSize = min($query?->getPageSize() ?? 100, 100);
        $q = ($query ?? QueryBuilder::new())
            ->fields(['id', 'docBeeDocument', 'status', 'invoiceNumber', 'billable', 'agreementInvoice'])
            ->pageSize($safePageSize);

        foreach ($this->cursor($q) as $invoice) {
            $docId = $invoice->getDocBeeDocument();
            if ($docId !== null && isset($target[$docId])) {
                $results[$docId] = $invoice;
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
        return $this->postExportByIds("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids);
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
        return $this->postExportByIds("{$this->endpoint}/exportOverviewPdfByIds/{$pdfLayoutId}", $ids);
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
        return $this->postExportByIds("{$this->endpoint}/exportOverviewPricePdfByIds/{$pdfLayoutId}", $ids);
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
        return $this->postExportByIds("{$this->endpoint}/exportPdfByIds/{$pdfLayoutId}", $ids);
    }
}