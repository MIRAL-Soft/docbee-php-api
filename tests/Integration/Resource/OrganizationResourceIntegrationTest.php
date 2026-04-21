<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\CustomColorDTO;
use miralsoft\docbee\api\DTO\CustomFieldDTO;
use miralsoft\docbee\api\DTO\DepartmentDTO;
use miralsoft\docbee\api\DTO\DepartmentProfileDTO;
use miralsoft\docbee\api\DTO\DueDateColorDTO;
use miralsoft\docbee\api\DTO\MaterialItemDTO;
use miralsoft\docbee\api\DTO\PriorityDTO;
use miralsoft\docbee\api\DTO\QueueDTO;
use miralsoft\docbee\api\DTO\RequestTypeDTO;
use miralsoft\docbee\api\DTO\ServiceProviderDTO;
use miralsoft\docbee\api\DTO\ServiceProviderUserDTO;
use miralsoft\docbee\api\DTO\ServiceTypeDTO;
use miralsoft\docbee\api\DTO\ServiceTypeProfileDTO;
use miralsoft\docbee\api\DTO\SkillDTO;
use miralsoft\docbee\api\DTO\TagDTO;
use miralsoft\docbee\api\DTO\TravelTypeDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for organisation and configuration resources — read-only.
 */
final class OrganizationResourceIntegrationTest extends IntegrationTestCase
{
    // ── Department ────────────────────────────────────────────────────────────

    public function testDepartmentListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->departments()->list());
        $this->assertIsArray($result);
    }

    public function testDepartmentListItemsAreDepartmentDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->departments()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DepartmentDTO::class, $item);
        }
    }

    // ── DepartmentProfile ─────────────────────────────────────────────────────

    public function testDepartmentProfileListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->departmentProfiles()->list());
        $this->assertIsArray($result);
    }

    public function testDepartmentProfileListItemsAreDepartmentProfileDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->departmentProfiles()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DepartmentProfileDTO::class, $item);
        }
    }

    // ── Queue ─────────────────────────────────────────────────────────────────

    public function testQueueListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->queues()->list());
        $this->assertIsArray($result);
    }

    public function testQueueListItemsAreQueueDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->queues()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(QueueDTO::class, $item);
        }
    }

    // ── RequestType ───────────────────────────────────────────────────────────

    public function testRequestTypeListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->requestTypes()->list());
        $this->assertIsArray($result);
    }

    public function testRequestTypeListItemsAreRequestTypeDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->requestTypes()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(RequestTypeDTO::class, $item);
        }
    }

    // ── ServiceType ───────────────────────────────────────────────────────────

    public function testServiceTypeListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceTypes()->list());
        $this->assertIsArray($result);
    }

    public function testServiceTypeListItemsAreServiceTypeDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceTypes()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ServiceTypeDTO::class, $item);
        }
    }

    // ── ServiceTypeProfile ────────────────────────────────────────────────────

    public function testServiceTypeProfileListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceTypeProfiles()->list());
        $this->assertIsArray($result);
    }

    public function testServiceTypeProfileListItemsAreServiceTypeProfileDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceTypeProfiles()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ServiceTypeProfileDTO::class, $item);
        }
    }

    // ── ServiceProvider ───────────────────────────────────────────────────────

    public function testServiceProviderListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceProviders()->list());
        $this->assertIsArray($result);
    }

    public function testServiceProviderListItemsAreServiceProviderDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceProviders()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ServiceProviderDTO::class, $item);
        }
    }

    // ── ServiceProviderUser ───────────────────────────────────────────────────

    public function testServiceProviderUserListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceProviderUsers()->list());
        $this->assertIsArray($result);
    }

    public function testServiceProviderUserListItemsAreServiceProviderUserDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->serviceProviderUsers()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ServiceProviderUserDTO::class, $item);
        }
    }

    // ── Priority ──────────────────────────────────────────────────────────────

    public function testPriorityListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->priorities()->list());
        $this->assertIsArray($result);
    }

    public function testPriorityListItemsArePriorityDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->priorities()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PriorityDTO::class, $item);
        }
    }

    // ── Tag ───────────────────────────────────────────────────────────────────

    public function testTagListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->tags()->list());
        $this->assertIsArray($result);
    }

    public function testTagListItemsAreTagDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->tags()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TagDTO::class, $item);
        }
    }

    // ── Skill ─────────────────────────────────────────────────────────────────

    public function testSkillListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->skills()->list());
        $this->assertIsArray($result);
    }

    public function testSkillListItemsAreSkillDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->skills()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SkillDTO::class, $item);
        }
    }

    // ── CustomColor ───────────────────────────────────────────────────────────

    public function testCustomColorListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customColors()->list());
        $this->assertIsArray($result);
    }

    public function testCustomColorListItemsAreCustomColorDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customColors()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomColorDTO::class, $item);
        }
    }

    // ── CustomField ───────────────────────────────────────────────────────────

    public function testCustomFieldListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->customFields()->list());
        $this->assertIsArray($result);
    }

    public function testCustomFieldListItemsAreCustomFieldDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->customFields()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(CustomFieldDTO::class, $item);
        }
    }

    // ── DueDateColor ──────────────────────────────────────────────────────────

    public function testDueDateColorListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->dueDateColors()->list());
        $this->assertIsArray($result);
    }

    public function testDueDateColorListItemsAreDueDateColorDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->dueDateColors()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(DueDateColorDTO::class, $item);
        }
    }

    // ── TravelType ────────────────────────────────────────────────────────────

    public function testTravelTypeListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->travelTypes()->list());
        $this->assertIsArray($result);
    }

    public function testTravelTypeListItemsAreTravelTypeDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->travelTypes()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TravelTypeDTO::class, $item);
        }
    }

    // ── MaterialItem ──────────────────────────────────────────────────────────

    public function testMaterialItemListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->materialItems()->list());
        $this->assertIsArray($result);
    }

    public function testMaterialItemListItemsAreMaterialItemDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->materialItems()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(MaterialItemDTO::class, $item);
        }
    }
}
