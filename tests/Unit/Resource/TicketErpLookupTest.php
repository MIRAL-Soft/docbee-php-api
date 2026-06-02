<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketDTO;
use miralsoft\docbee\api\Resource\TicketResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the global-search-based exact erpReferenceNumber lookups:
 *   TicketResource::findByErpReferenceNumber() / findByErpReferenceNumbers().
 *
 * Verifies the 2-step request shape (GET /search/{term} → GET /ticket?ids=…) and
 * the exact client-side filtering (search is a broad full-text match).
 */
final class TicketErpLookupTest extends TestCase
{
    /**
     * @param array<string, mixed> $routes substring => response
     */
    private function http(array $routes, ?array &$calls = null): HttpClientInterface
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

    private function ticketRow(int $id, ?string $erp): array
    {
        return ['id' => $id, 'erpReferenceNumber' => $erp];
    }

    public function testFindByErpReferenceNumberUsesGlobalSearchThenFetchesByIds(): void
    {
        $calls = [];
        $http  = $this->http([
            // 1. global search returns candidate ticket IDs
            'search/4993' => ['tickets' => [412467, 1612982], 'ticketMessages' => [999]],
            // 2. fetch candidates by ids
            'ticket?'     => ['ticket' => [
                $this->ticketRow(412467, null),       // full-text noise (term in title)
                $this->ticketRow(1612982, '4993'),    // the exact match
            ], 'totalCount' => 2],
        ], $calls);

        $hits = (new TicketResource($http))->findByErpReferenceNumber('4993');

        $this->assertCount(1, $hits);
        $this->assertSame(1612982, $hits[0]->getId());
        $this->assertSame('4993', $hits[0]->getErpReferenceNumber());
        // Step 1 must hit the global /search endpoint, step 2 the /ticket?ids= endpoint.
        $this->assertStringContainsString('search/4993', $calls[0]);
        $this->assertStringContainsString('ids=', $calls[1]);
    }

    public function testFindByErpReferenceNumberFiltersOutNonExactMatches(): void
    {
        $http = $this->http([
            'search/77' => ['tickets' => [1, 2, 3]],
            'ticket?'   => ['ticket' => [
                $this->ticketRow(1, '770'),   // contains 77 but not exact
                $this->ticketRow(2, '7'),     // not exact
                $this->ticketRow(3, '77'),    // exact
            ], 'totalCount' => 3],
        ]);

        $hits = (new TicketResource($http))->findByErpReferenceNumber('77');

        $this->assertCount(1, $hits);
        $this->assertSame(3, $hits[0]->getId());
    }

    public function testFindByErpReferenceNumberReturnsEmptyWhenSearchHasNoTickets(): void
    {
        $calls = [];
        // Global search with no match returns {} (no "tickets" key) — live-verified.
        $http  = $this->http(['search/' => []], $calls);

        $hits = (new TicketResource($http))->findByErpReferenceNumber('ZZZ-NONE');

        $this->assertSame([], $hits);
        // Must short-circuit: no /ticket fetch when there are no candidates.
        $this->assertCount(1, $calls);
        $this->assertStringContainsString('search/', $calls[0]);
    }

    public function testFindByErpReferenceNumberUrlEncodesTheTerm(): void
    {
        $calls = [];
        $http  = $this->http(['search/' => ['tickets' => []]], $calls);

        (new TicketResource($http))->findByErpReferenceNumber('WO 12/345');

        // Spaces and slashes must be percent-encoded into the path segment.
        $this->assertStringContainsString('search/WO%2012%2F345', $calls[0]);
    }

    public function testFindByErpReferenceNumbersBatchesIdFetchAcrossValues(): void
    {
        $calls = [];
        $http  = $this->http([
            'search/4993'     => ['tickets' => [1612982]],
            'search/4954'     => ['tickets' => [1516972]],
            'search/ZZZ-NONE' => [],
            // single combined fetch for all collected candidate IDs
            'ticket?'         => ['ticket' => [
                $this->ticketRow(1612982, '4993'),
                $this->ticketRow(1516972, '4954'),
            ], 'totalCount' => 2],
        ], $calls);

        $map = (new TicketResource($http))->findByErpReferenceNumbers(['4993', '4954', 'ZZZ-NONE']);

        $this->assertSame([1612982], array_map(fn($t) => $t->getId(), $map['4993']));
        $this->assertSame([1516972], array_map(fn($t) => $t->getId(), $map['4954']));
        $this->assertSame([], $map['ZZZ-NONE']);

        // 3 search calls + exactly 1 combined /ticket?ids= fetch.
        $ticketFetches = array_filter($calls, fn($u) => str_contains($u, 'ticket?'));
        $this->assertCount(1, $ticketFetches);
    }

    public function testFindByErpReferenceNumbersReturnsEmptyForEmptyInput(): void
    {
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->never())->method('get');

        $this->assertSame([], (new TicketResource($http))->findByErpReferenceNumbers([]));
    }

    public function testFindByErpReferenceNumbersDeduplicatesValues(): void
    {
        $calls = [];
        $http  = $this->http([
            'search/4993' => ['tickets' => [1612982]],
            'ticket?'     => ['ticket' => [$this->ticketRow(1612982, '4993')], 'totalCount' => 1],
        ], $calls);

        (new TicketResource($http))->findByErpReferenceNumbers(['4993', '4993']);

        // Duplicate value collapsed → a single search call.
        $searchCalls = array_filter($calls, fn($u) => str_contains($u, 'search/'));
        $this->assertCount(1, $searchCalls);
    }
}
