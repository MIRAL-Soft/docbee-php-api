<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\ElementDTO;
use miralsoft\docbee\api\DTO\PlanningTimeDTO;
use miralsoft\docbee\api\DTO\ProtocolDocumentTemplateDTO;
use miralsoft\docbee\api\DTO\ProtocolEntryDTO;
use miralsoft\docbee\api\DTO\ProtocolGroupDataDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for Protocol sub-resources — read-only.
 *
 * Required env vars per group:
 *   DOCBEE_TEST_PROTOCOL_SUB_PARENT_ID           — Protocol ID for entry/group/planningTime sub-tests
 *   DOCBEE_TEST_PROTOCOL_GROUP_PARENT_ID         — Protocol ID for protocolGroupEntries
 *                                                   (reuses PROTOCOL_SUB_PARENT_ID if not set separately)
 *   DOCBEE_TEST_PROTOCOL_TEMPLATE_DOC_PARENT_ID  — ProtocolTemplate ID for protocolDocumentTemplates
 *   DOCBEE_TEST_PROTOCOL_TEMPLATE_ENTRY_ELEMENTS_PARENT_ID — ProtocolTemplateEntry ID for elements
 */
final class ProtocolSubResourceIntegrationTest extends IntegrationTestCase
{
    private function protocolId(): int
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_SUB_PARENT_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        return $id;
    }

    // ── ProtocolEntry ─────────────────────────────────────────────────────────

    public function testProtocolEntryListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolEntries($this->protocolId())->list());
        $this->assertIsArray($result);
    }

    public function testProtocolEntryListItemsAreProtocolEntryDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolEntries($this->protocolId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolEntryDTO::class, $item);
        }
    }

    // ── ProtocolGroupData ─────────────────────────────────────────────────────

    public function testProtocolGroupDataListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolGroupData($this->protocolId())->list());
        $this->assertIsArray($result);
    }

    public function testProtocolGroupDataListItemsAreProtocolGroupDataDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolGroupData($this->protocolId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolGroupDataDTO::class, $item);
        }
    }

    // ── ProtocolPlanningTime ──────────────────────────────────────────────────

    public function testProtocolPlanningTimeListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolPlanningTimes($this->protocolId())->list());
        $this->assertIsArray($result);
    }

    public function testProtocolPlanningTimeListItemsArePlanningTimeDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->protocolPlanningTimes($this->protocolId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PlanningTimeDTO::class, $item);
        }
    }

    // ── ProtocolGroupEntries ──────────────────────────────────────────────────

    public function testProtocolGroupEntriesListReturnsArray(): void
    {
        $protocolId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_GROUP_PARENT_ID')
            ?? $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_SUB_PARENT_ID');
        $groupId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_GROUP_PARENT_ID');

        if ($protocolId === null || $groupId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_SUB_PARENT_ID and DOCBEE_TEST_PROTOCOL_GROUP_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->protocolGroupEntries($protocolId, $groupId)->list());
        $this->assertIsArray($result);
    }

    public function testProtocolGroupEntriesListItemsAreProtocolEntryDTOs(): void
    {
        $protocolId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_GROUP_PARENT_ID')
            ?? $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_SUB_PARENT_ID');
        $groupId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_GROUP_PARENT_ID');

        if ($protocolId === null || $groupId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_SUB_PARENT_ID and DOCBEE_TEST_PROTOCOL_GROUP_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->protocolGroupEntries($protocolId, $groupId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolEntryDTO::class, $item);
        }
    }

    // ── ProtocolDocumentTemplate ──────────────────────────────────────────────

    public function testProtocolDocumentTemplateListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_TEMPLATE_DOC_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_TEMPLATE_DOC_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->protocolDocumentTemplates($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testProtocolDocumentTemplateListItemsAreProtocolDocumentTemplateDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_TEMPLATE_DOC_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_TEMPLATE_DOC_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->protocolDocumentTemplates($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolDocumentTemplateDTO::class, $item);
        }
    }

    // ── ProtocolTemplateEntryElement ──────────────────────────────────────────

    public function testProtocolTemplateEntryElementListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_TEMPLATE_ENTRY_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_TEMPLATE_ENTRY_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->protocolTemplateEntryElements($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testProtocolTemplateEntryElementListItemsAreElementDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_TEMPLATE_ENTRY_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_TEMPLATE_ENTRY_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->protocolTemplateEntryElements($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ElementDTO::class, $item);
        }
    }
}
