<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\DTO;

use miralsoft\docbee\api\DTO\WebhookDTO;
use miralsoft\docbee\api\DTO\WebhookLinkDTO;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see WebhookDTO} and {@see WebhookLinkDTO}.
 */
final class WebhookDTOTest extends TestCase
{
    private function sampleData(): array
    {
        return [
            'id'                     => 5,
            'name'                   => 'New Ticket Notification',
            'type'                   => 'CREATE_TICKET',
            'docBeeDocumentTemplate' => null,
            'ticketTemplate'         => 1,
            'protocolTemplate'       => null,
            'withForm'               => false,
            'withEmail'              => true,
            'withAttachment'         => false,
            'threshold'              => null,
            'successText'            => 'Thank you!',
            'redirectUrl'            => 'https://example.com/success',
            'redirectWebhook'        => null,
            'ruleEngineActionId'     => null,
            'link'                   => 'https://pcs.docbee.com/webhook/abc123',
        ];
    }

    public function testFromArrayMapsAllFields(): void
    {
        $dto = WebhookDTO::fromArray($this->sampleData());

        $this->assertSame(5, $dto->getId());
        $this->assertSame('New Ticket Notification', $dto->getName());
        $this->assertSame('CREATE_TICKET', $dto->getType());
        $this->assertSame(1, $dto->getTicketTemplate());
        $this->assertFalse($dto->isWithForm());
        $this->assertTrue($dto->isWithEmail());
        $this->assertFalse($dto->isWithAttachment());
        $this->assertSame('Thank you!', $dto->getSuccessText());
        $this->assertSame('https://example.com/success', $dto->getRedirectUrl());
        $this->assertSame('https://pcs.docbee.com/webhook/abc123', $dto->getLink());
    }

    public function testToArrayExcludesReadOnlyLink(): void
    {
        $dto    = WebhookDTO::fromArray($this->sampleData());
        $result = $dto->toArray();

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('type', $result);
        $this->assertArrayNotHasKey('id', $result);
        $this->assertArrayNotHasKey('link', $result); // link is read-only
    }

    public function testWebhookLinkDTOFromArray(): void
    {
        $dto = WebhookLinkDTO::fromArray(['link' => 'https://pcs.docbee.com/wh/xyz']);

        $this->assertSame('https://pcs.docbee.com/wh/xyz', $dto->getLink());
        $this->assertNull($dto->getId());
    }
}
