<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CustomFieldDTO;
use miralsoft\docbee\api\Resource\CustomFieldResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see CustomFieldResource}.
 */
final class CustomFieldResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;
    private CustomFieldResource $resource;

    protected function setUp(): void
    {
        $this->http     = $this->createMock(HttpClientInterface::class);
        $this->resource = new CustomFieldResource($this->http);
    }

    // ── Constants ─────────────────────────────────────────────────────────────

    public function testParentTypeConstantsAreDefined(): void
    {
        $this->assertSame('DOCBEE_DOCUMENT',   CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT);
        $this->assertSame('TICKET',            CustomFieldResource::PARENT_TYPE_TICKET);
        $this->assertSame('CUSTOMER',          CustomFieldResource::PARENT_TYPE_CUSTOMER);
        $this->assertSame('CUSTOMER_CONTACT',  CustomFieldResource::PARENT_TYPE_CUSTOMER_CONTACT);
        $this->assertSame('CUSTOMER_LOCATION', CustomFieldResource::PARENT_TYPE_CUSTOMER_LOCATION);
        $this->assertSame('MATERIAL_ITEM',     CustomFieldResource::PARENT_TYPE_MATERIAL_ITEM);
    }

    public function testFieldTypeConstantsAreDefined(): void
    {
        $this->assertSame('SINGLELINE_TEXT', CustomFieldResource::TYPE_SINGLELINE_TEXT);
        $this->assertSame('MULTILINE_TEXT',  CustomFieldResource::TYPE_MULTILINE_TEXT);
        $this->assertSame('NUMBER_LONG',     CustomFieldResource::TYPE_NUMBER_LONG);
        $this->assertSame('NUMBER_DECIMAL',  CustomFieldResource::TYPE_NUMBER_DECIMAL);
        $this->assertSame('BOOLEAN',         CustomFieldResource::TYPE_BOOLEAN);
        $this->assertSame('DATE',            CustomFieldResource::TYPE_DATE);
        $this->assertSame('SELECTION',       CustomFieldResource::TYPE_SELECTION);
    }

    // ── findByParentType ──────────────────────────────────────────────────────

    public function testFindByParentTypeUsesPlainParamNotEqSuffix(): void
    {
        // The Docbee API silently ignores parentType-eq= and returns ALL fields.
        // Only the plain parentType= parameter works correctly.
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->logicalAnd(
                $this->stringContains('parentType=DOCBEE_DOCUMENT'),
                $this->logicalNot($this->stringContains('parentType-eq='))
            ))
            ->willReturn(['totalCount' => 0, 'customField' => []]);

        $this->resource->findByParentType('DOCBEE_DOCUMENT');
    }

    public function testFindByParentTypeRequestsExplicitFields(): void
    {
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->stringContains('fields='))
            ->willReturn(['totalCount' => 0, 'customField' => []]);

        $this->resource->findByParentType('TICKET');
    }

    public function testFindByParentTypeReturnsDTOArray(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount'  => 2,
                'customField' => [
                    ['id' => 1, 'name' => 'fieldA', 'parentType' => 'DOCBEE_DOCUMENT', 'type' => 'SINGLELINE_TEXT'],
                    ['id' => 2, 'name' => 'fieldB', 'parentType' => 'DOCBEE_DOCUMENT', 'type' => 'NUMBER_LONG'],
                ],
            ]);

        $results = $this->resource->findByParentType('DOCBEE_DOCUMENT');

        $this->assertCount(2, $results);
        $this->assertInstanceOf(CustomFieldDTO::class, $results[0]);
        $this->assertSame('fieldA', $results[0]->getName());
        $this->assertSame('DOCBEE_DOCUMENT', $results[0]->getParentType());
    }

    public function testFindByParentTypeReturnsEmptyArrayWhenNoneExist(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'customField' => []]);

        $this->assertSame([], $this->resource->findByParentType('CUSTOMER'));
    }

    // ── findByName ────────────────────────────────────────────────────────────

    public function testFindByNameDelegatesToFindByParentType(): void
    {
        // findByName() now uses findByParentType() so the parentType= filter
        // must be in the request (not just a client-side check across all fields).
        $this->http
            ->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->stringContains('parentType=DOCBEE_DOCUMENT'))
            ->willReturn([
                'totalCount'  => 1,
                'customField' => [
                    ['id' => 1, 'name' => 'weclappOrderItemId', 'parentType' => 'DOCBEE_DOCUMENT', 'type' => 'SINGLELINE_TEXT'],
                ],
            ]);

        $result = $this->resource->findByName('weclappOrderItemId', 'DOCBEE_DOCUMENT');

        $this->assertInstanceOf(CustomFieldDTO::class, $result);
        $this->assertSame(1, $result->getId());
    }

    public function testFindByNameReturnsNullWhenNoMatch(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'customField' => []]);

        $this->assertNull($this->resource->findByName('nonexistent', 'TICKET'));
    }

    public function testFindByNameReturnsNullWhenNameDoesNotMatch(): void
    {
        // Server returns field for the correct parentType but wrong name.
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount'  => 1,
                'customField' => [
                    ['id' => 5, 'name' => 'otherField', 'parentType' => 'DOCBEE_DOCUMENT', 'type' => 'SINGLELINE_TEXT'],
                ],
            ]);

        $this->assertNull($this->resource->findByName('myField', 'DOCBEE_DOCUMENT'));
    }

    // ── ensureDefinition ──────────────────────────────────────────────────────

    public function testEnsureDefinitionReturnsExistingFieldWithoutCreating(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->willReturn([
                'totalCount'  => 1,
                'customField' => [
                    ['id' => 10, 'name' => 'myField', 'parentType' => 'DOCBEE_DOCUMENT', 'type' => 'SINGLELINE_TEXT'],
                ],
            ]);

        $this->http->expects($this->never())->method('post');

        $result = $this->resource->ensureDefinition('myField', 'DOCBEE_DOCUMENT', 'SINGLELINE_TEXT');

        $this->assertSame(10, $result->getId());
    }

    public function testEnsureDefinitionCreatesFieldWhenAbsent(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['totalCount' => 0, 'customField' => []]);

        $this->http
            ->expects($this->once())
            ->method('post')
            ->with(
                'customField',
                $this->callback(fn(array $d): bool =>
                    $d['name']       === 'newField'
                    && $d['parentType']  === 'DOCBEE_DOCUMENT'
                    && $d['type']        === 'SINGLELINE_TEXT'
                    && $d['searchable']  === true
                )
            )
            ->willReturn(['id' => 99, 'name' => 'newField', 'parentType' => 'DOCBEE_DOCUMENT', 'type' => 'SINGLELINE_TEXT']);

        $result = $this->resource->ensureDefinition('newField', 'DOCBEE_DOCUMENT', 'SINGLELINE_TEXT');

        $this->assertSame(99, $result->getId());
    }

    public function testEnsureDefinitionPassesCustomOptions(): void
    {
        $this->http->method('get')->willReturn(['totalCount' => 0, 'customField' => []]);

        $this->http
            ->expects($this->once())
            ->method('post')
            ->with(
                'customField',
                $this->callback(fn(array $d): bool =>
                    $d['searchable']         === false
                    && $d['visibleForCustomer'] === true
                )
            )
            ->willReturn(['id' => 77, 'name' => 'f', 'parentType' => 'TICKET', 'type' => 'BOOLEAN']);

        $this->resource->ensureDefinition(
            name:              'f',
            parentType:        'TICKET',
            type:              'BOOLEAN',
            searchable:        false,
            visibleForCustomer: true,
        );
    }

    // ── ensureAssignedToDocument ──────────────────────────────────────────────

    public function testEnsureAssignedToDocumentSkipsWriteWhenAlreadyPresent(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with('docBeeDocument/customFields')
            ->willReturn(['customFields' => [10, 20, 30]]);

        // Field 20 is already in the list — no PUT should happen.
        $this->http->expects($this->never())->method('put');

        $this->resource->ensureAssignedToDocument(20);
    }

    public function testEnsureAssignedToDocumentMergesNewFieldId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with('docBeeDocument/customFields')
            ->willReturn(['customFields' => [10, 20]]);

        $this->http
            ->expects($this->once())
            ->method('put')
            ->with('docBeeDocument/customFields', ['customFields' => [10, 20, 30]]);

        $this->resource->ensureAssignedToDocument(30);
    }

    public function testEnsureAssignedToDocumentHandlesEmptyList(): void
    {
        $this->http
            ->method('get')
            ->willReturn(['customFields' => []]);

        $this->http
            ->expects($this->once())
            ->method('put')
            ->with('docBeeDocument/customFields', ['customFields' => [42]]);

        $this->resource->ensureAssignedToDocument(42);
    }

    // ── ensureAssignedToTicket ────────────────────────────────────────────────

    public function testEnsureAssignedToTicketMergesNewFieldId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with('ticket/customFields')
            ->willReturn(['customFields' => [5]]);

        $this->http
            ->expects($this->once())
            ->method('put')
            ->with('ticket/customFields', ['customFields' => [5, 99]]);

        $this->resource->ensureAssignedToTicket(99);
    }

    public function testEnsureAssignedToTicketSkipsWriteWhenAlreadyPresent(): void
    {
        $this->http
            ->method('get')
            ->with('ticket/customFields')
            ->willReturn(['customFields' => [5, 99]]);

        $this->http->expects($this->never())->method('put');

        $this->resource->ensureAssignedToTicket(99);
    }
}
