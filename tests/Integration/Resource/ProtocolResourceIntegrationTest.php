<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\ProtocolDTO;
use miralsoft\docbee\api\DTO\ProtocolTemplateDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for top-level protocol resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_PROTOCOL_ID          — enables find($id) test for Protocol
 *   DOCBEE_TEST_PROTOCOL_TEMPLATE_ID — enables find($id) test for ProtocolTemplate
 */
final class ProtocolResourceIntegrationTest extends IntegrationTestCase
{
    // ── Protocol ──────────────────────────────────────────────────────────────

    public function testProtocolListReturnsArray(): void
    {
        $this->assertIsArray($this->client->protocols()->list());
    }

    public function testProtocolListItemsAreProtocolDTOs(): void
    {
        foreach ($this->client->protocols()->list() as $item) {
            $this->assertInstanceOf(ProtocolDTO::class, $item);
        }
    }

    public function testProtocolFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->protocols()->find($id);
        $this->assertInstanceOf(ProtocolDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── ProtocolTemplate ──────────────────────────────────────────────────────

    public function testProtocolTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->protocolTemplates()->list());
    }

    public function testProtocolTemplateListItemsAreProtocolTemplateDTOs(): void
    {
        foreach ($this->client->protocolTemplates()->list() as $item) {
            $this->assertInstanceOf(ProtocolTemplateDTO::class, $item);
        }
    }

    public function testProtocolTemplateFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_TEMPLATE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_TEMPLATE_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->protocolTemplates()->find($id);
        $this->assertInstanceOf(ProtocolTemplateDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }
}
