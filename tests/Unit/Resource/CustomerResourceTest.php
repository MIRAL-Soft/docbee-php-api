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

    public function testFindByCustomerIdReturnsCursorMatch(): void
    {
        // The Docbee API ignores ALL customerId= and customerId-eq= filter parameters.
        // The fix uses a cursor scan + client-side exact match on getCustomerId().
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 2,
                'customer'   => [
                    ['id' => 4, 'customerId' => 'K-9999'],
                    ['id' => 5, 'customerId' => 'K-1001'],
                ],
            ]);

        $dto = $this->resource->findByCustomerId('K-1001');
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame('K-1001', $dto->getCustomerId());
        $this->assertSame(5, $dto->getId());
    }

    public function testFindByCustomerIdDoesNotReturnFirstItemWhenNoMatch(): void
    {
        // Regression: old code returned $results[0] (the wrong customer) even
        // when the server filter was ignored and the first item didn't match.
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 1,
                'customer'   => [['id' => 99, 'customerId' => 'K-OTHER']],
            ]);

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

    public function testFindOneByCustomerIdReturnsMatch(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 2,
                'customer'   => [
                    ['id' => 4, 'customerId' => 'K-9999'],
                    ['id' => 5, 'customerId' => 'K-1001'],
                ],
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

    public function testFindOneByCustomerIdReturnsNullWhenNoMatch(): void
    {
        // Regression: old code returned $results[0] even when customer didn't match.
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount' => 1,
                'customer'   => [['id' => 99, 'customerId' => 'K-OTHER']],
            ]);

        $result = $this->resource->findOneByCustomerId('K-1001');
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
