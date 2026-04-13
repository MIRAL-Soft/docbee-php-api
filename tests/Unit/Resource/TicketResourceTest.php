<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Resource\TicketResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see TicketResource}.
 */
final class TicketResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private TicketResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new TicketResource($this->http);
    }

    public function testFindReturnsTicketDTO(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with('ticket/42')
            ->willReturn(['id' => 42, 'description' => 'Test Ticket', 'customer' => 5]);

        $dto = $this->resource->find(42);

        $this->assertInstanceOf(TicketDTO::class, $dto);
        $this->assertSame(42, $dto->getId());
        $this->assertSame('Test Ticket', $dto->getDescription());
    }

    public function testFindThrowsNotFoundOnEmptyResponse(): void
    {
        $this->http
            ->method('get')
            ->willReturn([]);

        $this->expectException(NotFoundException::class);
        $this->resource->find(999);
    }

    public function testListReturnsDTOArray(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 2,
                'offset'     => 0,
                'limit'      => 50,
                'ticket'     => [
                    ['id' => 1, 'description' => 'Ticket A'],
                    ['id' => 2, 'description' => 'Ticket B'],
                ],
            ]);

        $results = $this->resource->list();

        $this->assertCount(2, $results);
        $this->assertInstanceOf(TicketDTO::class, $results[0]);
        $this->assertSame('Ticket A', $results[0]->getDescription());
    }

    public function testCountReturnsInteger(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 77]);

        $this->assertSame(77, $this->resource->count());
    }

    public function testCreatePostsData(): void
    {
        $payload = ['description' => 'New Ticket', 'customer' => 1];

        $this->http
            ->expects($this->once())
            ->method('post')
            ->with('ticket', $payload)
            ->willReturn(['id' => 100, 'description' => 'New Ticket', 'customer' => 1]);

        $dto = $this->resource->create($payload);

        $this->assertSame(100, $dto->getId());
    }

    public function testFindByCustomerFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('customer-eq=42'))
            ->willReturn([
                'totalCount' => 1,
                'ticket'     => [['id' => 10, 'customer' => 42]],
            ]);

        $results = $this->resource->findByCustomer(42);
        $this->assertCount(1, $results);
    }

    public function testFindByCustomerWithTicketStatusFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->logicalAnd(
                $this->stringContains('customer-eq=42'),
                $this->stringContains('ticketStatus-eq=3'),
            ))
            ->willReturn([
                'totalCount' => 1,
                'ticket'     => [['id' => 10, 'customer' => 42, 'ticketStatus' => 3]],
            ]);

        $results = $this->resource->findByCustomer(42, ticketStatusId: 3);
        $this->assertCount(1, $results);
    }

    public function testFindByTicketStatusFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('ticketStatus-eq=2'))
            ->willReturn([
                'totalCount' => 0,
                'ticket'     => [],
            ]);

        $results = $this->resource->findByTicketStatus(2);
        $this->assertSame([], $results);
    }

    public function testFindByReferenceNumberFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('referenceNumber-eq=REF-001'))
            ->willReturn([
                'totalCount' => 1,
                'ticket'     => [['id' => 7, 'referenceNumber' => 'REF-001']],
            ]);

        $results = $this->resource->findByReferenceNumber('REF-001');
        $this->assertCount(1, $results);
        $this->assertSame('REF-001', $results[0]->getReferenceNumber());
    }

    public function testFindByOwnerFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('owner-eq=5'))
            ->willReturn([
                'totalCount' => 2,
                'ticket'     => [
                    ['id' => 1, 'owner' => 5],
                    ['id' => 2, 'owner' => 5],
                ],
            ]);

        $results = $this->resource->findByOwner(5);
        $this->assertCount(2, $results);
        $this->assertSame(5, $results[0]->getOwner());
    }

    public function testDeleteCallsHttpDelete(): void
    {
        $this->http
            ->expects($this->once())
            ->method('delete')
            ->with('ticket/42');

        $this->resource->delete(42);
    }

    public function testUpdateCallsHttpPut(): void
    {
        $payload = ['description' => 'Updated description'];

        $this->http
            ->expects($this->once())
            ->method('put')
            ->with('ticket/42', $payload)
            ->willReturn(['id' => 42, 'description' => 'Updated description']);

        $dto = $this->resource->update(42, $payload);
        $this->assertSame('Updated description', $dto->getDescription());
    }
}
