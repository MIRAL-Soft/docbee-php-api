<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Resource\DocumentTemplateResource;
use miralsoft\docbee\api\Resource\PriorityResource;
use miralsoft\docbee\api\Resource\ServiceTypeResource;
use miralsoft\docbee\api\Resource\TagResource;
use miralsoft\docbee\api\Resource\TicketStatusResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for the universal Docbee API filter-ignore bug.
 *
 * The Docbee API silently ignores ALL filter operators (`name-eq=`, `number-eq=`,
 * `isClosed-eq=`, etc.) on most entity-list endpoints — the server always returns
 * the unfiltered list regardless of what filter parameters are sent.
 *
 * All `findByName()` / `findByNumber()` helpers that previously relied on
 * `filterEq('name', …)` + `limit(1)` have been rewritten to use a cursor scan
 * with a client-side exact match.  These tests verify that:
 *   1. An exact match in the list is returned correctly.
 *   2. A non-matching first item is skipped (the root cause of the original bug —
 *      the server returned the first-in-list regardless, and `$results[0]` was
 *      returned without any match verification).
 *   3. {@see NotFoundException} is thrown when no item matches.
 */
final class FindByNameFilterBugTest extends TestCase
{
    // ── ServiceTypeResource ────────────────────────────────────────────────────

    public function testServiceTypeFindByNameReturnsCursorMatch(): void
    {
        $http = $this->mockHttp(ServiceTypeResource::class, [
            ['id' => 1, 'name' => 'Backup', 'number' => '1105', 'deactivated' => false],
            ['id' => 2, 'name' => 'Consulting', 'number' => '1714', 'deactivated' => false],
        ]);
        $resource = new ServiceTypeResource($http);

        $result = $resource->findByName('Consulting');

        $this->assertSame(2, $result->getId());
        $this->assertSame('Consulting', $result->getName());
    }

    public function testServiceTypeFindByNameThrowsNotFoundWhenAbsent(): void
    {
        $http = $this->mockHttp(ServiceTypeResource::class, [
            ['id' => 1, 'name' => 'Backup', 'number' => '1105'],
        ]);
        $resource = new ServiceTypeResource($http);

        $this->expectException(NotFoundException::class);
        $resource->findByName('ZZZ-NEVER-EXISTS');
    }

    public function testServiceTypeFindByNameDoesNotReturnFirstItemWhenNoMatch(): void
    {
        // Regression: old code returned $results[0] (the unfiltered first item)
        // even when getName() didn't match the needle.
        $http = $this->mockHttp(ServiceTypeResource::class, [
            ['id' => 1, 'name' => 'Backup', 'number' => '1105'],
            ['id' => 2, 'name' => 'Consulting', 'number' => '1714'],
        ]);
        $resource = new ServiceTypeResource($http);

        $this->expectException(NotFoundException::class);
        $resource->findByName('ZZZ-NEVER-EXISTS');
    }

    public function testServiceTypeFindByNumberReturnsCursorMatch(): void
    {
        $http = $this->mockHttp(ServiceTypeResource::class, [
            ['id' => 1, 'name' => 'Backup', 'number' => '1105', 'deactivated' => false],
            ['id' => 2, 'name' => 'Consulting', 'number' => '1714', 'deactivated' => false],
        ]);
        $resource = new ServiceTypeResource($http);

        $result = $resource->findByNumber('1714');

        $this->assertSame(2, $result->getId());
        $this->assertSame('1714', $result->getNumber());
    }

    public function testServiceTypeFindByNumberThrowsNotFoundWhenAbsent(): void
    {
        $http = $this->mockHttp(ServiceTypeResource::class, [
            ['id' => 1, 'name' => 'Backup', 'number' => '1105'],
        ]);
        $resource = new ServiceTypeResource($http);

        $this->expectException(NotFoundException::class);
        $resource->findByNumber('9999');
    }

    public function testServiceTypeFindByNumberRequestsNumberField(): void
    {
        // The `number` field is excluded from the Docbee default list response —
        // it must be requested explicitly via fields=id,name,number,...
        $captured = [];
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturnCallback(function (string $url) use (&$captured): array {
            $captured[] = $url;
            return ['serviceType' => [], 'totalCount' => 0];
        });
        $resource = new ServiceTypeResource($http);

        try { $resource->findByNumber('9999'); } catch (NotFoundException) {}

        $this->assertNotEmpty($captured);
        $this->assertStringContainsString('number', $captured[0]);
        $this->assertStringNotContainsString('number-eq=', $captured[0]);
    }

    // ── PriorityResource ───────────────────────────────────────────────────────

    public function testPriorityFindByNameReturnsCursorMatch(): void
    {
        $http = $this->mockHttp(PriorityResource::class, [
            ['id' => 10, 'name' => 'Niedrig'],
            ['id' => 11, 'name' => 'Hoch'],
        ]);
        $resource = new PriorityResource($http);

        $result = $resource->findByName('Hoch');
        $this->assertSame(11, $result->getId());
    }

    public function testPriorityFindByNameThrowsNotFound(): void
    {
        $http = $this->mockHttp(PriorityResource::class, [['id' => 10, 'name' => 'Niedrig']]);
        $resource = new PriorityResource($http);

        $this->expectException(NotFoundException::class);
        $resource->findByName('ZZZ-NEVER-EXISTS');
    }

    // ── TagResource ────────────────────────────────────────────────────────────

    public function testTagFindByNameReturnsCursorMatch(): void
    {
        $http = $this->mockHttp(TagResource::class, [
            ['id' => 20, 'name' => 'Alpha'],
            ['id' => 21, 'name' => 'Beta'],
        ]);
        $resource = new TagResource($http);

        $result = $resource->findByName('Beta');
        $this->assertSame(21, $result->getId());
    }

    public function testTagFindByNameThrowsNotFound(): void
    {
        $http = $this->mockHttp(TagResource::class, [['id' => 20, 'name' => 'Alpha']]);
        $resource = new TagResource($http);

        $this->expectException(NotFoundException::class);
        $resource->findByName('ZZZ-NEVER-EXISTS');
    }

    // ── TicketStatusResource ───────────────────────────────────────────────────

    public function testTicketStatusFindByNameReturnsCursorMatch(): void
    {
        $http = $this->mockHttp(TicketStatusResource::class, [
            ['id' => 813, 'name' => 'Offen'],
            ['id' => 815, 'name' => 'Geschlossen'],
        ]);
        $resource = new TicketStatusResource($http);

        $result = $resource->findByName('Geschlossen');
        $this->assertSame(815, $result->getId());
    }

    public function testTicketStatusFindByNameThrowsNotFound(): void
    {
        $http = $this->mockHttp(TicketStatusResource::class, [['id' => 813, 'name' => 'Offen']]);
        $resource = new TicketStatusResource($http);

        $this->expectException(NotFoundException::class);
        $resource->findByName('ZZZ-NEVER-EXISTS');
    }

    public function testTicketStatusFindClosedFiltersByBehaviour(): void
    {
        // isClosed is NOT a real API field — `filterEq('isClosed', true)` was always
        // ignored.  findClosed() now filters by behaviour === BEHAVIOUR_CLOSED.
        $http = $this->mockHttp(TicketStatusResource::class, [
            ['id' => 813, 'name' => 'Offen',        'behaviour' => 'NORMAL'],
            ['id' => 815, 'name' => 'Geschlossen',  'behaviour' => 'CLOSED'],
            ['id' => 820, 'name' => 'Zurückgestellt', 'behaviour' => 'PAUSED'],
        ]);
        $resource = new TicketStatusResource($http);

        $closed = $resource->findClosed();

        $this->assertCount(1, $closed);
        $this->assertSame(815, $closed[0]->getId());
        $this->assertSame('Geschlossen', $closed[0]->getName());
    }

    public function testTicketStatusFindClosedRequestsBehaviourField(): void
    {
        $captured = [];
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturnCallback(function (string $url) use (&$captured): array {
            $captured[] = $url;
            return ['ticketStatus' => [], 'totalCount' => 0];
        });
        $resource = new TicketStatusResource($http);

        $resource->findClosed();

        $this->assertNotEmpty($captured);
        $this->assertStringContainsString('behaviour', $captured[0]);
        $this->assertStringNotContainsString('isClosed', $captured[0]);
    }

    // ── DocumentTemplateResource ───────────────────────────────────────────────

    public function testDocumentTemplateFindByNameReturnsCursorMatch(): void
    {
        $http = $this->mockHttp(DocumentTemplateResource::class, [
            ['id' => 100, 'name' => 'Template A'],
            ['id' => 101, 'name' => 'Template B'],
        ]);
        $resource = new DocumentTemplateResource($http);

        $result = $resource->findByName('Template B');
        $this->assertSame(101, $result->getId());
    }

    public function testDocumentTemplateFindByNameThrowsNotFound(): void
    {
        $http = $this->mockHttp(DocumentTemplateResource::class, [['id' => 100, 'name' => 'Template A']]);
        $resource = new DocumentTemplateResource($http);

        $this->expectException(NotFoundException::class);
        $resource->findByName('ZZZ-NEVER-EXISTS');
    }

    // ── Shared helper ─────────────────────────────────────────────────────────

    /**
     * Returns a mock HttpClient that always serves the given items as a single page.
     *
     * Determines the listKey from the resource class name heuristically.
     * Returns totalCount equal to the item count so the cursor stops after one page.
     *
     * @param class-string $resourceClass
     * @param array<int, array<string, mixed>> $items
     * @return HttpClientInterface&MockObject
     */
    private function mockHttp(string $resourceClass, array $items): HttpClientInterface&MockObject
    {
        $listKey = match (true) {
            str_contains($resourceClass, 'ServiceType')      => 'serviceType',
            str_contains($resourceClass, 'Priority')         => 'priority',
            str_contains($resourceClass, 'Tag')              => 'tag',
            str_contains($resourceClass, 'TicketStatus')     => 'ticketStatus',
            str_contains($resourceClass, 'DocumentTemplate') => 'docBeeDocumentTemplate',
            default                                          => throw new \LogicException("Unknown: {$resourceClass}"),
        };

        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn([
            $listKey     => $items,
            'totalCount' => count($items),
        ]);

        return $http;
    }
}
