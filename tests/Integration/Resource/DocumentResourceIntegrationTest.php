<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentRecurrenceDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTaskDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateProfileDTO;
use miralsoft\docbee\api\DTO\SiteConfigDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for document-related resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_DOCUMENT_ID      — enables find($id) test for Document
 *   DOCBEE_TEST_DOCUMENT_TASK_ID — enables find($id) test for DocumentTask (top-level)
 */
final class DocumentResourceIntegrationTest extends IntegrationTestCase
{
    // ── Document ──────────────────────────────────────────────────────────────

    public function testDocumentListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documents()->list());
        $this->assertIsArray($result);
    }

    public function testDocumentListItemsAreDocBeeDocumentDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documents()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentDTO::class, $item);
        }
    }

    public function testDocumentFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_DOCUMENT_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOCUMENT_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->documents()->find($id));
        $this->assertInstanceOf(DocBeeDocumentDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── DocumentTemplate ──────────────────────────────────────────────────────

    public function testDocumentTemplateListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTemplates()->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTemplateListItemsAreDocBeeDocumentTemplateDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTemplates()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentTemplateDTO::class, $item);
        }
    }

    // ── DocumentRecurrence ────────────────────────────────────────────────────

    public function testDocumentRecurrenceListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentRecurrences()->list());
        $this->assertIsArray($result);
    }

    public function testDocumentRecurrenceListItemsAreDocBeeDocumentRecurrenceDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentRecurrences()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentRecurrenceDTO::class, $item);
        }
    }

    // ── DocumentTask (top-level) ──────────────────────────────────────────────

    public function testDocumentTaskListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTasks()->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTaskListItemsAreDocBeeDocumentTaskDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTasks()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentTaskDTO::class, $item);
        }
    }

    public function testDocumentTaskFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_DOCUMENT_TASK_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOCUMENT_TASK_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->documentTasks()->find($id));
        $this->assertInstanceOf(DocBeeDocumentTaskDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── DocBeeDocumentSiteConfig ──────────────────────────────────────────────

    public function testDocBeeDocumentSiteConfigGetReturnsSiteConfigDTO(): void
    {
        $dto = $this->client->docBeeDocumentSiteConfig()->get();
        $this->assertInstanceOf(SiteConfigDTO::class, $dto);
    }
}
