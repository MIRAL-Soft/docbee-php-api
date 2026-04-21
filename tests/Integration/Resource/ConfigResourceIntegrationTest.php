<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\ConfidentialTagDTO;
use miralsoft\docbee\api\DTO\DailyClosingConfigDTO;
use miralsoft\docbee\api\DTO\ObjectCategoryDTO;
use miralsoft\docbee\api\DTO\ObserverCategoryDTO;
use miralsoft\docbee\api\DTO\ObserverTypeDTO;
use miralsoft\docbee\api\DTO\ObserverUserDTO;
use miralsoft\docbee\api\DTO\PermissionGroupDTO;
use miralsoft\docbee\api\DTO\PresetProfileDTO;
use miralsoft\docbee\api\DTO\PresetValueDTO;
use miralsoft\docbee\api\DTO\SelectionCategoryDTO;
use miralsoft\docbee\api\DTO\SelectionValueDTO;
use miralsoft\docbee\api\DTO\SlaProfileDTO;
use miralsoft\docbee\api\DTO\SlaProfileSpecializationDTO;
use miralsoft\docbee\api\DTO\SlaProfileWorkingHourDTO;
use miralsoft\docbee\api\DTO\TableConfigStorageDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for configuration and profile resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_PRESET_PROFILE_ID        — enables find($id) for PresetProfile
 *   DOCBEE_TEST_SLA_PROFILE_ID           — enables find($id) for SlaProfile
 *   DOCBEE_TEST_PRESET_VALUES_PARENT_ID      — enables presetValues sub-tests
 *   DOCBEE_TEST_SELECTION_VALUES_PARENT_ID   — enables selectionValues sub-tests
 *   DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID    — enables slaProfileSpecializations/WorkingHours sub-tests
 */
final class ConfigResourceIntegrationTest extends IntegrationTestCase
{
    // ── ObjectCategory ────────────────────────────────────────────────────────

    public function testObjectCategoryListReturnsArray(): void
    {
        $this->assertIsArray($this->client->objectCategories()->list());
    }

    public function testObjectCategoryListItemsAreObjectCategoryDTOs(): void
    {
        foreach ($this->client->objectCategories()->list() as $item) {
            $this->assertInstanceOf(ObjectCategoryDTO::class, $item);
        }
    }

    // ── ObserverCategory ──────────────────────────────────────────────────────

    public function testObserverCategoryListReturnsArray(): void
    {
        $this->assertIsArray($this->client->observerCategories()->list());
    }

    public function testObserverCategoryListItemsAreObserverCategoryDTOs(): void
    {
        foreach ($this->client->observerCategories()->list() as $item) {
            $this->assertInstanceOf(ObserverCategoryDTO::class, $item);
        }
    }

    // ── ObserverType ──────────────────────────────────────────────────────────

    public function testObserverTypeListReturnsArray(): void
    {
        $this->assertIsArray($this->client->observerTypes()->list());
    }

    public function testObserverTypeListItemsAreObserverTypeDTOs(): void
    {
        foreach ($this->client->observerTypes()->list() as $item) {
            $this->assertInstanceOf(ObserverTypeDTO::class, $item);
        }
    }

    // ── ObserverUser ──────────────────────────────────────────────────────────

    public function testObserverUserListReturnsArray(): void
    {
        $this->assertIsArray($this->client->observerUsers()->list());
    }

    public function testObserverUserListItemsAreObserverUserDTOs(): void
    {
        foreach ($this->client->observerUsers()->list() as $item) {
            $this->assertInstanceOf(ObserverUserDTO::class, $item);
        }
    }

    // ── ConfidentialTag ───────────────────────────────────────────────────────

    public function testConfidentialTagListReturnsArray(): void
    {
        $this->assertIsArray($this->client->confidentialTags()->list());
    }

    public function testConfidentialTagListItemsAreConfidentialTagDTOs(): void
    {
        foreach ($this->client->confidentialTags()->list() as $item) {
            $this->assertInstanceOf(ConfidentialTagDTO::class, $item);
        }
    }

    // ── PermissionGroup ───────────────────────────────────────────────────────

    public function testPermissionGroupListReturnsArray(): void
    {
        $this->assertIsArray($this->client->permissionGroups()->list());
    }

    public function testPermissionGroupListItemsArePermissionGroupDTOs(): void
    {
        foreach ($this->client->permissionGroups()->list() as $item) {
            $this->assertInstanceOf(PermissionGroupDTO::class, $item);
        }
    }

    // ── DailyClosingConfig ────────────────────────────────────────────────────

    public function testDailyClosingConfigListReturnsArray(): void
    {
        $this->assertIsArray($this->client->dailyClosingConfigs()->list());
    }

    public function testDailyClosingConfigListItemsAreDailyClosingConfigDTOs(): void
    {
        foreach ($this->client->dailyClosingConfigs()->list() as $item) {
            $this->assertInstanceOf(DailyClosingConfigDTO::class, $item);
        }
    }

    // ── TableConfigStorage ────────────────────────────────────────────────────

    public function testTableConfigStorageListReturnsArray(): void
    {
        $this->assertIsArray($this->client->tableConfigStorages()->list());
    }

    public function testTableConfigStorageListItemsAreTableConfigStorageDTOs(): void
    {
        foreach ($this->client->tableConfigStorages()->list() as $item) {
            $this->assertInstanceOf(TableConfigStorageDTO::class, $item);
        }
    }

    // ── SelectionCategory ─────────────────────────────────────────────────────

    public function testSelectionCategoryListReturnsArray(): void
    {
        $this->assertIsArray($this->client->selectionCategories()->list());
    }

    public function testSelectionCategoryListItemsAreSelectionCategoryDTOs(): void
    {
        foreach ($this->client->selectionCategories()->list() as $item) {
            $this->assertInstanceOf(SelectionCategoryDTO::class, $item);
        }
    }

    // ── SelectionValue (sub-resource) ─────────────────────────────────────────

    public function testSelectionValueListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SELECTION_VALUES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SELECTION_VALUES_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->selectionValues($parentId)->list());
    }

    public function testSelectionValueListItemsAreSelectionValueDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SELECTION_VALUES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SELECTION_VALUES_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->selectionValues($parentId)->list() as $item) {
            $this->assertInstanceOf(SelectionValueDTO::class, $item);
        }
    }

    // ── PresetProfile ─────────────────────────────────────────────────────────

    public function testPresetProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->presetProfiles()->list());
    }

    public function testPresetProfileListItemsArePresetProfileDTOs(): void
    {
        foreach ($this->client->presetProfiles()->list() as $item) {
            $this->assertInstanceOf(PresetProfileDTO::class, $item);
        }
    }

    public function testPresetProfileFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PRESET_PROFILE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PRESET_PROFILE_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->presetProfiles()->find($id);
        $this->assertInstanceOf(PresetProfileDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── PresetValue (sub-resource) ────────────────────────────────────────────

    public function testPresetValueListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PRESET_VALUES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PRESET_VALUES_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->presetValues($parentId)->list());
    }

    public function testPresetValueListItemsArePresetValueDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PRESET_VALUES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PRESET_VALUES_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->presetValues($parentId)->list() as $item) {
            $this->assertInstanceOf(PresetValueDTO::class, $item);
        }
    }

    // ── SlaProfile ────────────────────────────────────────────────────────────

    public function testSlaProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->slaProfiles()->list());
    }

    public function testSlaProfileListItemsAreSlaProfileDTOs(): void
    {
        foreach ($this->client->slaProfiles()->list() as $item) {
            $this->assertInstanceOf(SlaProfileDTO::class, $item);
        }
    }

    public function testSlaProfileFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->slaProfiles()->find($id);
        $this->assertInstanceOf(SlaProfileDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── SlaProfileSpecialization (sub-resource) ───────────────────────────────

    public function testSlaProfileSpecializationListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->slaProfileSpecializations($parentId)->list());
    }

    public function testSlaProfileSpecializationListItemsAreSlaProfileSpecializationDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->slaProfileSpecializations($parentId)->list() as $item) {
            $this->assertInstanceOf(SlaProfileSpecializationDTO::class, $item);
        }
    }

    // ── SlaProfileWorkingHour (sub-resource) ──────────────────────────────────

    public function testSlaProfileWorkingHourListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->slaProfileWorkingHours($parentId)->list());
    }

    public function testSlaProfileWorkingHourListItemsAreSlaProfileWorkingHourDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->slaProfileWorkingHours($parentId)->list() as $item) {
            $this->assertInstanceOf(SlaProfileWorkingHourDTO::class, $item);
        }
    }
}
