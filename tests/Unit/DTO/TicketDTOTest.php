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
            'id'               => 99,
            'title'            => 'Printer offline',
            'description'      => 'The printer on 2nd floor is not responding.',
            'customer'         => 42,
            'customerContact'  => 7,
            'customerLocation' => 3,
            'assignedUser'     => 5,
            'status'           => 1,
            'priority'         => 2,
            'serviceType'      => 10,
            'requestType'      => 4,
            'orderId'          => 'ORD-2024-001',
            'dueDate'          => '2024-12-31T23:59:59',
            'closedAt'         => null,
            'createdAt'        => '2024-06-01T08:00:00',
            'changedAt'        => '2024-06-01T09:00:00',
        ];
    }

    public function testFromArrayMapsAllFields(): void
    {
        $dto = TicketDTO::fromArray($this->sampleData());

        $this->assertSame(99, $dto->getId());
        $this->assertSame('Printer offline', $dto->getTitle());
        $this->assertSame('The printer on 2nd floor is not responding.', $dto->getDescription());
        $this->assertSame(42, $dto->getCustomer());
        $this->assertSame(7, $dto->getCustomerContact());
        $this->assertSame(3, $dto->getCustomerLocation());
        $this->assertSame(5, $dto->getAssignedUser());
        $this->assertSame(1, $dto->getStatus());
        $this->assertSame(2, $dto->getPriority());
        $this->assertSame(10, $dto->getServiceType());
        $this->assertSame(4, $dto->getRequestType());
        $this->assertSame('ORD-2024-001', $dto->getOrderId());
        $this->assertSame('2024-12-31T23:59:59', $dto->getDueDate());
        $this->assertNull($dto->getClosedAt());
    }

    public function testToArrayExcludesReadOnlyFields(): void
    {
        $dto    = TicketDTO::fromArray($this->sampleData());
        $result = $dto->toArray();

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('customer', $result);
        $this->assertArrayNotHasKey('id', $result);
        $this->assertArrayNotHasKey('closedAt', $result);
        $this->assertArrayNotHasKey('createdAt', $result);
        $this->assertArrayNotHasKey('changedAt', $result);
    }
}
