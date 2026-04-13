<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\DTO;

use miralsoft\docbee\api\DTO\CustomerDTO;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see CustomerDTO}.
 */
final class CustomerDTOTest extends TestCase
{
    private function sampleData(): array
    {
        return [
            'id'                      => 42,
            'created'                 => '2024-01-01T12:00:00',
            'modified'                => '2024-06-01T08:30:00',
            'defaultCustomerLocation' => 7,
            'name'                    => 'Acme Corp',
            'customerId'              => 'K-1001',
            'customerStatus'          => 1,
            'shortName'               => 'Acme',
            'wildcardAddress'         => 'acme.com',
            'inhouse'                 => false,
            'companyData'             => 5,
            'info'                    => 'VIP customer',
            'syncToApp'               => true,
            'warning'                 => 'Contract ends 2025-01-01',
        ];
    }

    public function testFromArrayMapsAllFields(): void
    {
        $dto = CustomerDTO::fromArray($this->sampleData());

        $this->assertSame(42, $dto->getId());
        $this->assertSame('2024-01-01T12:00:00', $dto->getCreated());
        $this->assertSame('2024-06-01T08:30:00', $dto->getModified());
        $this->assertSame(7, $dto->getDefaultCustomerLocation());
        $this->assertSame('Acme Corp', $dto->getName());
        $this->assertSame('K-1001', $dto->getCustomerId());
        $this->assertSame(1, $dto->getCustomerStatus());
        $this->assertSame('Acme', $dto->getShortName());
        $this->assertSame('acme.com', $dto->getWildcardAddress());
        $this->assertFalse($dto->isInhouse());
        $this->assertSame(5, $dto->getCompanyData());
        $this->assertSame('VIP customer', $dto->getInfo());
        $this->assertTrue($dto->isSyncToApp());
        $this->assertSame('Contract ends 2025-01-01', $dto->getWarning());
    }

    public function testFromArrayHandlesNullValues(): void
    {
        $dto = CustomerDTO::fromArray(['id' => null, 'name' => null]);

        $this->assertNull($dto->getId());
        $this->assertNull($dto->getName());
        $this->assertNull($dto->getCustomerId());
        $this->assertNull($dto->getCustomerStatus());
    }

    public function testToArrayExcludesNullValues(): void
    {
        $dto    = CustomerDTO::fromArray($this->sampleData());
        $result = $dto->toArray();

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('customerId', $result);
        $this->assertArrayHasKey('customerStatus', $result);
    }

    public function testToArrayDoesNotIncludeReadOnlyFields(): void
    {
        $dto    = CustomerDTO::fromArray($this->sampleData());
        $result = $dto->toArray();

        // id, created, modified, defaultCustomerLocation are server-managed
        $this->assertArrayNotHasKey('id', $result);
        $this->assertArrayNotHasKey('created', $result);
        $this->assertArrayNotHasKey('modified', $result);
        $this->assertArrayNotHasKey('defaultCustomerLocation', $result);
        $this->assertArrayNotHasKey('link', $result);
    }
}
