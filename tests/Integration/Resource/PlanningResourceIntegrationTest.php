<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\ContingentDTO;
use miralsoft\docbee\api\DTO\ContingentElementDTO;
use miralsoft\docbee\api\DTO\ContingentItemDTO;
use miralsoft\docbee\api\DTO\ContingentItemRecurrenceDTO;
use miralsoft\docbee\api\DTO\CostEstimationDTO;
use miralsoft\docbee\api\DTO\CostEstimationTaskDTO;
use miralsoft\docbee\api\DTO\CostEstimationTaskTemplateDTO;
use miralsoft\docbee\api\DTO\CostEstimationTemplateDTO;
use miralsoft\docbee\api\DTO\DashboardDTO;
use miralsoft\docbee\api\DTO\DashboardWidgetDTO;
use miralsoft\docbee\api\DTO\TaskTemplateDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for planning resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_CONTINGENT_ID                    — enables find($id) for Contingent
 *   DOCBEE_TEST_COST_ESTIMATION_ID               — enables find($id) for CostEstimation
 *   DOCBEE_TEST_COST_ESTIMATION_TEMPLATE_ID      — enables find($id) for CostEstimationTemplate
 *   DOCBEE_TEST_DASHBOARD_ID                     — enables find($id) for Dashboard
 *   DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID    — enables contingentElements/Items/Recurrences sub-tests
 *   DOCBEE_TEST_COST_ESTIMATION_TASKS_PARENT_ID  — enables costEstimationTasks sub-tests
 *   DOCBEE_TEST_COST_ESTIMATION_TASK_TEMPLATES_PARENT_ID — enables costEstimationTaskTemplates sub-tests
 *   DOCBEE_TEST_DASHBOARD_WIDGETS_PARENT_ID      — enables dashboardWidgets sub-tests
 */
final class PlanningResourceIntegrationTest extends IntegrationTestCase
{
    // ── Contingent ────────────────────────────────────────────────────────────

    public function testContingentListReturnsArray(): void
    {
        $this->assertIsArray($this->client->contingents()->list());
    }

    public function testContingentListItemsAreContingentDTOs(): void
    {
        foreach ($this->client->contingents()->list() as $item) {
            $this->assertInstanceOf(ContingentDTO::class, $item);
        }
    }

    public function testContingentFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_CONTINGENT_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CONTINGENT_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->contingents()->find($id);
        $this->assertInstanceOf(ContingentDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── ContingentElement (sub-resource) ──────────────────────────────────────

    public function testContingentElementListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->contingentElements($parentId)->list());
    }

    public function testContingentElementListItemsAreContingentElementDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->contingentElements($parentId)->list() as $item) {
            $this->assertInstanceOf(ContingentElementDTO::class, $item);
        }
    }

    // ── ContingentItem (sub-resource) ─────────────────────────────────────────

    public function testContingentItemListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->contingentItems($parentId)->list());
    }

    public function testContingentItemListItemsAreContingentItemDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->contingentItems($parentId)->list() as $item) {
            $this->assertInstanceOf(ContingentItemDTO::class, $item);
        }
    }

    // ── ContingentItemRecurrence (sub-resource) ───────────────────────────────

    public function testContingentItemRecurrenceListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->contingentItemRecurrences($parentId)->list());
    }

    public function testContingentItemRecurrenceListItemsAreContingentItemRecurrenceDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_CONTINGENT_ELEMENTS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->contingentItemRecurrences($parentId)->list() as $item) {
            $this->assertInstanceOf(ContingentItemRecurrenceDTO::class, $item);
        }
    }

    // ── CostEstimation ────────────────────────────────────────────────────────

    public function testCostEstimationListReturnsArray(): void
    {
        $this->assertIsArray($this->client->costEstimations()->list());
    }

    public function testCostEstimationListItemsAreCostEstimationDTOs(): void
    {
        foreach ($this->client->costEstimations()->list() as $item) {
            $this->assertInstanceOf(CostEstimationDTO::class, $item);
        }
    }

    public function testCostEstimationFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_COST_ESTIMATION_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_COST_ESTIMATION_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->costEstimations()->find($id);
        $this->assertInstanceOf(CostEstimationDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── CostEstimationTask (sub-resource) ─────────────────────────────────────

    public function testCostEstimationTaskListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_COST_ESTIMATION_TASKS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_COST_ESTIMATION_TASKS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->costEstimationTasks($parentId)->list());
    }

    public function testCostEstimationTaskListItemsAreCostEstimationTaskDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_COST_ESTIMATION_TASKS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_COST_ESTIMATION_TASKS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->costEstimationTasks($parentId)->list() as $item) {
            $this->assertInstanceOf(CostEstimationTaskDTO::class, $item);
        }
    }

    // ── CostEstimationTemplate ────────────────────────────────────────────────

    public function testCostEstimationTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->costEstimationTemplates()->list());
    }

    public function testCostEstimationTemplateListItemsAreCostEstimationTemplateDTOs(): void
    {
        foreach ($this->client->costEstimationTemplates()->list() as $item) {
            $this->assertInstanceOf(CostEstimationTemplateDTO::class, $item);
        }
    }

    public function testCostEstimationTemplateFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_COST_ESTIMATION_TEMPLATE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_COST_ESTIMATION_TEMPLATE_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->costEstimationTemplates()->find($id);
        $this->assertInstanceOf(CostEstimationTemplateDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── CostEstimationTaskTemplate (sub-resource) ─────────────────────────────

    public function testCostEstimationTaskTemplateListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_COST_ESTIMATION_TASK_TEMPLATES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_COST_ESTIMATION_TASK_TEMPLATES_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->costEstimationTaskTemplates($parentId)->list());
    }

    public function testCostEstimationTaskTemplateListItemsAreCostEstimationTaskTemplateDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_COST_ESTIMATION_TASK_TEMPLATES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_COST_ESTIMATION_TASK_TEMPLATES_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->costEstimationTaskTemplates($parentId)->list() as $item) {
            $this->assertInstanceOf(CostEstimationTaskTemplateDTO::class, $item);
        }
    }

    // ── TaskTemplate ──────────────────────────────────────────────────────────

    public function testTaskTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->taskTemplates()->list());
    }

    public function testTaskTemplateListItemsAreTaskTemplateDTOs(): void
    {
        foreach ($this->client->taskTemplates()->list() as $item) {
            $this->assertInstanceOf(TaskTemplateDTO::class, $item);
        }
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function testDashboardListReturnsArray(): void
    {
        $this->assertIsArray($this->client->dashboards()->list());
    }

    public function testDashboardListItemsAreDashboardDTOs(): void
    {
        foreach ($this->client->dashboards()->list() as $item) {
            $this->assertInstanceOf(DashboardDTO::class, $item);
        }
    }

    public function testDashboardFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_DASHBOARD_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DASHBOARD_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->dashboards()->find($id);
        $this->assertInstanceOf(DashboardDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── DashboardWidget (sub-resource) ────────────────────────────────────────

    public function testDashboardWidgetListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_DASHBOARD_WIDGETS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DASHBOARD_WIDGETS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->dashboardWidgets($parentId)->list());
    }

    public function testDashboardWidgetListItemsAreDashboardWidgetDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_DASHBOARD_WIDGETS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DASHBOARD_WIDGETS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->dashboardWidgets($parentId)->list() as $item) {
            $this->assertInstanceOf(DashboardWidgetDTO::class, $item);
        }
    }
}
