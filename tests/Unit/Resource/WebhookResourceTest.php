<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use InvalidArgumentException;
use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Resource\WebhookResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see WebhookResource}.
 */
final class WebhookResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private WebhookResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new WebhookResource($this->http);
    }

    public function testRegisterPostsCorrectData(): void
    {
        $responseData = [
            'id'        => 10,
            'name'      => 'Test Webhook',
            'type'      => 'CREATE_TICKET',
            'withEmail' => true,
            'link'      => 'https://pcs.docbee.com/wh/test',
        ];

        $this->http
            ->expects($this->once())
            ->method('post')
            ->with(
                'webhook',
                $this->callback(fn(array $d) =>
                    $d['name'] === 'Test Webhook' &&
                    $d['type'] === 'CREATE_TICKET' &&
                    $d['withEmail'] === true
                )
            )
            ->willReturn($responseData);

        $dto = $this->resource->register(
            name:      'Test Webhook',
            type:      WebhookResource::TYPE_CREATE_TICKET,
            withEmail: true,
        );

        $this->assertSame(10, $dto->getId());
        $this->assertSame('CREATE_TICKET', $dto->getType());
    }

    public function testRegisterThrowsOnInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->resource->register(name: 'Bad', type: 'INVALID_TYPE');
    }

    public function testRegisterThrowsOnEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->resource->register(name: '', type: WebhookResource::TYPE_CREATE_TICKET);
    }

    public function testRegisterThrowsOnWhitespaceOnlyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->resource->register(name: '   ', type: WebhookResource::TYPE_CREATE_TICKET);
    }

    public function testTypeConstants(): void
    {
        $this->assertSame('CREATE_DOCUMENT',       WebhookResource::TYPE_CREATE_DOCUMENT);
        $this->assertSame('CREATE_TICKET',          WebhookResource::TYPE_CREATE_TICKET);
        $this->assertSame('EDIT_PROTOCOL',          WebhookResource::TYPE_EDIT_PROTOCOL);
        $this->assertSame('CREATE_TICKET_MESSAGE',  WebhookResource::TYPE_CREATE_TICKET_MESSAGE);
        $this->assertSame('EXECUTE_RULE_ENGINE',    WebhookResource::TYPE_EXECUTE_RULE_ENGINE);
        $this->assertSame('CONTAINER',              WebhookResource::TYPE_CONTAINER);
        $this->assertCount(6, WebhookResource::TYPES);
    }

    public function testCreateLinkCallsCorrectEndpoint(): void
    {
        $this->http
            ->expects($this->once())
            ->method('post')
            ->with(
                'webhook/5/link',
                $this->callback(fn(array $d) =>
                    $d['owner'] === 1 && $d['customer'] === 42
                )
            )
            ->willReturn(['link' => 'https://pcs.docbee.com/wh/xyz']);

        $linkDto = $this->resource->createLink(
            webhookId:  5,
            ownerId:    1,
            customerId: 42,
        );

        $this->assertSame('https://pcs.docbee.com/wh/xyz', $linkDto->getLink());
    }
}
