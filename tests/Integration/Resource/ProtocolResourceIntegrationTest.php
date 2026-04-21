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
        $result = $this->callApi(fn() => $this->client->protocols()->list());
        $this->assertIsArray($result);
    }

    public function testProtocolListItemsAreProtocolDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->protocols()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolDTO::class, $item);
        }
    }

    public function testProtocolFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->protocols()->find($id));
        $this->assertInstanceOf(ProtocolDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── ProtocolTemplate ──────────────────────────────────────────────────────

    public function testProtocolTemplateListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolTemplates()->list());
        $this->assertIsArray($result);
    }

    public function testProtocolTemplateListItemsAreProtocolTemplateDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolTemplates()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolTemplateDTO::class, $item);
        }
    }

    public function testProtocolTemplateFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_TEMPLATE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_TEMPLATE_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->protocolTemplates()->find($id));
        $this->assertInstanceOf(ProtocolTemplateDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }
}
