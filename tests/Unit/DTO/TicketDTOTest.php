<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\DTO;

use miralsoft\docbee\api\DTO\TicketDTO;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see TicketDTO}.
 */
final class TicketDTOTest extends TestCase
{
    private function sampleData(): array
    {
        return [
            'id'                      => 99,
            'created'                 => '2024-06-01T08:00:00',
            'modified'                => '2024-06-01T09:00:00',
            'ticketNumber'            => 'TK-0099',
            'description'             => 'The printer on 2nd floor is not responding.',
            'internalDescription'     => 'Checked remotely, needs on-site visit.',
            'customer'                => 42,
            'customerContact'         => 7,
            'customerLocation'        => 3,
            'owner'                   => 5,
            'ticketStatus'            => 1,
            'priority'                => 2,
            'dueDate'                 => '2024-12-31T23:59:59',
            'referenceNumber'         => 'REF-001',
            'erpReferenceNumber'      => 'ERP-2024-001',
            'externalReferenceNumber' => 'EXT-123',
            'billable'                => true,
            'startDate'               => '2024-06-01T00:00:00',
        ];
    }

    public function testFromArrayMapsAllFields(): void
    {
        $dto = TicketDTO::fromArray($this->sampleData());

        $this->assertSame(99, $dto->getId());
        $this->assertSame('2024-06-01T08:00:00', $dto->getCreated());
        $this->assertSame('2024-06-01T09:00:00', $dto->getModified());
        $this->assertSame('TK-0099', $dto->getTicketNumber());
        $this->assertSame('The printer on 2nd floor is not responding.', $dto->getDescription());
        $this->assertSame('Checked remotely, needs on-site visit.', $dto->getInternalDescription());
        $this->assertSame(42, $dto->getCustomer());
        $this->assertSame(7, $dto->getCustomerContact());
        $this->assertSame(3, $dto->getCustomerLocation());
        $this->assertSame(5, $dto->getOwner());
        $this->assertSame(1, $dto->getTicketStatus());
        $this->assertSame(2, $dto->getPriority());
        $this->assertSame('2024-12-31T23:59:59', $dto->getDueDate());
        $this->assertSame('REF-001', $dto->getReferenceNumber());
        $this->assertSame('ERP-2024-001', $dto->getErpReferenceNumber());
        $this->assertSame('EXT-123', $dto->getExternalReferenceNumber());
        $this->assertTrue($dto->getBillable());
    }

    public function testFromArrayHandlesNullAndMissingFields(): void
    {
        $dto = TicketDTO::fromArray(['id' => 1, 'customer' => null]);

        $this->assertSame(1, $dto->getId());
        $this->assertNull($dto->getCustomer());
        $this->assertNull($dto->getOwner());
        $this->assertNull($dto->getTicketStatus());
    }

    public function testToArrayExcludesReadOnlyFields(): void
    {
        $dto    = TicketDTO::fromArray($this->sampleData());
        $result = $dto->toArray();

        // Writable fields are present
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('customer', $result);
        $this->assertArrayHasKey('ticketStatus', $result);
        $this->assertArrayHasKey('owner', $result);
        $this->assertArrayHasKey('referenceNumber', $result);

        // Read-only server fields must not be sent
        $this->assertArrayNotHasKey('id', $result);
        $this->assertArrayNotHasKey('created', $result);
        $this->assertArrayNotHasKey('modified', $result);
        $this->assertArrayNotHasKey('ticketNumber', $result);
        $this->assertArrayNotHasKey('link', $result);
    }
}
