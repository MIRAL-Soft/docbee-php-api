<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentTaskDTO;
use miralsoft\docbee\api\DTO\DocumentTaskDeletionCheckDTO;
use miralsoft\docbee\api\Resource\DocBeeDocumentTaskResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see DocBeeDocumentTaskResource}.
 */
final class DocBeeDocumentTaskResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private DocBeeDocumentTaskResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new DocBeeDocumentTaskResource($this->http, docBeeDocumentId: 100);
    }

    // ── updateDescription ─────────────────────────────────────────────────────

    public function testUpdateDescriptionCallsPutWithDescriptionPayload(): void
    {
        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocument/100/task/7',
                ['description' => 'Updated description']
            )
            ->willReturn(['id' => 7, 'description' => 'Updated description']);

        $dto = $this->resource->updateDescription(7, 'Updated description');

        $this->assertInstanceOf(DocBeeDocumentTaskDTO::class, $dto);
        $this->assertSame('Updated description', $dto->getDescription());
    }

    public function testUpdateDescriptionReturnsDTO(): void
    {
        $this->http
            ->method('put')
            ->willReturn(['id' => 3, 'description' => 'New desc', 'name' => 'Task 3']);

        $dto = $this->resource->updateDescription(3, 'New desc');

        $this->assertSame(3, $dto->getId());
    }

    // ── canBeDeleted ──────────────────────────────────────────────────────────

    public function testCanBeDeletedReturnsTrueWhenNoLinkedRecords(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0]);

        $check = $this->resource->canBeDeleted(5);

        $this->assertInstanceOf(DocumentTaskDeletionCheckDTO::class, $check);
        $this->assertTrue($check->canDelete());
        $this->assertSame([], $check->getBlockers());
    }

    public function testCanBeDeletedQueriesAllThreeSubResources(): void
    {
        $calls = [];
        $this->http
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$calls): array {
                $calls[] = $url;
                return ['totalCount' => 0];
            });

        $this->resource->canBeDeleted(9);

        $this->assertCount(3, $calls);
        $this->assertStringContainsString('workLog',      implode('|', $calls));
        $this->assertStringContainsString('planningTime', implode('|', $calls));
        $this->assertStringContainsString('material',     implode('|', $calls));
    }

    public function testCanBeDeletedUsesCorrectNestedUrl(): void
    {
        $calls = [];
        $this->http
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$calls): array {
                $calls[] = $url;
                return ['totalCount' => 0];
            });

        $this->resource->canBeDeleted(9);

        foreach ($calls as $url) {
            $this->assertStringStartsWith('docBeeDocument/100/task/9/', $url);
        }
    }

    public function testCanBeDeletedReturnsFalseWhenWorkLogsExist(): void
    {
        $this->http
            ->method('get')
            ->willReturnCallback(fn(string $url): array => match (true) {
                str_contains($url, 'workLog')      => ['totalCount' => 2],
                str_contains($url, 'planningTime') => ['totalCount' => 0],
                str_contains($url, 'material')     => ['totalCount' => 0],
                default                             => ['totalCount' => 0],
            });

        $check = $this->resource->canBeDeleted(5);

        $this->assertFalse($check->canDelete());
        $this->assertSame(2, $check->getWorkLogCount());
        $this->assertStringContainsString('work log', $check->getBlockers()[0]);
    }

    public function testCanBeDeletedReturnsFalseWhenMultipleBlockers(): void
    {
        $this->http
            ->method('get')
            ->willReturnCallback(fn(string $url): array => match (true) {
                str_contains($url, 'workLog')      => ['totalCount' => 1],
                str_contains($url, 'planningTime') => ['totalCount' => 3],
                str_contains($url, 'material')     => ['totalCount' => 2],
                default                             => ['totalCount' => 0],
            });

        $check = $this->resource->canBeDeleted(5);

        $this->assertFalse($check->canDelete());
        $this->assertCount(3, $check->getBlockers());
        $this->assertSame(1, $check->getWorkLogCount());
        $this->assertSame(3, $check->getPlanningTimeCount());
        $this->assertSame(2, $check->getMaterialCount());
    }
}
