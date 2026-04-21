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
        $this->assertIsArray($this->client->tickets()->list());
    }

    public function testTicketListItemsAreTicketDTOs(): void
    {
        foreach ($this->client->tickets()->list() as $item) {
            $this->assertInstanceOf(TicketDTO::class, $item);
        }
    }

    public function testTicketFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_TICKET_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->tickets()->find($id);
        $this->assertInstanceOf(TicketDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── TicketStatus ──────────────────────────────────────────────────────────

    public function testTicketStatusListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketStatuses()->list());
    }

    public function testTicketStatusListItemsAreTicketStatusDTOs(): void
    {
        foreach ($this->client->ticketStatuses()->list() as $item) {
            $this->assertInstanceOf(TicketStatusDTO::class, $item);
        }
    }

    // ── TicketCategory ────────────────────────────────────────────────────────

    public function testTicketCategoryListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketCategories()->list());
    }

    public function testTicketCategoryListItemsAreTicketCategoryDTOs(): void
    {
        foreach ($this->client->ticketCategories()->list() as $item) {
            $this->assertInstanceOf(TicketCategoryDTO::class, $item);
        }
    }

    // ── TicketTemplate ────────────────────────────────────────────────────────

    public function testTicketTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketTemplates()->list());
    }

    public function testTicketTemplateListItemsAreTicketTemplateDTOs(): void
    {
        foreach ($this->client->ticketTemplates()->list() as $item) {
            $this->assertInstanceOf(TicketTemplateDTO::class, $item);
        }
    }

    // ── TicketLinkType ────────────────────────────────────────────────────────

    public function testTicketLinkTypeListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketLinkTypes()->list());
    }

    public function testTicketLinkTypeListItemsAreTicketLinkTypeDTOs(): void
    {
        foreach ($this->client->ticketLinkTypes()->list() as $item) {
            $this->assertInstanceOf(TicketLinkTypeDTO::class, $item);
        }
    }

    // ── TicketBoardProfile ────────────────────────────────────────────────────

    public function testTicketBoardProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketBoardProfiles()->list());
    }

    public function testTicketBoardProfileListItemsAreTicketBoardProfileDTOs(): void
    {
        foreach ($this->client->ticketBoardProfiles()->list() as $item) {
            $this->assertInstanceOf(TicketBoardProfileDTO::class, $item);
        }
    }

    // ── TicketBoard ───────────────────────────────────────────────────────────

    public function testTicketBoardListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketBoards()->list());
    }

    public function testTicketBoardListItemsAreTicketBoardDTOs(): void
    {
        foreach ($this->client->ticketBoards()->list() as $item) {
            $this->assertInstanceOf(TicketBoardDTO::class, $item);
        }
    }

    public function testTicketBoardFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->ticketBoards()->find($id);
        $this->assertInstanceOf(TicketBoardDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── TicketMailParserConfig ────────────────────────────────────────────────

    public function testTicketMailParserConfigListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketMailParserConfigs()->list());
    }

    public function testTicketMailParserConfigListItemsAreTicketMailParserConfigDTOs(): void
    {
        foreach ($this->client->ticketMailParserConfigs()->list() as $item) {
            $this->assertInstanceOf(TicketMailParserConfigDTO::class, $item);
        }
    }

    // ── TicketRecurrence ──────────────────────────────────────────────────────

    public function testTicketRecurrenceListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ticketRecurrences()->list());
    }

    public function testTicketRecurrenceListItemsAreTicketRecurrenceDTOs(): void
    {
        foreach ($this->client->ticketRecurrences()->list() as $item) {
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
        $this->assertIsArray($this->client->ticketLinks($parentId)->list());
    }

    public function testTicketLinkListItemsAreTicketLinkDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ticketLinks($parentId)->list() as $item) {
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
        $this->assertIsArray($this->client->ticketMessages($parentId)->list());
    }

    public function testTicketMessageListItemsAreTicketMessageDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ticketMessages($parentId)->list() as $item) {
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
        $this->assertIsArray($this->client->ticketBoardColumns($parentId)->list());
    }

    public function testTicketBoardColumnListItemsAreTicketBoardColumnDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ticketBoardColumns($parentId)->list() as $item) {
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
        $this->assertIsArray($this->client->ticketBoardFields($parentId)->list());
    }

    public function testTicketBoardFieldListItemsAreTicketBoardFieldDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ticketBoardFields($parentId)->list() as $item) {
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
        $this->assertIsArray($this->client->ticketBoardFilters($parentId)->list());
    }

    public function testTicketBoardFilterListItemsAreTicketBoardFilterDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_TICKET_BOARD_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ticketBoardFilters($parentId)->list() as $item) {
            $this->assertInstanceOf(TicketBoardFilterDTO::class, $item);
        }
    }
}
