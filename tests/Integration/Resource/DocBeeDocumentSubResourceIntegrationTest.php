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
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTasks($this->documentId())->list());
        $this->assertIsArray($result);
    }

    public function testDocBeeDocumentTaskListItemsAreDocBeeDocumentTaskDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTasks($this->documentId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentTaskDTO::class, $item);
        }
    }

    // ── DocBeeDocumentTaskMaterial ────────────────────────────────────────────

    public function testDocBeeDocumentTaskMaterialListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTaskMaterials($docId, $taskId)->list());
        $this->assertIsArray($result);
    }

    public function testDocBeeDocumentTaskMaterialListItemsAreMaterialDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTaskMaterials($docId, $taskId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(MaterialDTO::class, $item);
        }
    }

    // ── DocBeeDocumentTaskPlanningTime ────────────────────────────────────────

    public function testDocBeeDocumentTaskPlanningTimeListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTaskPlanningTimes($docId, $taskId)->list());
        $this->assertIsArray($result);
    }

    public function testDocBeeDocumentTaskPlanningTimeListItemsArePlanningTimeDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTaskPlanningTimes($docId, $taskId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PlanningTimeDTO::class, $item);
        }
    }

    // ── DocBeeDocumentTaskWorkLog ─────────────────────────────────────────────

    public function testDocBeeDocumentTaskWorkLogListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTaskWorkLogs($docId, $taskId)->list());
        $this->assertIsArray($result);
    }

    public function testDocBeeDocumentTaskWorkLogListItemsAreWorkLogDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->docBeeDocumentTaskWorkLogs($docId, $taskId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(WorkLogDTO::class, $item);
        }
    }

    // ── DocumentTravelLog ─────────────────────────────────────────────────────

    public function testDocumentTravelLogListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTravelLogs($this->documentId())->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTravelLogListItemsAreTravelLogDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTravelLogs($this->documentId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TravelLogDTO::class, $item);
        }
    }

    // ── DocumentMessage ───────────────────────────────────────────────────────

    public function testDocumentMessageListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentMessages($this->documentId())->list());
        $this->assertIsArray($result);
    }

    public function testDocumentMessageListItemsAreDocBeeDocumentMessageDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentMessages($this->documentId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentMessageDTO::class, $item);
        }
    }

    // ── DocumentConflict ──────────────────────────────────────────────────────

    public function testDocumentConflictListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentConflicts($this->documentId())->list());
        $this->assertIsArray($result);
    }

    public function testDocumentConflictListItemsAreDocBeeDocumentConflictDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentConflicts($this->documentId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentConflictDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplate ──────────────────────────────────────────

    public function testDocumentTemplateTaskTemplateListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplates($this->documentId())->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTemplateTaskTemplateListItemsAreDocBeeDocumentTemplateTaskTemplateDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplates($this->documentId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentTemplateTaskTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTravelLogTemplate ─────────────────────────────────────

    public function testDocumentTemplateTravelLogTemplateListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTemplateTravelLogTemplates($this->documentId())->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTemplateTravelLogTemplateListItemsAreTravelLogTemplateDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->documentTemplateTravelLogTemplates($this->documentId())->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TravelLogTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplateMaterial ──────────────────────────────────

    public function testDocumentTemplateTaskTemplateMaterialListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplateMaterials($docId, $taskId)->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTemplateTaskTemplateMaterialListItemsAreMaterialTemplateDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplateMaterials($docId, $taskId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(MaterialTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplatePlanningTime ──────────────────────────────

    public function testDocumentTemplateTaskTemplatePlanningTimeListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplatePlanningTimes($docId, $taskId)->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTemplateTaskTemplatePlanningTimeListItemsArePlanningTimeTemplateDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplatePlanningTimes($docId, $taskId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PlanningTimeTemplateDTO::class, $item);
        }
    }

    // ── DocumentTemplateTaskTemplateWorkLog ───────────────────────────────────

    public function testDocumentTemplateTaskTemplateWorkLogListReturnsArray(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplateWorkLogs($docId, $taskId)->list());
        $this->assertIsArray($result);
    }

    public function testDocumentTemplateTaskTemplateWorkLogListItemsAreWorkLogTemplateDTOs(): void
    {
        [$docId, $taskId] = $this->documentIdAndTaskId();
        $result = $this->callApi(fn() => $this->client->documentTemplateTaskTemplateWorkLogs($docId, $taskId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(WorkLogTemplateDTO::class, $item);
        }
    }
}
