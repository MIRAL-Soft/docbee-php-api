<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\AbstractResource;
use miralsoft\docbee\api\Resource\DocumentResource;
use miralsoft\docbee\api\Resource\InvoiceResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Verifies page-size behaviour in AbstractResource::cursor() and related methods.
 *
 * Background: DEFAULT_PAGE_SIZE was raised from 50 → 100 (2026-05-23).
 * The /invoice endpoint silently returns 0 items for limit > 100, while
 * /docBeeDocument supports up to 500 per page — hence DocumentResource
 * overrides $defaultPageSize = 500 and InvoiceResource keeps 100.
 *
 * cursor() now uses QueryBuilder::buildForPage() instead of the clamped
 * limit() method, so callers can set pageSize(500) for document scans.
 */
final class PageSizeTest extends TestCase
{
    // ── helpers ──────────────────────────────────────────────────────────────

    private function makeResource(
        HttpClientInterface $http,
        int $defaultPageSize = 100,
    ): AbstractResource {
        return new class($http, $defaultPageSize) extends AbstractResource {
            protected string $endpoint = 'ticket';
            protected string $dtoClass = TicketDTO::class;
            protected string $listKey  = 'ticket';

            public function __construct(HttpClientInterface $http, int $ps)
            {
                $this->defaultPageSize = $ps;
                parent::__construct($http);
            }
        };
    }

    private function singlePageResponse(int $count = 0): array
    {
        return ['ticket' => array_fill(0, $count, ['id' => 1]), 'totalCount' => $count];
    }

    // ── Default page size ─────────────────────────────────────────────────────

    public function testCursorUsesDefaultPageSizeOf100(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->stringContains('limit=100'))
             ->willReturn($this->singlePageResponse(0));

        $resource = $this->makeResource($http, 100);
        iterator_to_array($resource->cursor());
    }

    public function testCursorUsesSubclassDefaultPageSize(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->stringContains('limit=500'))
             ->willReturn($this->singlePageResponse(0));

        $resource = $this->makeResource($http, 500); // e.g. DocumentResource default
        iterator_to_array($resource->cursor());
    }

    // ── QueryBuilder::pageSize() overrides the resource default ──────────────

    public function testCursorRespectsExplicitPageSize(): void
    {
        $capturedUrl = null;
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')
             ->willReturnCallback(function (string $url) use (&$capturedUrl) {
                 $capturedUrl = $url;
                 return $this->singlePageResponse(0);
             });

        $resource = $this->makeResource($http, 100); // default 100
        // Caller overrides to 250 for this specific scan
        iterator_to_array($resource->cursor(QueryBuilder::new()->pageSize(250)));

        $this->assertNotNull($capturedUrl);
        $this->assertStringContainsString('limit=250', $capturedUrl);
    }

    public function testCursorPageSizeDoesNotAffectListMethod(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        // list() uses QueryBuilder's limit(), not pageSize()
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalNot($this->stringContains('limit=250')))
             ->willReturn(['ticket' => [], 'totalCount' => 0]);

        $resource = $this->makeResource($http, 100);
        $resource->list(QueryBuilder::new()->pageSize(250));
    }

    // ── Pagination round-trips ────────────────────────────────────────────────

    public function testCursorPaginatesUsingConfiguredPageSize(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);

        // 200 records, 500-per-page → 1 page
        $http->expects($this->once())
             ->method('get')
             ->willReturn([
                 'ticket'     => array_fill(0, 200, ['id' => 1]),
                 'totalCount' => 200,
             ]);

        $resource = $this->makeResource($http, 500);
        $items    = iterator_to_array($resource->cursor());
        $this->assertCount(200, $items);
    }

    public function testCursorMultiplePages(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);

        // 150 records, 100-per-page → 2 pages
        $http->expects($this->exactly(2))
             ->method('get')
             ->willReturnOnConsecutiveCalls(
                 ['ticket' => array_fill(0, 100, ['id' => 1]), 'totalCount' => 150],
                 ['ticket' => array_fill(0, 50, ['id' => 1]),  'totalCount' => 150],
             );

        $resource = $this->makeResource($http, 100);
        $items    = iterator_to_array($resource->cursor());
        $this->assertCount(150, $items);
    }

    // ── DocumentResource uses 500/page by default ─────────────────────────────

    public function testDocumentResourceDefaultPageSizeIs500(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->stringContains('limit=500'))
             ->willReturn(['docBeeDocument' => [], 'totalCount' => 0]);

        $resource = new DocumentResource($http);
        iterator_to_array($resource->cursor(QueryBuilder::new()->fields(['id'])));
    }

    // ── InvoiceResource caps at 100/page ─────────────────────────────────────

    public function testInvoiceResourceDefaultPageSizeIs100(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('limit=100'),
                 $this->logicalNot($this->stringContains('limit=500')),
             ))
             ->willReturn(['invoice' => [], 'totalCount' => 0]);

        $resource = new InvoiceResource($http);
        iterator_to_array($resource->cursor(QueryBuilder::new()->fields(['id'])));
    }

    // ── QueryBuilder::pageSize() and getPageSize() ────────────────────────────

    public function testGetPageSizeReturnsNullByDefault(): void
    {
        $this->assertNull(QueryBuilder::new()->getPageSize());
    }

    public function testGetPageSizeReturnsSetValue(): void
    {
        $this->assertSame(250, QueryBuilder::new()->pageSize(250)->getPageSize());
    }

    public function testPageSizeClampsToOne(): void
    {
        $this->assertSame(1, QueryBuilder::new()->pageSize(0)->getPageSize());
        $this->assertSame(1, QueryBuilder::new()->pageSize(-10)->getPageSize());
    }

    public function testPageSizeIsNotCappedAt100(): void
    {
        // Unlike limit(), pageSize() is not capped at MAX_LIMIT
        $this->assertSame(500, QueryBuilder::new()->pageSize(500)->getPageSize());
        $this->assertSame(1000, QueryBuilder::new()->pageSize(1000)->getPageSize());
    }

    // ── QueryBuilder::getLimit() ──────────────────────────────────────────────

    public function testGetLimitReturns100AsDefault(): void
    {
        $this->assertSame(100, QueryBuilder::new()->getLimit());
    }

    public function testGetLimitReturnsSetValue(): void
    {
        $this->assertSame(50, QueryBuilder::new()->limit(50)->getLimit());
    }
}
