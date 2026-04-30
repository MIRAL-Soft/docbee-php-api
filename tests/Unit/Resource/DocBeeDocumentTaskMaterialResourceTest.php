<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\MaterialDTO;
use miralsoft\docbee\api\Resource\DocBeeDocumentTaskMaterialResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see DocBeeDocumentTaskMaterialResource}.
 */
final class DocBeeDocumentTaskMaterialResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private DocBeeDocumentTaskMaterialResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new DocBeeDocumentTaskMaterialResource($this->http, docBeeDocumentId: 10, taskId: 5);
    }

    // ── findByMaterialItemId ──────────────────────────────────────────────────

    public function testFindByMaterialItemIdReturnsMatchingEntry(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 2,
                'material'   => [
                    ['id' => 1, 'materialItem' => 99, 'amount' => 2.0],
                    ['id' => 2, 'materialItem' => 77, 'amount' => 5.0],
                ],
            ]);

        $result = $this->resource->findByMaterialItemId(77);

        $this->assertInstanceOf(MaterialDTO::class, $result);
        $this->assertSame(2, $result->getId());
        $this->assertSame(77, $result->getMaterialItem());
    }

    public function testFindByMaterialItemIdReturnsNullWhenNotFound(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 1,
                'material'   => [['id' => 1, 'materialItem' => 99, 'amount' => 1.0]],
            ]);

        $this->assertNull($this->resource->findByMaterialItemId(42));
    }

    public function testFindByMaterialItemIdReturnsNullOnEmptyList(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'material' => []]);

        $this->assertNull($this->resource->findByMaterialItemId(1));
    }

    // ── addOrIncrementByMaterialItemId ────────────────────────────────────────

    public function testAddOrIncrementCreatesNewEntryWhenAbsent(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'material' => []]);

        $this->http
            ->expects($this->once())
            ->method('post')
            ->with(
                'docBeeDocument/10/task/5/material',
                ['materialItem' => 55, 'amount' => 3.0]
            )
            ->willReturn(['id' => 10, 'materialItem' => 55, 'amount' => 3.0]);

        $dto = $this->resource->addOrIncrementByMaterialItemId(55, 3.0);

        $this->assertInstanceOf(MaterialDTO::class, $dto);
        $this->assertSame(55, $dto->getMaterialItem());
    }

    public function testAddOrIncrementIncrementsExistingEntry(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 1,
                'material'   => [['id' => 7, 'materialItem' => 55, 'amount' => 2.0]],
            ]);

        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocument/10/task/5/material/7',
                ['amount' => 5.0]  // 2.0 existing + 3.0 new
            )
            ->willReturn(['id' => 7, 'materialItem' => 55, 'amount' => 5.0]);

        $dto = $this->resource->addOrIncrementByMaterialItemId(55, 3.0);

        $this->assertSame(5.0, $dto->getAmount());
    }

    public function testAddOrIncrementHandlesNullExistingAmount(): void
    {
        // amount=null is treated as 0.0 when incrementing
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 1,
                'material'   => [['id' => 8, 'materialItem' => 55]],  // no amount key
            ]);

        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocument/10/task/5/material/8',
                ['amount' => 2.5]
            )
            ->willReturn(['id' => 8, 'materialItem' => 55, 'amount' => 2.5]);

        $this->resource->addOrIncrementByMaterialItemId(55, 2.5);
    }

    public function testAddOrIncrementNeverCallsPostWhenEntryExists(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 1,
                'material'   => [['id' => 7, 'materialItem' => 55, 'amount' => 1.0]],
            ]);
        $this->http->method('put')->willReturn(['id' => 7, 'materialItem' => 55, 'amount' => 2.0]);

        $this->http->expects($this->never())->method('post');

        $this->resource->addOrIncrementByMaterialItemId(55, 1.0);
    }

    public function testAddOrIncrementNeverCallsPutWhenEntryIsNew(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'material' => []]);
        $this->http->method('post')->willReturn(['id' => 1, 'materialItem' => 55, 'amount' => 1.0]);

        $this->http->expects($this->never())->method('put');

        $this->resource->addOrIncrementByMaterialItemId(55, 1.0);
    }
}
