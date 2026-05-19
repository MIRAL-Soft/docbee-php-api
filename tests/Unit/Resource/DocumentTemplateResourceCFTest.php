<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CustomFieldValueDTO;
use miralsoft\docbee\api\Resource\DocumentTemplateResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for custom-field helpers in {@see DocumentTemplateResource}.
 *
 * Document templates share custom-field definitions with regular documents
 * (parentType = DOCBEE_DOCUMENT). These tests verify that:
 *   - setCustomFieldValue() / setCustomFieldValues() build the correct PUT payload
 *   - getCustomFieldValues() maps the dot-notation response to a fieldId => value map
 *   - cursorWithCustomFields() uses the correct fields= parameter
 *   - findByCustomFieldValue() performs a cursor scan and matches correctly
 *   - DocBeeDocumentTemplateDTO deserialises customFields arrays into CustomFieldValueDTO objects
 */
final class DocumentTemplateResourceCFTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private DocumentTemplateResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new DocumentTemplateResource($this->http);
    }

    // ── setCustomFieldValue ───────────────────────────────────────────────────

    public function testSetCustomFieldValueSendsPutWithCorrectPayload(): void
    {
        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocumentTemplate/77',
                ['customFields' => [['id' => 104, 'value' => 'WO-12345']]]
            )
            ->willReturn([]);

        $this->resource->setCustomFieldValue(77, 104, 'WO-12345');
    }

    // ── setCustomFieldValues ──────────────────────────────────────────────────

    public function testSetCustomFieldValuesBuildsArrayPayload(): void
    {
        $this->http
            ->expects($this->once())
            ->method('put')
            ->with(
                'docBeeDocumentTemplate/77',
                ['customFields' => [
                    ['id' => 102, 'value' => 99],
                    ['id' => 104, 'value' => 'WO-12345'],
                ]]
            )
            ->willReturn([]);

        $this->resource->setCustomFieldValues(77, [102 => 99, 104 => 'WO-12345']);
    }

    public function testSetCustomFieldValuesSkipsEmptyMap(): void
    {
        $this->http->expects($this->never())->method('put');

        $this->resource->setCustomFieldValues(77, []);
    }

    // ── getCustomFieldValues ──────────────────────────────────────────────────

    public function testGetCustomFieldValuesReturnsMappedArray(): void
    {
        $this->http
            ->method('get')
            ->with('docBeeDocumentTemplate/77?fields=customFields.id,customFields.value')
            ->willReturn([
                'id'           => 77,
                'customFields' => [
                    ['id' => 102, 'value' => 99],
                    ['id' => 104, 'value' => 'WO-12345'],
                ],
            ]);

        $values = $this->resource->getCustomFieldValues(77);

        $this->assertSame([102 => 99, 104 => 'WO-12345'], $values);
    }

    public function testGetCustomFieldValuesReturnsEmptyArrayWhenNoneSet(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['id' => 77]); // no customFields key

        $this->assertSame([], $this->resource->getCustomFieldValues(77));
    }

    // ── cursorWithCustomFields ────────────────────────────────────────────────

    public function testCursorWithCustomFieldsRequestsDotNotationFields(): void
    {
        $captured = [];
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$captured): array {
                $captured[] = $url;
                return ['docBeeDocumentTemplate' => [], 'totalCount' => 0];
            });

        iterator_to_array($this->resource->cursorWithCustomFields(205023));

        $this->assertNotEmpty($captured);
        $this->assertStringContainsString('customFields.id', $captured[0]);
        $this->assertStringContainsString('customFields.value', $captured[0]);
        $this->assertStringContainsString('customer-eq=205023', $captured[0]);
    }

    // ── findByCustomFieldValue ────────────────────────────────────────────────

    public function testFindByCustomFieldValueReturnsMatchingTemplate(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocumentTemplate' => [
                    [
                        'id'           => 10,
                        'customFields' => [
                            ['id' => 104, 'value' => 'WO-12345'],
                        ],
                    ],
                    [
                        'id'           => 11,
                        'customFields' => [
                            ['id' => 104, 'value' => 'WO-99999'],
                        ],
                    ],
                ],
                'totalCount' => 2,
            ]);

        $results = $this->resource->findByCustomFieldValue(205023, 104, 'WO-12345');

        $this->assertCount(1, $results);
        $this->assertSame(10, $results[0]->getId());
    }

    public function testFindByCustomFieldValueReturnsEmptyWhenNoMatch(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocumentTemplate' => [
                    ['id' => 10, 'customFields' => [['id' => 104, 'value' => 'OTHER']]],
                ],
                'totalCount' => 1,
            ]);

        $this->assertSame([], $this->resource->findByCustomFieldValue(205023, 104, 'NOTEXIST'));
    }

    public function testFindByCustomFieldValueSkipsTemplatesWithoutCustomFields(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocumentTemplate' => [
                    ['id' => 1],                               // no customFields key
                    ['id' => 2, 'customFields' => null],       // explicit null
                    ['id' => 3, 'customFields' => [['id' => 104, 'value' => 'HIT']]],
                ],
                'totalCount' => 3,
            ]);

        $results = $this->resource->findByCustomFieldValue(205023, 104, 'HIT');

        $this->assertCount(1, $results);
        $this->assertSame(3, $results[0]->getId());
    }

    // ── DTO deserialisation ───────────────────────────────────────────────────

    public function testDtoDeserializesCustomFieldsToValueDTOs(): void
    {
        // DocBeeDocumentTemplateDTO must convert [{id,value}] arrays into
        // CustomFieldValueDTO objects so that findByCustomFieldValue() instanceof
        // checks work correctly.
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocumentTemplate' => [
                    [
                        'id'           => 55,
                        'customFields' => [
                            ['id' => 104, 'value' => 'X'],
                        ],
                    ],
                ],
                'totalCount' => 1,
            ]);

        iterator_to_array($cursor = $this->resource->cursorWithCustomFields(1));

        // Re-run to get items
        $this->http
            ->method('get')
            ->willReturn([
                'docBeeDocumentTemplate' => [
                    ['id' => 55, 'customFields' => [['id' => 104, 'value' => 'X']]],
                ],
                'totalCount' => 1,
            ]);

        $results = $this->resource->findByCustomFieldValue(1, 104, 'X');

        $this->assertCount(1, $results);
        $cfs = $results[0]->getCustomFields();
        $this->assertIsArray($cfs);
        $this->assertInstanceOf(CustomFieldValueDTO::class, $cfs[0]);
        $this->assertSame(104, $cfs[0]->getId());
        $this->assertSame('X', $cfs[0]->getValue());
    }
}
