<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CustomerDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Resource\CustomerResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see CustomerResource}.
 */
final class CustomerResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private CustomerResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new CustomerResource($this->http);
    }

    public function testFindByCustomerNumberReturnsDTO(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('customerId-eq=K-1001'))
            ->willReturn([
                'totalCount' => 1,
                'customer'   => [['id' => 5, 'name' => 'Acme', 'customerId' => 'K-1001']],
            ]);

        $dto = $this->resource->findByCustomerNumber('K-1001');
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame('K-1001', $dto->getCustomerId());
    }

    public function testFindByCustomerNumberThrowsNotFound(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'customer' => []]);

        $this->expectException(NotFoundException::class);
        $this->resource->findByCustomerNumber('NOPE');
    }

    public function testFindByNameUsesIlikeFilter(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('name-ilike'))
            ->willReturn(['totalCount' => 0, 'customer' => []]);

        $results = $this->resource->findByName('Acme');
        $this->assertSame([], $results);
    }

    public function testFindByEmailFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('email-eq=info%40acme.com'))
            ->willReturn([
                'totalCount' => 1,
                'customer'   => [['id' => 3, 'email' => 'info@acme.com']],
            ]);

        $results = $this->resource->findByEmail('info@acme.com');
        $this->assertCount(1, $results);
        $this->assertInstanceOf(CustomerDTO::class, $results[0]);
        $this->assertSame('info@acme.com', $results[0]->getEmail());
    }

    public function testFindActiveFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('active-eq=1'))
            ->willReturn([
                'totalCount' => 2,
                'customer'   => [
                    ['id' => 1, 'name' => 'Active Corp', 'active' => true],
                    ['id' => 2, 'name' => 'Also Active', 'active' => true],
                ],
            ]);

        $results = $this->resource->findActive();
        $this->assertCount(2, $results);
        $this->assertTrue($results[0]->isActive());
    }
}
