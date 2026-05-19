<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Resource\TicketResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see TicketResource::findByErpReferenceNumber()}.
 *
 * Verifies the search-based strategy that avoids full-table scans on large tenants.
 */
final class TicketResourceErpTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private TicketResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new TicketResource($this->http);
    }

    public function testFindByErpReferenceNumberUsesSearchNotFilter(): void
    {
        // The Docbee API ignores erpReferenceNumber-eq and erpReferenceNumber filters.
        // search= is the only server-side mechanism that narrows the hit set.
        $captured = [];
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$captured): array {
                $captured[] = $url;
                return ['ticket' => [], 'totalCount' => 0];
            });

        $this->resource->findByErpReferenceNumber('WO-12345');

        $this->assertNotEmpty($captured);
        $this->assertStringContainsString('search=WO-12345', urldecode($captured[0]));
        $this->assertStringNotContainsString('erpReferenceNumber-eq', $captured[0]);
        $this->assertStringNotContainsString('erpReferenceNumber=WO', urldecode($captured[0]));
    }

    public function testFindByErpReferenceNumberRequestsErpField(): void
    {
        // Must include erpReferenceNumber in fields so client-side filtering
        // works without extra find() calls per hit.
        $captured = [];
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$captured): array {
                $captured[] = $url;
                return ['ticket' => [], 'totalCount' => 0];
            });

        $this->resource->findByErpReferenceNumber('4981');

        $this->assertStringContainsString('erpReferenceNumber', $captured[0]);
    }

    public function testFindByErpReferenceNumberFiltersExactMatch(): void
    {
        // search() is broad — it may return tickets where '4981' appears in
        // title, description, etc.  Only exact erpReferenceNumber matches survive.
        $this->http
            ->method('get')
            ->willReturn([
                'ticket' => [
                    ['id' => 1437421, 'erpReferenceNumber' => null],   // search hit, not exact
                    ['id' => 1602785, 'erpReferenceNumber' => '4981'],  // exact match ✓
                    ['id' => 9999999, 'erpReferenceNumber' => '49810'], // partial — excluded
                ],
                'totalCount' => 3,
            ]);

        $tickets = $this->resource->findByErpReferenceNumber('4981');

        $this->assertCount(1, $tickets);
        $this->assertSame(1602785, $tickets[0]->getId());
    }

    public function testFindByErpReferenceNumberReturnsEmptyArrayWhenNoMatch(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['ticket' => [], 'totalCount' => 0]);

        $this->assertSame([], $this->resource->findByErpReferenceNumber('UNKNOWN'));
    }
}
