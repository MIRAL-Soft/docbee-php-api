<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Resource\DocumentResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see DocumentResource} custom-field value helpers.
 */
final class DocumentResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private DocumentResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new DocumentResource($this->http);
    }

    // ── getCustomFieldValues ──────────────────────────────────────────────────

    public function testGetCustomFieldValuesUsesDotNotation(): void
    {
        // The Docbee API only returns actual values when dot-notation is used:
        //   ?fields=customFields.id,customFields.value
        // The plain ?fields=customFields returns only IDs without values.
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->logicalAnd(
                $this->stringContains('customFields.id'),
                $this->stringContains('customFields.value')
            ))
            ->willReturn(['customFields' => [['id' => 102, 'value' => 123], ['id' => 104, 'value' => 'WO-12345']]]);

        $values = $this->resource->getCustomFieldValues(5);

        $this->assertSame([102 => 123, 104 => 'WO-12345'], $values);
    }

    public function testGetCustomFieldValuesReturnsEmptyArrayWhenNoneSet(): void
    {
        $this->http->method('get')->willReturn([]);

        $this->assertSame([], $this->resource->getCustomFieldValues(5));
    }

    public function testGetCustomFieldValuesHandlesNullValue(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['customFields' => [['id' => 102, 'value' => null]]]);

        $values = $this->resource->getCustomFieldValues(5);
        $this->assertArrayHasKey(102, $values);
        $this->assertNull($values[102]);
    }

    // ── getCustomFieldValue ───────────────────────────────────────────────────

    public function testGetCustomFieldValueReturnsCorrectValue(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['customFields' => [['id' => 102, 'value' => 42], ['id' => 104, 'value' => 'hello']]]);

        $this->assertSame(42,      $this->resource->getCustomFieldValue(5, 102));
        $this->assertSame('hello', $this->resource->getCustomFieldValue(5, 104));
    }

    public function testGetCustomFieldValueReturnsNullWhenFieldAbsent(): void
    {
        $this->http->method('get')->willReturn(['customFields' => []]);

        $this->assertNull($this->resource->getCustomFieldValue(5, 999));
    }

    // ── getCustomFieldIds ─────────────────────────────────────────────────────

    public function testGetCustomFieldIdsRequestsFieldsParam(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('fields=customFields'))
            ->willReturn(['customFields' => [101, 102]]);

        $ids = $this->resource->getCustomFieldIds(5);

        $this->assertSame([101, 102], $ids);
    }

    public function testGetCustomFieldIdsReturnsEmptyArrayWhenNoneSet(): void
    {
        $this->http
            ->method('get')
            ->willReturn([]);

        $this->assertSame([], $this->resource->getCustomFieldIds(5));
    }

    // ── hasCustomFieldValue ───────────────────────────────────────────────────

    public function testHasCustomFieldValueReturnsTrueWhenPresent(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['customFields' => [101, 102, 103]]);

        $this->assertTrue($this->resource->hasCustomFieldValue(5, 102));
    }

    public function testHasCustomFieldValueReturnsFalseWhenAbsent(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['customFields' => [101, 103]]);

        $this->assertFalse($this->resource->hasCustomFieldValue(5, 102));
    }

    // ── setCustomFieldValue ───────────────────────────────────────────────────

    public function testSetCustomFieldValueCallsPutWithCorrectPayload(): void
    {
        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocument/42',
                ['customFields' => [['id' => 101, 'value' => 'WO-12345']]]
            );

        $this->resource->setCustomFieldValue(42, 101, 'WO-12345');
    }

    public function testSetCustomFieldValueSupportsIntegerValues(): void
    {
        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocument/7',
                ['customFields' => [['id' => 99, 'value' => 9876]]]
            );

        $this->resource->setCustomFieldValue(7, 99, 9876);
    }

    // ── setCustomFieldValues ──────────────────────────────────────────────────

    public function testSetCustomFieldValuesSendsAllValuesInOneRequest(): void
    {
        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocument/10',
                $this->callback(fn(array $p): bool =>
                    count($p['customFields']) === 2
                    && in_array(['id' => 101, 'value' => 'abc'], $p['customFields'], true)
                    && in_array(['id' => 102, 'value' => 'xyz'], $p['customFields'], true)
                )
            );

        $this->resource->setCustomFieldValues(10, [101 => 'abc', 102 => 'xyz']);
    }

    public function testSetCustomFieldValuesSkipsEmptyMap(): void
    {
        $this->http->expects($this->never())->method('put');

        $this->resource->setCustomFieldValues(10, []);
    }
}
