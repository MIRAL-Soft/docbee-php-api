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
        $this->assertIsArray($this->client->departments()->list());
    }

    public function testDepartmentListItemsAreDepartmentDTOs(): void
    {
        foreach ($this->client->departments()->list() as $item) {
            $this->assertInstanceOf(DepartmentDTO::class, $item);
        }
    }

    // ── DepartmentProfile ─────────────────────────────────────────────────────

    public function testDepartmentProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->departmentProfiles()->list());
    }

    public function testDepartmentProfileListItemsAreDepartmentProfileDTOs(): void
    {
        foreach ($this->client->departmentProfiles()->list() as $item) {
            $this->assertInstanceOf(DepartmentProfileDTO::class, $item);
        }
    }

    // ── Queue ─────────────────────────────────────────────────────────────────

    public function testQueueListReturnsArray(): void
    {
        $this->assertIsArray($this->client->queues()->list());
    }

    public function testQueueListItemsAreQueueDTOs(): void
    {
        foreach ($this->client->queues()->list() as $item) {
            $this->assertInstanceOf(QueueDTO::class, $item);
        }
    }

    // ── RequestType ───────────────────────────────────────────────────────────

    public function testRequestTypeListReturnsArray(): void
    {
        $this->assertIsArray($this->client->requestTypes()->list());
    }

    public function testRequestTypeListItemsAreRequestTypeDTOs(): void
    {
        foreach ($this->client->requestTypes()->list() as $item) {
            $this->assertInstanceOf(RequestTypeDTO::class, $item);
        }
    }

    // ── ServiceType ───────────────────────────────────────────────────────────

    public function testServiceTypeListReturnsArray(): void
    {
        $this->assertIsArray($this->client->serviceTypes()->list());
    }

    public function testServiceTypeListItemsAreServiceTypeDTOs(): void
    {
        foreach ($this->client->serviceTypes()->list() as $item) {
            $this->assertInstanceOf(ServiceTypeDTO::class, $item);
        }
    }

    // ── ServiceTypeProfile ────────────────────────────────────────────────────

    public function testServiceTypeProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->serviceTypeProfiles()->list());
    }

    public function testServiceTypeProfileListItemsAreServiceTypeProfileDTOs(): void
    {
        foreach ($this->client->serviceTypeProfiles()->list() as $item) {
            $this->assertInstanceOf(ServiceTypeProfileDTO::class, $item);
        }
    }

    // ── ServiceProvider ───────────────────────────────────────────────────────

    public function testServiceProviderListReturnsArray(): void
    {
        $this->assertIsArray($this->client->serviceProviders()->list());
    }

    public function testServiceProviderListItemsAreServiceProviderDTOs(): void
    {
        foreach ($this->client->serviceProviders()->list() as $item) {
            $this->assertInstanceOf(ServiceProviderDTO::class, $item);
        }
    }

    // ── ServiceProviderUser ───────────────────────────────────────────────────

    public function testServiceProviderUserListReturnsArray(): void
    {
        $this->assertIsArray($this->client->serviceProviderUsers()->list());
    }

    public function testServiceProviderUserListItemsAreServiceProviderUserDTOs(): void
    {
        foreach ($this->client->serviceProviderUsers()->list() as $item) {
            $this->assertInstanceOf(ServiceProviderUserDTO::class, $item);
        }
    }

    // ── Priority ──────────────────────────────────────────────────────────────

    public function testPriorityListReturnsArray(): void
    {
        $this->assertIsArray($this->client->priorities()->list());
    }

    public function testPriorityListItemsArePriorityDTOs(): void
    {
        foreach ($this->client->priorities()->list() as $item) {
            $this->assertInstanceOf(PriorityDTO::class, $item);
        }
    }

    // ── Tag ───────────────────────────────────────────────────────────────────

    public function testTagListReturnsArray(): void
    {
        $this->assertIsArray($this->client->tags()->list());
    }

    public function testTagListItemsAreTagDTOs(): void
    {
        foreach ($this->client->tags()->list() as $item) {
            $this->assertInstanceOf(TagDTO::class, $item);
        }
    }

    // ── Skill ─────────────────────────────────────────────────────────────────

    public function testSkillListReturnsArray(): void
    {
        $this->assertIsArray($this->client->skills()->list());
    }

    public function testSkillListItemsAreSkillDTOs(): void
    {
        foreach ($this->client->skills()->list() as $item) {
            $this->assertInstanceOf(SkillDTO::class, $item);
        }
    }

    // ── CustomColor ───────────────────────────────────────────────────────────

    public function testCustomColorListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customColors()->list());
    }

    public function testCustomColorListItemsAreCustomColorDTOs(): void
    {
        foreach ($this->client->customColors()->list() as $item) {
            $this->assertInstanceOf(CustomColorDTO::class, $item);
        }
    }

    // ── CustomField ───────────────────────────────────────────────────────────

    public function testCustomFieldListReturnsArray(): void
    {
        $this->assertIsArray($this->client->customFields()->list());
    }

    public function testCustomFieldListItemsAreCustomFieldDTOs(): void
    {
        foreach ($this->client->customFields()->list() as $item) {
            $this->assertInstanceOf(CustomFieldDTO::class, $item);
        }
    }

    // ── DueDateColor ──────────────────────────────────────────────────────────

    public function testDueDateColorListReturnsArray(): void
    {
        $this->assertIsArray($this->client->dueDateColors()->list());
    }

    public function testDueDateColorListItemsAreDueDateColorDTOs(): void
    {
        foreach ($this->client->dueDateColors()->list() as $item) {
            $this->assertInstanceOf(DueDateColorDTO::class, $item);
        }
    }

    // ── TravelType ────────────────────────────────────────────────────────────

    public function testTravelTypeListReturnsArray(): void
    {
        $this->assertIsArray($this->client->travelTypes()->list());
    }

    public function testTravelTypeListItemsAreTravelTypeDTOs(): void
    {
        foreach ($this->client->travelTypes()->list() as $item) {
            $this->assertInstanceOf(TravelTypeDTO::class, $item);
        }
    }

    // ── MaterialItem ──────────────────────────────────────────────────────────

    public function testMaterialItemListReturnsArray(): void
    {
        $this->assertIsArray($this->client->materialItems()->list());
    }

    public function testMaterialItemListItemsAreMaterialItemDTOs(): void
    {
        foreach ($this->client->materialItems()->list() as $item) {
            $this->assertInstanceOf(MaterialItemDTO::class, $item);
        }
    }
}
