<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Resource\ProtocolResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see ProtocolResource::preview()}.
 *
 * The /protocol/{id}/preview endpoint returns a binary PDF (application/pdf), so the
 * method must use getRaw() — NOT the JSON-parsing get(), which would corrupt or crash
 * on binary content.
 */
final class ProtocolPreviewTest extends TestCase
{
    private const FAKE_PDF = "%PDF-1.4\n…binary…\n%%EOF";

    public function testPreviewUsesGetRawAgainstPreviewEndpoint(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('getRaw')
             ->with('protocol/72451/preview')
             ->willReturn(self::FAKE_PDF);
        // Must NOT use the JSON-parsing get() for a binary PDF response.
        $http->expects($this->never())->method('get');

        $pdf = (new ProtocolResource($http))->preview(72451);

        $this->assertSame(self::FAKE_PDF, $pdf);
        $this->assertStringStartsWith('%PDF', $pdf);
    }

    public function testPreviewReturnsString(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('getRaw')->willReturn(self::FAKE_PDF);

        $this->assertIsString((new ProtocolResource($http))->preview(1));
    }
}
