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
        $result = $this->callApi(fn() => $this->client->objectCategories()->list());
        $this->assertIsArray($result);
    }

    public function testObjectCategoryListItemsAreObjectCategoryDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->objectCategories()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ObjectCategoryDTO::class, $item);
        }
    }

    // ── ObserverCategory ──────────────────────────────────────────────────────

    public function testObserverCategoryListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->observerCategories()->list());
        $this->assertIsArray($result);
    }

    public function testObserverCategoryListItemsAreObserverCategoryDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->observerCategories()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ObserverCategoryDTO::class, $item);
        }
    }

    // ── ObserverType ──────────────────────────────────────────────────────────

    public function testObserverTypeListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->observerTypes()->list());
        $this->assertIsArray($result);
    }

    public function testObserverTypeListItemsAreObserverTypeDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->observerTypes()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ObserverTypeDTO::class, $item);
        }
    }

    // ── ObserverUser ──────────────────────────────────────────────────────────

    public function testObserverUserListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->observerUsers()->list());
        $this->assertIsArray($result);
    }

    public function testObserverUserListItemsAreObserverUserDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->observerUsers()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ObserverUserDTO::class, $item);
        }
    }

    // ── ConfidentialTag ───────────────────────────────────────────────────────

    public function testConfidentialTagListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->confidentialTags()->list());
        $this->assertIsArray($result);
    }

    public function testConfidentialTagListItemsAreConfidentialTagDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->confidentialTags()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ConfidentialTagDTO::class, $item);
        }
    }

    // ── PermissionGroup ───────────────────────────────────────────────────────

    public function testPermissionGroupListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->permissionGroups()->list());
        $this->assertIsArray($result);
    }

    public function testPermissionGroupListItemsArePermissionGroupDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->permissionGroups()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PermissionGroupDTO::class, $item);
        }
    }

    // ── DailyClosingConfig ────────────────────────────────────────────────────

    public function testDailyClosingConfigListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->dailyClosingConfigs()->list());
        $this->assertIsArray($result);
    }

    public function testDailyClosingConfigListItemsAreDailyClosingConfigDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->dailyClosingConfigs()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DailyClosingConfigDTO::class, $item);
        }
    }

    // ── TableConfigStorage ────────────────────────────────────────────────────

    public function testTableConfigStorageListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->tableConfigStorages()->list());
        $this->assertIsArray($result);
    }

    public function testTableConfigStorageListItemsAreTableConfigStorageDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->tableConfigStorages()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TableConfigStorageDTO::class, $item);
        }
    }

    // ── SelectionCategory ─────────────────────────────────────────────────────

    public function testSelectionCategoryListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->selectionCategories()->list());
        $this->assertIsArray($result);
    }

    public function testSelectionCategoryListItemsAreSelectionCategoryDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->selectionCategories()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
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
        $result = $this->callApi(fn() => $this->client->selectionValues($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testSelectionValueListItemsAreSelectionValueDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SELECTION_VALUES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SELECTION_VALUES_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->selectionValues($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SelectionValueDTO::class, $item);
        }
    }

    // ── PresetProfile ─────────────────────────────────────────────────────────

    public function testPresetProfileListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->presetProfiles()->list());
        $this->assertIsArray($result);
    }

    public function testPresetProfileListItemsArePresetProfileDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->presetProfiles()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PresetProfileDTO::class, $item);
        }
    }

    public function testPresetProfileFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PRESET_PROFILE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PRESET_PROFILE_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->presetProfiles()->find($id));
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
        $result = $this->callApi(fn() => $this->client->presetValues($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testPresetValueListItemsArePresetValueDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PRESET_VALUES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PRESET_VALUES_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->presetValues($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PresetValueDTO::class, $item);
        }
    }

    // ── SlaProfile ────────────────────────────────────────────────────────────

    public function testSlaProfileListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->slaProfiles()->list());
        $this->assertIsArray($result);
    }

    public function testSlaProfileListItemsAreSlaProfileDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->slaProfiles()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SlaProfileDTO::class, $item);
        }
    }

    public function testSlaProfileFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_ID in tests/.env.test to enable.');
        }
        $dto = $this->callApi(fn() => $this->client->slaProfiles()->find($id));
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
        $result = $this->callApi(fn() => $this->client->slaProfileSpecializations($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testSlaProfileSpecializationListItemsAreSlaProfileSpecializationDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->slaProfileSpecializations($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
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
        $result = $this->callApi(fn() => $this->client->slaProfileWorkingHours($parentId)->list());
        $this->assertIsArray($result);
    }

    public function testSlaProfileWorkingHourListItemsAreSlaProfileWorkingHourDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_SLA_PROFILE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $result = $this->callApi(fn() => $this->client->slaProfileWorkingHours($parentId)->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SlaProfileWorkingHourDTO::class, $item);
        }
    }
}
