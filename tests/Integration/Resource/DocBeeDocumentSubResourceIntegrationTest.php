<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentConflictDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentMessageDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTaskDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateTaskTemplateDTO;
use miralsoft\docbee\api\DTO\MaterialDTO;
use miralsoft\docbee\api\DTO\MaterialTemplateDTO;
use miralsoft\docbee\api\DTO\PlanningTimeDTO;
use miralsoft\docbee\api\DTO\PlanningTimeTemplateDTO;
use miralsoft\docbee\api\DTO\TravelLogDTO;
use miralsoft\docbee\api\DTO\TravelLogTemplateDTO;
use miralsoft\docbee\api\DTO\WorkLogDTO;
use miralsoft\docbee\api\DTO\WorkLogTemplateDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for DocBeeDocument sub-resources — read-only.
 *
 * All tests in this class require a parent document ID:
 *   DOCBEE_TEST_DOCUMENT_SUB_PARENT_ID — parent DocBeeDocument ID for most sub-resources
 *
 * Task-level sub-sub-resources additionally require:
 *   DOCBEE_TEST_DOCUMENT_TASK_SUB_PARENT_ID — task ID within that document
 */
final class DocBeeDocumentSubResourceIntegrationTest extends IntegrationTestCase
{
    private function documentId(): int
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_DOCUMENT_SUB_PARENT_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOCUMENT_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        return $id;
    }

    private function documentIdAndTaskId(): array
    {
        $docId  = $this->optionalIntEnv('DOCBEE_TEST_DOCUMENT_SUB_PARENT_ID');
        $taskId = $this->optionalIntEnv('DOCBEE_TEST_DOCUMENT_TASK_SUB_PARENT_ID');
        if ($docId === null || $taskId === null) {
            $this->markTestSkipped(
                'Set DOCBEE_TEST_DOCUMENT_SUB_PARENT_ID and DOCBEE_TEST_DOCUMENT_TASK_SUB_PARENT_ID in tests/.env.test to enable.'
            );
        }
        return [$docId, $taskId];
    }

    // ── DocBeeDocumentTask ────────────────────────────────────────────────────

    public function testDocBeeDocumentTaskListReturnsArray(): void
    {
        $this->assertIsArray($this->client->docBeeDocumentTasks($this->documentId())->list());
    }

    public function testDocBeeDocumentTaskListItemsAreDocBeeDocumentTaskDTOs(): void
    {
        foreach ($this->client->docBeeDocumentTasks($this->documentId())->list() as $item) {
            $this->assertInstanceOf(DocBeeDocumentTaskDTO::class, $item);
        }
    }

    // ── DocBeeDocumentTaskMaterial ────────────────────────────────────────────

    public function testDocBeeDocumentTaskMaterialListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $this->assertIsArray($this->client->docBeeDocumentTaskMaterials($docId, $taskId)->list());
    }

    public function testDocBeeDocumentTaskMaterialListItemsAreMaterialDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        foreach ($this->client->docBeeDocumentTaskMaterials($docId, $taskId)->list() as $item) {
            $this->assertInstanceOf(MaterialDTO::class, $item);
        }
    }

    // ── DocBeeDocumentTaskPlanningTime ────────────────────────────────────────

    public function testDocBeeDocumentTaskPlanningTimeListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $this->assertIsArray($this->client->docBeeDocumentTaskPlanningTimes($docId, $taskId)->list());
    }

    public function testDocBeeDocumentTaskPlanningTimeListItemsArePlanningTimeDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        foreach ($this->client->docBeeDocumentTaskPlanningTimes($docId, $taskId)->list() as $item) {
            $this->assertInstanceOf(PlanningTimeDTO::class, $item);
        }
    }

    // ── DocBeeDocumentTaskWorkLog ─────────────────────────────────────────────

    public function testDocBeeDocumentTaskWorkLogListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $this->assertIsArray($this->client->docBeeDocumentTaskWorkLogs($docId, $taskId)->list());
    }

    public function testDocBeeDocumentTaskWorkLogListItemsAreWorkLogDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        foreach ($this->client->docBeeDocumentTaskWorkLogs($docId, $taskId)->list() as $item) {
            $this->assertInstanceOf(WorkLogDTO::class, $item);
        }
    }

    // ── DocumentTravelLog ─────────────────────────────────────────────────────

    public function testDocumentTravelLogListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documentTravelLogs($this->documentId())->list());
    }

    public function testDocumentTravelLogListItemsAreTravelLogDTOs(): void
    {
        foreach ($this->client->documentTravelLogs($this->documentId())->list() as $item) {
            $this->assertInstanceOf(TravelLogDTO::class, $item);
        }
    }

    // ── DocumentMessage ───────────────────────────────────────────────────────

    public function testDocumentMessageListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documentMessages($this->documentId())->list());
    }

    public function testDocumentMessageListItemsAreDocBeeDocumentMessageDTOs(): void
    {
        foreach ($this->client->documentMessages($this->documentId())->list() as $item) {
            $this->assertInstanceOf(DocBeeDocumentMessageDTO::class, $item);
        }
    }

    // ── DocumentConflict ──────────────────────────────────────────────────────

    public function testDocumentConflictListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documentConflicts($this->documentId())->list());
    }

    public function testDocumentConflictListItemsAreDocBeeDocumentConflictDTOs(): void
    {
        foreach ($this->client->documentConflicts($this->documentId())->list() as $item) {
            $this->assertInstanceOf(DocBeeDocumentConflictDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplate ──────────────────────────────────────────

    public function testDocumentTemplateTaskTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documentTemplateTaskTemplates($this->documentId())->list());
    }

    public function testDocumentTemplateTaskTemplateListItemsAreDocBeeDocumentTemplateTaskTemplateDTOs(): void
    {
        foreach ($this->client->documentTemplateTaskTemplates($this->documentId())->list() as $item) {
            $this->assertInstanceOf(DocBeeDocumentTemplateTaskTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTravelLogTemplate ─────────────────────────────────────

    public function testDocumentTemplateTravelLogTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->documentTemplateTravelLogTemplates($this->documentId())->list());
    }

    public function testDocumentTemplateTravelLogTemplateListItemsAreTravelLogTemplateDTOs(): void
    {
        foreach ($this->client->documentTemplateTravelLogTemplates($this->documentId())->list() as $item) {
            $this->assertInstanceOf(TravelLogTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplateMaterial ──────────────────────────────────

    public function testDocumentTemplateTaskTemplateMaterialListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $this->assertIsArray($this->client->documentTemplateTaskTemplateMaterials($docId, $taskId)->list());
    }

    public function testDocumentTemplateTaskTemplateMaterialListItemsAreMaterialTemplateDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        foreach ($this->client->documentTemplateTaskTemplateMaterials($docId, $taskId)->list() as $item) {
            $this->assertInstanceOf(MaterialTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplatePlanningTime ──────────────────────────────

    public function testDocumentTemplateTaskTemplatePlanningTimeListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $this->assertIsArray($this->client->documentTemplateTaskTemplatePlanningTimes($docId, $taskId)->list());
    }

    public function testDocumentTemplateTaskTemplatePlanningTimeListItemsArePlanningTimeTemplateDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        foreach ($this->client->documentTemplateTaskTemplatePlanningTimes($docId, $taskId)->list() as $item) {
            $this->assertInstanceOf(PlanningTimeTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplateWorkLog ───────────────────────────────────

    public function testDocumentTemplateTaskTemplateWorkLogListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $this->assertIsArray($this->client->documentTemplateTaskTemplateWorkLogs($docId, $taskId)->list());
    }

    public function testDocumentTemplateTaskTemplateWorkLogListItemsAreWorkLogTemplateDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        foreach ($this->client->documentTemplateTaskTemplateWorkLogs($docId, $taskId)->list() as $item) {
            $this->assertInstanceOf(WorkLogTemplateDTO::class, $item);
        }
    }
}
