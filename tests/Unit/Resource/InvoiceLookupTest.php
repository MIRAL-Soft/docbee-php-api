<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\InvoiceDTO;
use miralsoft\docbee\api\Resource\InvoiceResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the fast ticket-based invoice lookups on {@see InvoiceResource}:
 *   findByTicket(), findByTickets(), findByDocument(), findByDocuments().
 *
 * These verify the request shape (server-side `ticketIds` filter, document ticket
 * resolution) without hitting the live API.
 */
final class InvoiceLookupTest extends TestCase
{
    /**
     * Routes a mocked GET by URL substring to a canned response.
     *
     * @param array<string, mixed> $routes substring => response array
     */
    private function httpReturning(array $routes, ?array &$calls = null): HttpClientInterface
    {
        $calls ??= [];
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturnCallback(function (string $url) use ($routes, &$calls) {
            $calls[] = $url;
            foreach ($routes as $needle => $response) {
                if (str_contains($url, $needle)) {
                    return $response;
                }
            }
            return [];
        });
        return $http;
    }

    private function invoiceRow(int $id, int $docId, ?string $number, string $status): array
    {
        return ['id' => $id, 'docBeeDocument' => $docId, 'invoiceNumber' => $number, 'status' => $status];
    }

    // ── findByTicket ──────────────────────────────────────────────────────────

    public function testFindByTicketUsesServerSideTicketIdsFilter(): void
    {
        $calls = [];
        $http  = $this->httpReturning([
            'ticketIds=1602785' => [
                'invoice'    => [
                    $this->invoiceRow(157022, 165591, 'RE-1', 'INVOICED'),
                    $this->invoiceRow(157436, 165937, null, 'NOT_INVOICED'),
                ],
                'totalCount' => 2,
            ],
        ], $calls);

        $invoices = (new InvoiceResource($http))->findByTicket(1602785);

        $this->assertCount(2, $invoices);
        $this->assertContainsOnlyInstancesOf(InvoiceDTO::class, $invoices);
        $this->assertSame(165591, $invoices[0]->getDocBeeDocument());
        // The request must carry the server-side ticketIds filter and NOT scan unfiltered.
        $this->assertStringContainsString('ticketIds=1602785', $calls[0]);
    }

    public function testFindByTicketReturnsEmptyWhenNoInvoices(): void
    {
        $http = $this->httpReturning(['ticketIds' => ['invoice' => [], 'totalCount' => 0]]);
        $this->assertSame([], (new InvoiceResource($http))->findByTicket(999));
    }

    // ── findByTickets ─────────────────────────────────────────────────────────

    public function testFindByTicketsDeduplicatesAndBatches(): void
    {
        $calls = [];
        $http  = $this->httpReturning([
            'ticketIds' => [
                'invoice'    => [$this->invoiceRow(1, 71033, null, 'NOT_INVOICED')],
                'totalCount' => 1,
            ],
        ], $calls);

        $invoices = (new InvoiceResource($http))->findByTickets([261861, 261861, 261856]);

        $this->assertCount(1, $invoices);
        // Duplicate ticket ID collapsed → single chunk request.
        $this->assertCount(1, $calls);
        $this->assertStringContainsString('ticketIds=', $calls[0]);
        $this->assertStringContainsString('261861', $calls[0]);
        $this->assertStringContainsString('261856', $calls[0]);
    }

    public function testFindByTicketsReturnsEmptyForEmptyInput(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->never())->method('get');
        $this->assertSame([], (new InvoiceResource($http))->findByTickets([]));
    }

    // ── findByDocument (fast path) ────────────────────────────────────────────

    public function testFindByDocumentResolvesTicketThenFiltersByIt(): void
    {
        $calls = [];
        $http  = $this->httpReturning([
            // 1. document fetch → ticket
            'docBeeDocument/164483' => ['id' => 164483, 'ticket' => 1591446],
            // 2. invoices for that ticket
            'ticketIds=1591446'     => [
                'invoice'    => [$this->invoiceRow(156117, 164483, 'RE28335', 'INVOICED')],
                'totalCount' => 1,
            ],
        ], $calls);

        $invoice = (new InvoiceResource($http))->findByDocument(164483);

        $this->assertInstanceOf(InvoiceDTO::class, $invoice);
        $this->assertSame(156117, $invoice->getId());
        $this->assertSame(164483, $invoice->getDocBeeDocument());
        // Two-step fast path: doc fetch (for ticket) then ticket-filtered invoice query.
        $this->assertStringContainsString('docBeeDocument/164483', $calls[0]);
        $this->assertStringContainsString('ticketIds=1591446', $calls[1]);
    }

    public function testFindByDocumentWithSuppliedTicketSkipsDocumentFetch(): void
    {
        $calls = [];
        $http  = $this->httpReturning([
            'ticketIds=1591446' => [
                'invoice'    => [$this->invoiceRow(156117, 164483, 'RE28335', 'INVOICED')],
                'totalCount' => 1,
            ],
        ], $calls);

        $invoice = (new InvoiceResource($http))->findByDocument(164483, 1591446);

        $this->assertSame(156117, $invoice?->getId());
        // No document fetch — straight to the ticket-filtered invoice query.
        $this->assertCount(1, $calls);
        $this->assertStringContainsString('ticketIds=1591446', $calls[0]);
        $this->assertStringNotContainsString('docBeeDocument/', $calls[0]);
    }

    public function testFindByDocumentReturnsNullWhenTicketHasNoMatchingInvoice(): void
    {
        $http = $this->httpReturning([
            'docBeeDocument/164483' => ['id' => 164483, 'ticket' => 1591446],
            // ticket returns invoices, but none for doc 164483
            'ticketIds=1591446'     => [
                'invoice'    => [$this->invoiceRow(999, 777, null, 'NOT_INVOICED')],
                'totalCount' => 1,
            ],
        ]);

        $this->assertNull((new InvoiceResource($http))->findByDocument(164483));
    }

    public function testFindByDocumentFallsBackToScanWhenDocumentHasNoTicket(): void
    {
        $calls = [];
        $http  = $this->httpReturning([
            // document has no ticket key
            'docBeeDocument/164483' => ['id' => 164483],
            // fallback: full invoice scan (no ticketIds filter) finds the doc
            'invoice'               => [
                'invoice'    => [$this->invoiceRow(156117, 164483, 'RE28335', 'INVOICED')],
                'totalCount' => 1,
            ],
        ], $calls);

        $invoice = (new InvoiceResource($http))->findByDocument(164483);

        $this->assertSame(156117, $invoice?->getId());
        // The fallback scan must NOT use a ticketIds filter.
        $scanCall = $calls[1] ?? '';
        $this->assertStringNotContainsString('ticketIds', $scanCall);
    }

    // ── findByDocuments (batched) ─────────────────────────────────────────────

    public function testFindByDocumentsBatchesTicketResolutionAndInvoiceQuery(): void
    {
        $calls = [];
        $http  = $this->httpReturning([
            // batch document fetch → tickets
            'docBeeDocument?' => [
                'docBeeDocument' => [
                    ['id' => 164483, 'ticket' => 1591446],
                    ['id' => 165591, 'ticket' => 1602785],
                    ['id' => 165937, 'ticket' => 1602785],
                ],
                'totalCount'     => 3,
            ],
            // one ticket-filtered invoice query for both tickets
            'ticketIds' => [
                'invoice'    => [
                    $this->invoiceRow(156117, 164483, 'RE28335', 'INVOICED'),
                    $this->invoiceRow(157022, 165591, 'RE-1', 'INVOICED'),
                    $this->invoiceRow(157436, 165937, null, 'NOT_INVOICED'),
                ],
                'totalCount' => 3,
            ],
        ], $calls);

        $map = (new InvoiceResource($http))->findByDocuments([164483, 165591, 165937]);

        $this->assertCount(3, $map);
        $this->assertSame(156117, $map[164483]->getId());
        $this->assertSame(157022, $map[165591]->getId());
        $this->assertSame(157436, $map[165937]->getId());
        // Each returned invoice maps to the document key it is filed under.
        foreach ($map as $docId => $invoice) {
            $this->assertSame($docId, $invoice->getDocBeeDocument());
        }
    }

    public function testFindByDocumentsReturnsEmptyForEmptyInput(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->never())->method('get');
        $this->assertSame([], (new InvoiceResource($http))->findByDocuments([]));
    }

    public function testFindByDocumentsOmitsDocumentsWithNoInvoice(): void
    {
        $http = $this->httpReturning([
            'docBeeDocument?' => [
                'docBeeDocument' => [
                    ['id' => 164483, 'ticket' => 1591446],
                    ['id' => 999999, 'ticket' => 1591446], // no invoice for this one
                ],
                'totalCount'     => 2,
            ],
            'ticketIds' => [
                'invoice'    => [$this->invoiceRow(156117, 164483, 'RE28335', 'INVOICED')],
                'totalCount' => 1,
            ],
        ]);

        $map = (new InvoiceResource($http))->findByDocuments([164483, 999999]);

        $this->assertArrayHasKey(164483, $map);
        $this->assertArrayNotHasKey(999999, $map);
    }
}
