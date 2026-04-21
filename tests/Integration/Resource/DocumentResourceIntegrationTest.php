<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentRecurrenceDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateProfileDTO;
use miralsoft\docbee\api\DTO\SiteConfigDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for document-related resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_DOCUMENT_ID — enables find($id) test for Document
 */
final class DocumentResourceIntegrationTest extends IntegrationTestCase
{
    // ── Document ──────────────────────────────────────────────────────────────

    public function testDocumentListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documents()->list());
    }

    public function testDocumentListItemsAreDocBeeDocumentDTOs(): void
    {
        foreach ($this->client->documents()->list() as $item) {
            $this->assertInstanceOf(DocBeeDocumentDTO::class, $item);
        }
    }

    public function testDocumentFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_DOCUMENT_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOCUMENT_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->documents()->find($id);
        $this->assertInstanceOf(DocBeeDocumentDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── DocumentTemplate ──────────────────────────────────────────────────────

    public function testDocumentTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documentTemplates()->list());
    }

    public function testDocumentTemplateListItemsAreDocBeeDocumentTemplateDTOs(): void
    {
        foreach ($this->client->documentTemplates()->list() as $item) {
            $this->assertInstanceOf(DocBeeDocumentTemplateDTO::class, $item);
        }
    }

    // ── DocumentRecurrence ────────────────────────────────────────────────────

    public function testDocumentRecurrenceListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documentRecurrences()->list());
    }

    public function testDocumentRecurrenceListItemsAreDocBeeDocumentRecurrenceDTOs(): void
    {
        foreach ($this->client->documentRecurrences()->list() as $item) {
            $this->assertInstanceOf(DocBeeDocumentRecurrenceDTO::class, $item);
        }
    }

    // ── DocBeeDocumentSiteConfig ──────────────────────────────────────────────

    public function testDocBeeDocumentSiteConfigGetReturnsSiteConfigDTO(): void
    {
        $dto = $this->client->docBeeDocumentSiteConfig()->get();
        $this->assertInstanceOf(SiteConfigDTO::class, $dto);
    }
}
