<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentMessageDTO;
use miralsoft\docbee\api\DTO\TicketMessageDTO;
use miralsoft\docbee\api\Resource\DocBeeDocumentMessageResource;
use miralsoft\docbee\api\Resource\TicketMessageResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see TicketMessageResource} and {@see DocBeeDocumentMessageResource}.
 */
final class MessageResourceTest extends TestCase
{
    // ── TicketMessageResource ─────────────────────────────────────────────────

    public function testTicketMessageAddPostsToCorrectEndpoint(): void
    {
        $http = $this->mockHttp();
        $http->expects($this->once())
            ->method('post')
            ->with(
                'ticket/42/message',
                $this->callback(fn(array $b) =>
                    $b['content']  === 'Hello'
                    && $b['internal'] === false
                    && !isset($b['subject'])
                ),
            )
            ->willReturn(['id' => 1, 'content' => 'Hello']);

        $dto = (new TicketMessageResource($http, 42))->add('Hello');

        $this->assertInstanceOf(TicketMessageDTO::class, $dto);
        $this->assertSame(1, $dto->getId());
    }

    public function testTicketMessageAddWithSubjectAndInternal(): void
    {
        $captured = [];
        $http = $this->mockHttp();
        $http->method('post')->willReturnCallback(function (string $url, array $body) use (&$captured): array {
            $captured = $body;
            return ['id' => 2, 'content' => 'Note', 'subject' => 'Sub', 'internal' => true];
        });

        (new TicketMessageResource($http, 10))->add('Note', subject: 'Sub', internal: true);

        $this->assertSame('Note', $captured['content']);
        $this->assertSame('Sub', $captured['subject']);
        $this->assertTrue($captured['internal']);
    }

    public function testTicketMessageHasMessagesReturnsTrueWhenCountPositive(): void
    {
        $http = $this->mockHttp();
        $http->method('get')->willReturn(['totalCount' => 3]);

        $this->assertTrue((new TicketMessageResource($http, 42))->hasMessages());
    }

    public function testTicketMessageHasMessagesReturnsFalseWhenCountZero(): void
    {
        $http = $this->mockHttp();
        $http->method('get')->willReturn(['totalCount' => 0]);

        $this->assertFalse((new TicketMessageResource($http, 42))->hasMessages());
    }

    public function testTicketMessageCountMessagesReturnsInteger(): void
    {
        $http = $this->mockHttp();
        $http->method('get')->willReturn(['totalCount' => 7]);

        $this->assertSame(7, (new TicketMessageResource($http, 42))->countMessages());
    }

    public function testTicketMessageUsesCorrectEndpointForListId(): void
    {
        $captured = [];
        $http = $this->mockHttp();
        $http->method('get')->willReturnCallback(function (string $url) use (&$captured): array {
            $captured[] = $url;
            return ['totalCount' => 0, 'ticketMessage' => []];
        });

        (new TicketMessageResource($http, 99))->list();

        $this->assertStringStartsWith('ticket/99/message', $captured[0]);
    }

    // ── DocBeeDocumentMessageResource ─────────────────────────────────────────

    public function testDocumentMessageAddPostsToCorrectEndpoint(): void
    {
        $http = $this->mockHttp();
        $http->expects($this->once())
            ->method('post')
            ->with(
                'docBeeDocument/71/message',
                $this->callback(fn(array $b) =>
                    $b['content']  === 'Done'
                    && $b['internal'] === false
                    && !isset($b['subject'])
                ),
            )
            ->willReturn(['id' => 5, 'content' => 'Done']);

        $dto = (new DocBeeDocumentMessageResource($http, 71))->add('Done');

        $this->assertInstanceOf(DocBeeDocumentMessageDTO::class, $dto);
        $this->assertSame(5, $dto->getId());
    }

    public function testDocumentMessageAddWithSubjectAndInternal(): void
    {
        $captured = [];
        $http = $this->mockHttp();
        $http->method('post')->willReturnCallback(function (string $url, array $body) use (&$captured): array {
            $captured = $body;
            return ['id' => 6, 'content' => 'Internal note'];
        });

        (new DocBeeDocumentMessageResource($http, 71))->add('Internal note', subject: 'Subj', internal: true);

        $this->assertSame('Internal note', $captured['content']);
        $this->assertSame('Subj', $captured['subject']);
        $this->assertTrue($captured['internal']);
    }

    public function testDocumentMessageAddWithoutSubjectDoesNotSendSubjectKey(): void
    {
        $captured = [];
        $http = $this->mockHttp();
        $http->method('post')->willReturnCallback(function (string $url, array $body) use (&$captured): array {
            $captured = $body;
            return ['id' => 7, 'content' => 'x'];
        });

        (new DocBeeDocumentMessageResource($http, 71))->add('x');

        $this->assertArrayNotHasKey('subject', $captured);
    }

    public function testDocumentMessageHasMessagesReturnsTrueWhenCountPositive(): void
    {
        $http = $this->mockHttp();
        $http->method('get')->willReturn(['totalCount' => 2]);

        $this->assertTrue((new DocBeeDocumentMessageResource($http, 71))->hasMessages());
    }

    public function testDocumentMessageHasMessagesReturnsFalseWhenCountZero(): void
    {
        $http = $this->mockHttp();
        $http->method('get')->willReturn(['totalCount' => 0]);

        $this->assertFalse((new DocBeeDocumentMessageResource($http, 71))->hasMessages());
    }

    public function testDocumentMessageCountMessagesReturnsInteger(): void
    {
        $http = $this->mockHttp();
        $http->method('get')->willReturn(['totalCount' => 4]);

        $this->assertSame(4, (new DocBeeDocumentMessageResource($http, 71))->countMessages());
    }

    public function testDocumentMessageUsesCorrectEndpointForList(): void
    {
        $captured = [];
        $http = $this->mockHttp();
        $http->method('get')->willReturnCallback(function (string $url) use (&$captured): array {
            $captured[] = $url;
            return ['totalCount' => 0, 'docBeeDocumentMessage' => []];
        });

        (new DocBeeDocumentMessageResource($http, 88))->list();

        $this->assertStringStartsWith('docBeeDocument/88/message', $captured[0]);
    }

    // ── helper ────────────────────────────────────────────────────────────────

    /** @return HttpClientInterface&MockObject */
    private function mockHttp(): HttpClientInterface
    {
        return $this->createMock(HttpClientInterface::class);
    }
}
