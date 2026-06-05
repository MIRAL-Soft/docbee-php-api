<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\Resource\DocumentResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for document tag support:
 *   DocBeeDocumentDTO `tags` parsing/serialisation, and
 *   DocumentResource::addTag() / removeTag() read-modify-write behaviour.
 */
final class DocumentTagsTest extends TestCase
{
    // ── DTO ───────────────────────────────────────────────────────────────────

    public function testDtoParsesTagsAsIntList(): void
    {
        $dto = DocBeeDocumentDTO::fromArray(['id' => 1, 'tags' => [37, '50', 51]]);

        $this->assertSame([37, 50, 51], $dto->getTags());
    }

    public function testDtoTagsNullWhenAbsent(): void
    {
        $dto = DocBeeDocumentDTO::fromArray(['id' => 1]);

        $this->assertNull($dto->getTags());
    }

    public function testToArrayIncludesTagsWhenSet(): void
    {
        $dto = DocBeeDocumentDTO::fromArray(['id' => 1, 'tags' => [42]]);

        $this->assertSame([42], $dto->toArray()['tags']);
    }

    public function testToArrayOmitsTagsWhenNull(): void
    {
        $dto = DocBeeDocumentDTO::fromArray(['id' => 1]);

        $this->assertArrayNotHasKey('tags', $dto->toArray());
    }

    // ── addTag ────────────────────────────────────────────────────────────────

    public function testAddTagMergesWithExistingTags(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        // read current tags
        $http->method('get')->willReturn(['id' => 5, 'tags' => [37]]);
        // write merged list — existing tag preserved, new one appended
        $http->expects($this->once())
             ->method('put')
             ->with('docBeeDocument/5', ['tags' => [37, 50]])
             ->willReturn(['id' => 5]);

        (new DocumentResource($http))->addTag(5, 50);
    }

    public function testAddTagOnDocumentWithNoTags(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn(['id' => 5, 'tags' => null]);
        $http->expects($this->once())
             ->method('put')
             ->with('docBeeDocument/5', ['tags' => [50]])
             ->willReturn(['id' => 5]);

        (new DocumentResource($http))->addTag(5, 50);
    }

    public function testAddTagIsIdempotentWhenAlreadyPresent(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn(['id' => 5, 'tags' => [37, 50]]);
        // Already tagged → no write.
        $http->expects($this->never())->method('put');

        (new DocumentResource($http))->addTag(5, 50);
    }

    // ── removeTag ─────────────────────────────────────────────────────────────

    public function testRemoveTagKeepsOtherTags(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn(['id' => 5, 'tags' => [37, 50]]);
        $http->expects($this->once())
             ->method('put')
             ->with('docBeeDocument/5', ['tags' => [37]])
             ->willReturn(['id' => 5]);

        (new DocumentResource($http))->removeTag(5, 50);
    }

    public function testRemoveLastTagWritesEmptyList(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn(['id' => 5, 'tags' => [50]]);
        $http->expects($this->once())
             ->method('put')
             ->with('docBeeDocument/5', ['tags' => []])
             ->willReturn(['id' => 5]);

        (new DocumentResource($http))->removeTag(5, 50);
    }

    public function testRemoveTagIsIdempotentWhenAbsent(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn(['id' => 5, 'tags' => [37]]);
        // Tag not present → no write.
        $http->expects($this->never())->method('put');

        (new DocumentResource($http))->removeTag(5, 99);
    }

    public function testRemoveTagReindexesList(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn(['id' => 5, 'tags' => [10, 20, 30]]);
        // Removing the middle tag must produce a 0-indexed (list) array, not [0=>10, 2=>30].
        $http->expects($this->once())
             ->method('put')
             ->with('docBeeDocument/5', ['tags' => [10, 30]])
             ->willReturn(['id' => 5]);

        (new DocumentResource($http))->removeTag(5, 20);
    }
}
