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

    public function testFindByCustomerIdReturnsDTO(): void
    {
        // Must use plain `customerId=` — the `-eq` operator form is silently
        // ignored by the Docbee API even for scalar fields on this endpoint.
        $this->http
            ->method('get')
            ->with($this->stringContains('customerId=K-1001'))
            ->willReturn([
                'totalCount' => 1,
                'customer'   => [['id' => 5, 'name' => 'Acme', 'customerId' => 'K-1001']],
            ]);

        $dto = $this->resource->findByCustomerId('K-1001');
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame('K-1001', $dto->getCustomerId());
    }

    public function testFindByCustomerIdDoesNotUseEqSuffix(): void
    {
        // Regression guard: ensure filterEq() (which appends -eq) is never used
        // for customerId — that form is silently ignored by the Docbee backend.
        $this->http
            ->method('get')
            ->with($this->logicalNot($this->stringContains('customerId-eq=')))
            ->willReturn(['totalCount' => 0, 'customer' => []]);

        $this->expectException(NotFoundException::class);
        $this->resource->findByCustomerId('K-1001');
    }

    public function testFindByCustomerIdThrowsNotFound(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'customer' => []]);

        $this->expectException(NotFoundException::class);
        $this->resource->findByCustomerId('NOPE');
    }

    public function testFindOneByCustomerIdReturnsDTO(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('customerId=K-1001'))
            ->willReturn([
                'totalCount' => 1,
                'customer'   => [['id' => 5, 'name' => 'Acme', 'customerId' => 'K-1001']],
            ]);

        $dto = $this->resource->findOneByCustomerId('K-1001');
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame('K-1001', $dto->getCustomerId());
    }

    public function testFindOneByCustomerIdReturnsNullWhenNotFound(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'customer' => []]);

        $result = $this->resource->findOneByCustomerId('NOPE');
        $this->assertNull($result);
    }

    public function testFindByNameDelegatesToSearch(): void
    {
        // findByName() is a @deprecated alias for search(), which uses the
        // native Docbee `search` parameter instead of `name-ilike`.
        $this->http
            ->method('get')
            ->with($this->stringContains('search=Acme'))
            ->willReturn(['totalCount' => 0, 'customer' => []]);

        $results = $this->resource->findByName('Acme');
        $this->assertSame([], $results);
    }

    public function testFindByCustomerStatusFiltersCorrectly(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('customerStatus-eq=1'))
            ->willReturn([
                'totalCount' => 2,
                'customer'   => [
                    ['id' => 1, 'name' => 'Acme', 'customerStatus' => 1],
                    ['id' => 2, 'name' => 'Corp',  'customerStatus' => 1],
                ],
            ]);

        $results = $this->resource->findByCustomerStatus(1);
        $this->assertCount(2, $results);
        $this->assertInstanceOf(CustomerDTO::class, $results[0]);
        $this->assertSame(1, $results[0]->getCustomerStatus());
    }
}
