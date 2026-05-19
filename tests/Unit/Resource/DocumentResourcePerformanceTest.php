<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Resource\DocumentResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the performance-optimised helpers in {@see DocumentResource}.
 *
 * Covers: findByTicket(), findByErpReferenceNumber(),
 *         cursorWithCustomFields(), findByCustomFieldValue().
 */
final class DocumentResourcePerformanceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private DocumentResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new DocumentResource($this->http);
    }

    // ── findByTicket ──────────────────────────────────────────────────────────

    public function testFindByTicketUsesTicketIdsParam(): void
    {
        // The correct filter parameter is `ticketIds` (array[string] in the OpenAPI spec).
        // `ticket-eq` is silently ignored by Docbee and must NOT be used.
        $captured = [];
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$captured): array {
                $captured[] = $url;
                return ['docBeeDocument' => [], 'totalCount' => 0];
            });

        $this->resource->findByTicket(261861);

        $this->assertNotEmpty($captured);
        $this->assertStringContainsString('ticketIds=261861', $captured[0]);
        $this->assertStringNotContainsString('ticket-eq', $captured[0]);
    }

    public function testFindByTicketReturnsMatchingDocuments(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocument' => [['id' => 71033, 'ticket' => 261861]],
                'totalCount'     => 1,
            ]);

        $docs = $this->resource->findByTicket(261861);

        $this->assertCount(1, $docs);
        $this->assertSame(71033, $docs[0]->getId());
    }

    // ── findByErpReferenceNumber ───────────────────────────────────────────────

    public function testFindByErpReferenceNumberScopedToCustomer(): void
    {
        // Must scan customer's docs with fields=id,erpReferenceNumber
        $captured = [];
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$captured): array {
                $captured[] = $url;
                return ['docBeeDocument' => [], 'totalCount' => 0];
            });

        $this->resource->findByErpReferenceNumber(205023, 'WO-12345');

        $this->assertNotEmpty($captured);
        $this->assertStringContainsString('customer-eq=205023', $captured[0]);
        $this->assertStringContainsString('erpReferenceNumber', $captured[0]);
    }

    public function testFindByErpReferenceNumberFiltersExactMatch(): void
    {
        // Returns only documents where erpReferenceNumber matches exactly.
        // Docs with a different or null erpReferenceNumber are excluded.
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocument' => [
                    ['id' => 1, 'erpReferenceNumber' => 'WO-12345'],
                    ['id' => 2, 'erpReferenceNumber' => 'WO-99999'],
                    ['id' => 3, 'erpReferenceNumber' => null],
                ],
                'totalCount' => 3,
            ]);

        $docs = $this->resource->findByErpReferenceNumber(205023, 'WO-12345');

        $this->assertCount(1, $docs);
        $this->assertSame(1, $docs[0]->getId());
    }

    // ── cursorWithCustomFields ─────────────────────────────────────────────────

    public function testCursorWithCustomFieldsRequestsDotNotationFields(): void
    {
        // Must use ?fields=id,customFields.id,customFields.value
        // so that the list response already contains custom field values —
        // avoiding one getCustomFieldValues() call per document.
        $captured = [];
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$captured): array {
                $captured[] = $url;
                return ['docBeeDocument' => [], 'totalCount' => 0];
            });

        // Consume the generator
        iterator_to_array($this->resource->cursorWithCustomFields(205023));

        $this->assertNotEmpty($captured);
        $this->assertStringContainsString('customFields.id', $captured[0]);
        $this->assertStringContainsString('customFields.value', $captured[0]);
        $this->assertStringContainsString('customer-eq=205023', $captured[0]);
    }

    // ── findByCustomFieldValue ─────────────────────────────────────────────────

    public function testFindByCustomFieldValueReturnsMatchingDocument(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocument' => [
                    [
                        'id'           => 71033,
                        'customFields' => [
                            ['id' => 102, 'value' => null],
                            ['id' => 104, 'value' => 'WO-12345'],
                        ],
                    ],
                    [
                        'id'           => 71034,
                        'customFields' => [
                            ['id' => 104, 'value' => 'WO-99999'],
                        ],
                    ],
                ],
                'totalCount' => 2,
            ]);

        $docs = $this->resource->findByCustomFieldValue(205023, 104, 'WO-12345');

        $this->assertCount(1, $docs);
        $this->assertSame(71033, $docs[0]->getId());
    }

    public function testFindByCustomFieldValueReturnsEmptyWhenNoMatch(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocument' => [
                    ['id' => 1, 'customFields' => [['id' => 104, 'value' => 'OTHER']]],
                ],
                'totalCount' => 1,
            ]);

        $docs = $this->resource->findByCustomFieldValue(205023, 104, 'WO-NOTEXIST');

        $this->assertSame([], $docs);
    }

    public function testFindByCustomFieldValueSkipsDocsWithoutCustomFields(): void
    {
        // Documents without the custom field assigned return customFields=null (absent
        // from the API response). These must be skipped, not treated as a match.
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocument' => [
                    ['id' => 1],                  // no customFields key
                    ['id' => 2, 'customFields' => null], // null
                    ['id' => 3, 'customFields' => [['id' => 104, 'value' => 'X']]],
                ],
                'totalCount' => 3,
            ]);

        $docs = $this->resource->findByCustomFieldValue(205023, 104, 'X');

        $this->assertCount(1, $docs);
        $this->assertSame(3, $docs[0]->getId());
    }

    public function testFindByCustomFieldValueUsesOneCallPerPage(): void
    {
        // Key performance invariant: only one HTTP GET per pagination page,
        // NOT one GET per document.
        $callCount = 0;
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnCallback(function () use (&$callCount): array {
                $callCount++;
                return ['docBeeDocument' => [], 'totalCount' => 0];
            });

        $this->resource->findByCustomFieldValue(205023, 104, 'anything');

        // For an empty dataset the cursor makes exactly one page request.
        $this->assertGreaterThanOrEqual(1, $callCount);
        $this->assertLessThanOrEqual(2, $callCount); // ≤ 2 because first page may include a totalCount probe
    }
}
