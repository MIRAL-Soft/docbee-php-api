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
            'id'             => 42,
            'name'           => 'Acme Corp',
            'customerNumber' => 'K-1001',
            'email'          => 'info@acme.com',
            'phone'          => '+49 89 123456',
            'mobile'         => null,
            'fax'            => null,
            'website'        => 'https://acme.com',
            'street'         => 'Main Street 1',
            'zip'            => '80333',
            'city'           => 'Munich',
            'country'        => 'DE',
            'notes'          => 'VIP customer',
            'status'         => 1,
            'active'         => true,
            'createdAt'      => '2024-01-01T12:00:00',
            'changedAt'      => '2024-06-01T08:30:00',
        ];
    }

    public function testFromArrayMapsAllFields(): void
    {
        $dto = CustomerDTO::fromArray($this->sampleData());

        $this->assertSame(42, $dto->getId());
        $this->assertSame('Acme Corp', $dto->getName());
        $this->assertSame('K-1001', $dto->getCustomerNumber());
        $this->assertSame('info@acme.com', $dto->getEmail());
        $this->assertSame('+49 89 123456', $dto->getPhone());
        $this->assertSame('https://acme.com', $dto->getWebsite());
        $this->assertSame('Main Street 1', $dto->getStreet());
        $this->assertSame('80333', $dto->getZip());
        $this->assertSame('Munich', $dto->getCity());
        $this->assertSame('DE', $dto->getCountry());
        $this->assertSame('VIP customer', $dto->getNotes());
        $this->assertSame(1, $dto->getStatus());
        $this->assertTrue($dto->isActive());
        $this->assertSame('2024-01-01T12:00:00', $dto->getCreatedAt());
        $this->assertSame('2024-06-01T08:30:00', $dto->getChangedAt());
    }

    public function testFromArrayHandlesNullValues(): void
    {
        $dto = CustomerDTO::fromArray(['id' => null, 'name' => null]);

        $this->assertNull($dto->getId());
        $this->assertNull($dto->getName());
        $this->assertNull($dto->getEmail());
    }

    public function testToArrayExcludesNullValues(): void
    {
        $dto    = CustomerDTO::fromArray($this->sampleData());
        $result = $dto->toArray();

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayNotHasKey('id', $result);
        $this->assertArrayNotHasKey('createdAt', $result);
        $this->assertArrayNotHasKey('changedAt', $result);
    }

    public function testToArrayDoesNotIncludeReadOnlyFields(): void
    {
        $dto    = CustomerDTO::fromArray($this->sampleData());
        $result = $dto->toArray();

        // id, createdAt, changedAt are server-managed and must not be sent
        $this->assertArrayNotHasKey('id', $result);
        $this->assertArrayNotHasKey('createdAt', $result);
        $this->assertArrayNotHasKey('changedAt', $result);
    }
}
