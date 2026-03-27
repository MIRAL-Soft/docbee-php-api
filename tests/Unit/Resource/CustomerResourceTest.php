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
            ->with($this->stringContains('customerNumber-eq=K-1001'))
            ->willReturn([
                'totalCount' => 1,
                'customer'   => [['id' => 5, 'name' => 'Acme', 'customerNumber' => 'K-1001']],
            ]);

        $dto = $this->resource->findByCustomerNumber('K-1001');
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame('K-1001', $dto->getCustomerNumber());
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
        $this->assertIsArray($results);
    }
}
