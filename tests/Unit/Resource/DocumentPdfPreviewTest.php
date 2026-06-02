<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Resource\DocumentResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the single-document "Leistungsnachweis" PDF rendering:
 *   DocumentResource::preview()  and its alias  exportPdfById().
 *
 * Both map to GET /docBeeDocument/{id}/preview and return raw PDF bytes via getRaw()
 * (NOT the JSON-parsing get(), which would corrupt binary content).
 */
final class DocumentPdfPreviewTest extends TestCase
{
    private const FAKE_PDF = "%PDF-1.4\n…binary…\n%%EOF";

    public function testPreviewCallsPreviewEndpointViaGetRaw(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('getRaw')
             ->with('docBeeDocument/165887/preview')
             ->willReturn(self::FAKE_PDF);
        // Must NOT use the JSON-parsing get() for a binary PDF response.
        $http->expects($this->never())->method('get');

        $pdf = (new DocumentResource($http))->preview(165887);

        $this->assertSame(self::FAKE_PDF, $pdf);
        $this->assertStringStartsWith('%PDF', $pdf);
    }

    public function testPreviewReturnsString(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('getRaw')->willReturn(self::FAKE_PDF);

        $this->assertIsString((new DocumentResource($http))->preview(1));
    }

    public function testExportPdfByIdDelegatesToPreviewEndpoint(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('getRaw')
             ->with('docBeeDocument/165887/preview')
             ->willReturn(self::FAKE_PDF);

        $pdf = (new DocumentResource($http))->exportPdfById(165887);

        $this->assertSame(self::FAKE_PDF, $pdf);
    }

    public function testExportPdfByIdAndPreviewProduceIdenticalResult(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('getRaw')->willReturn(self::FAKE_PDF);

        $resource = new DocumentResource($http);
        $this->assertSame($resource->preview(42), $resource->exportPdfById(42));
    }
}
