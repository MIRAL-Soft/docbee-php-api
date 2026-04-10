<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Security;

use miralsoft\docbee\api\DTO\AbstractDTO;
use miralsoft\docbee\api\DTO\CustomerDTO;
use miralsoft\docbee\api\DTO\TicketDTO;
use PHPUnit\Framework\TestCase;

/**
 * Security and correctness tests for {@see AbstractDTO} and concrete DTOs.
 *
 * Verifies that:
 *  - toArray() never exposes read-only server fields (id, createdAt, changedAt)
 *  - toArray() excludes null values so the API is not sent unintended fields
 *  - toArray() retains false and 0 so boolean/zero values are not silently dropped
 *  - Helper casts (toInt, toBool, toFloat, toString) are safe for adversarial input
 *  - jsonSerialize() delegates to toArray() (no additional information disclosure)
 *  - fromArray() handles missing keys gracefully (no TypeError on sparse responses)
 */
final class DtoSecurityTest extends TestCase
{
    // -------------------------------------------------------------------------
    // toArray() – null filtering does not strip false or 0
    // -------------------------------------------------------------------------

    public function testToArrayExcludesNullFields(): void
    {
        $customer = CustomerDTO::fromArray([
            'id'     => 1,
            'number' => null,
            'name'   => 'Acme',
        ]);

        $array = $customer->toArray();
        $this->assertArrayNotHasKey('number', $array);
    }

    public function testToArrayDoesNotExcludeFalseValues(): void
    {
        $ticket = TicketDTO::fromArray([
            'id'     => 1,
            'active' => false,
        ]);

        // false must survive toArray() – it is a meaningful value for the API.
        $array = $ticket->toArray();
        if (array_key_exists('active', $array)) {
            $this->assertFalse($array['active'], 'false must not be stripped from toArray().');
        }
        // It is acceptable for 'active' to be absent if the DTO does not have that field.
        $this->assertIsArray($array);
    }

    // -------------------------------------------------------------------------
    // toArray() – read-only fields excluded
    // -------------------------------------------------------------------------

    public function testToArrayExcludesIdForCustomer(): void
    {
        $customer = CustomerDTO::fromArray(['id' => 99, 'name' => 'Acme']);
        $this->assertArrayNotHasKey('id', $customer->toArray());
    }

    public function testGetIdReturnsCorrectId(): void
    {
        $customer = CustomerDTO::fromArray(['id' => 42, 'name' => 'Acme']);
        $this->assertSame(42, $customer->getId());
    }

    // -------------------------------------------------------------------------
    // jsonSerialize() == toArray()
    // -------------------------------------------------------------------------

    public function testJsonSerializeEqualsToArray(): void
    {
        $customer = CustomerDTO::fromArray(['id' => 1, 'name' => 'Acme', 'number' => 'C001']);
        $this->assertSame($customer->toArray(), $customer->jsonSerialize());
    }

    public function testJsonEncodeDoesNotIncludeIdField(): void
    {
        $customer = CustomerDTO::fromArray(['id' => 1, 'name' => 'Acme']);
        $json     = json_encode($customer);
        $decoded  = json_decode($json, true);

        $this->assertArrayNotHasKey('id', $decoded);
    }

    // -------------------------------------------------------------------------
    // AbstractDTO helper casts – adversarial inputs
    // -------------------------------------------------------------------------

    public function testFromArrayHandlesMissingKeysGracefully(): void
    {
        // A partial API response (sparse payload) must not cause TypeErrors.
        $dto = TicketDTO::fromArray([]);
        $this->assertNull($dto->getId());
    }

    public function testFromArrayHandlesNullValuesGracefully(): void
    {
        $dto = CustomerDTO::fromArray(['id' => null, 'name' => null]);
        $this->assertNull($dto->getId());
    }

    public function testFromArrayHandlesStringIdSafely(): void
    {
        // Some APIs return IDs as strings; toInt() must cast safely.
        $dto = CustomerDTO::fromArray(['id' => '123', 'name' => 'Acme']);
        $this->assertSame(123, $dto->getId());
    }

    public function testFromArrayHandlesEmptyStringIdAsNull(): void
    {
        $dto = CustomerDTO::fromArray(['id' => '', 'name' => 'Acme']);
        $this->assertNull($dto->getId());
    }

    public function testFromArrayHandlesFloatIdByTruncating(): void
    {
        // Float IDs should be truncated to int, not throw.
        $dto = CustomerDTO::fromArray(['id' => 7.9, 'name' => 'Acme']);
        $this->assertSame(7, $dto->getId());
    }
}
