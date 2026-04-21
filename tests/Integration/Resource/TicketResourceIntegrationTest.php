<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\TicketBoardColumnDTO;
use miralsoft\docbee\api\DTO\TicketBoardDTO;
use miralsoft\docbee\api\DTO\TicketBoardFieldDTO;
use miralsoft\docbee\api\DTO\TicketBoardFilterDTO;
use miralsoft\docbee\api\DTO\TicketBoardProfileDTO;
use miralsoft\docbee\api\DTO\TicketCategoryDTO;
use miralsoft\docbee\api\DTO\TicketDTO;
use miralsoft\docbee\api\DTO\TicketLinkDTO;
use miralsoft\docbee\api\DTO\TicketLinkTypeDTO;
use miralsoft\docbee\api\DTO\TicketMailParserConfigDTO;
use miralsoft\docbee\api\DTO\TicketMessageDTO;
use miralsoft\docbee\api\DTO\TicketRecurrenceDTO;
use miralsoft\docbee\api\DTO\TicketStatusDTO;
use miralsoft\docbee\api\DTO\TicketTemplateDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for ticket resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_TICKET_ID            — enables find($id) for Ticket
 *   DOCBEE_TEST_TICKET_BOARD_ID      — enables find($id) for TicketBoard
 *   DOCBEE_TEST_TICKET_SUB_PARENT_ID     — enables ticketLinks / ticketMessages sub-resource tests
 *   DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID — enables ticketBoardColumns/Fields/Filters sub-tests
 */
final class TicketResourceIntegrationTest extends IntegrationTestCase
{
    // ── Ticket ────────────────────────────────────────────────────────────────

    public function testTicketListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->tickets()->list());
        $this->assertIsArray($result);
    }

    public function testTicketListItemsAreTicketDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->tickets()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketDTO::class, $item);
        }
    }

    public function testTicketFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_TICKET_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->tickets()->find($id));
        $this->assertInstanceOf(TicketDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── TicketStatus ──────────────────────────────────────────────────────────

    public function testTicketStatusListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketStatuses()->list());
        $this->assertIsArray($result);
    }

    public function testTicketStatusListItemsAreTicketStatusDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketStatuses()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketStatusDTO::class, $item);
        }
    }

    // ── TicketCategory ────────────────────────────────────────────────────────

    public function testTicketCategoryListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketCategories()->list());
        $this->assertIsArray($result);
    }

    public function testTicketCategoryListItemsAreTicketCategoryDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketCategories()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketCategoryDTO::class, $item);
        }
    }

    // ── TicketTemplate ────────────────────────────────────────────────────────

    public function testTicketTemplateListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketTemplates()->list());
        $this->assertIsArray($result);
    }

    public function testTicketTemplateListItemsAreTicketTemplateDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketTemplates()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketTemplateDTO::class, $item);
        }
    }

    // ── TicketLinkType ────────────────────────────────────────────────────────

    public function testTicketLinkTypeListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketLinkTypes()->list());
        $this->assertIsArray($result);
    }

    public function testTicketLinkTypeListItemsAreTicketLinkTypeDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketLinkTypes()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketLinkTypeDTO::class, $item);
        }
    }

    // ── TicketBoardProfile ────────────────────────────────────────────────────

    public function testTicketBoardProfileListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketBoardProfiles()->list());
        $this->assertIsArray($result);
    }

    public function testTicketBoardProfileListItemsAreTicketBoardProfileDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketBoardProfiles()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketBoardProfileDTO::class, $item);
        }
    }

    // ── TicketBoard ───────────────────────────────────────────────────────────

    public function testTicketBoardListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketBoards()->list());
        $this->assertIsArray($result);
    }

    public function testTicketBoardListItemsAreTicketBoardDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketBoards()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketBoardDTO::class, $item);
        }
    }

    public function testTicketBoardFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->ticketBoards()->find($id));
        $this->assertInstanceOf(TicketBoardDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── TicketMailParserConfig ────────────────────────────────────────────────

    public function testTicketMailParserConfigListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketMailParserConfigs()->list());
        $this->assertIsArray($result);
    }

    public function testTicketMailParserConfigListItemsAreTicketMailParserConfigDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketMailParserConfigs()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketMailParserConfigDTO::class, $item);
        }
    }

    // ── TicketRecurrence ──────────────────────────────────────────────────────

    public function testTicketRecurrenceListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketRecurrences()->list());
        $this->assertIsArray($result);
    }

    public function testTicketRecurrenceListItemsAreTicketRecurrenceDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->ticketRecurrences()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketRecurrenceDTO::class, $item);
        }
    }

    // ── TicketLink (sub-resource) ─────────────────────────────────────────────

    public function testTicketLinkListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketLinks($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testTicketLinkListItemsAreTicketLinkDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketLinks($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketLinkDTO::class, $item);
        }
    }

    // ── TicketMessage (sub-resource) ──────────────────────────────────────────

    public function testTicketMessageListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketMessages($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testTicketMessageListItemsAreTicketMessageDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketMessages($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketMessageDTO::class, $item);
        }
    }

    // ── TicketBoardColumn (sub-resource) ──────────────────────────────────────

    public function testTicketBoardColumnListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketBoardColumns($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testTicketBoardColumnListItemsAreTicketBoardColumnDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketBoardColumns($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketBoardColumnDTO::class, $item);
        }
    }

    // ── TicketBoardField (sub-resource) ───────────────────────────────────────

    public function testTicketBoardFieldListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketBoardFields($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testTicketBoardFieldListItemsAreTicketBoardFieldDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketBoardFields($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketBoardFieldDTO::class, $item);
        }
    }

    // ── TicketBoardFilter (sub-resource) ──────────────────────────────────────

    public function testTicketBoardFilterListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketBoardFilters($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testTicketBoardFilterListItemsAreTicketBoardFilterDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->ticketBoardFilters($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TicketBoardFilterDTO::class, $item);
        }
    }
}
