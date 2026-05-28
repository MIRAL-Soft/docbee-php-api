<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\AbstractResource;
use miralsoft\docbee\api\Resource\DocumentResource;
use miralsoft\docbee\api\Resource\InvoiceResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that `$defaultListFields` is injected automatically into list, cursor,
 * findModifiedSince and findCreatedSince requests, and that an explicit caller-supplied
 * QueryBuilder::fields() always takes precedence.
 *
 * Background: The Docbee API list endpoint returns a smaller default field set than the
 * single-record endpoint.  Without `$defaultListFields`, calling `findModifiedSince()`
 * on DocumentResource returns DTOs where `getModified()`, `getTicket()`, `getApproved()`
 * etc. are all null — causing delta-sync logic to silently skip every record.
 * (Live-verified bug, pcs tenant, 2026-05-27.)
 */
final class DefaultListFieldsTest extends TestCase
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Builds an anonymous AbstractResource subclass with configurable `$defaultListFields`.
     *
     * @param array<string> $defaultListFields
     */
    private function makeResource(
        HttpClientInterface $http,
        array $defaultListFields,
    ): AbstractResource {
        return new class($http, $defaultListFields) extends AbstractResource {
            protected string $endpoint = 'ticket';
            protected string $dtoClass = TicketDTO::class;
            protected string $listKey  = 'ticket';

            public function __construct(HttpClientInterface $http, array $dlf)
            {
                $this->defaultListFields = $dlf;
                parent::__construct($http);
            }
        };
    }

    /** Returns a minimal list-response stub accepted by the resource. */
    private function emptyListResponse(): array
    {
        return ['ticket' => [], 'totalCount' => 0];
    }

    // ── list() ────────────────────────────────────────────────────────────────

    public function testListInjectsDefaultFieldsWhenNoneSet(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->stringContains('fields=id%2Cname%2Cmodified'))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        $resource->list();
    }

    public function testListDoesNotInjectDefaultFieldsWhenCallerSetsFields(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('fields=id'),
                 $this->logicalNot($this->stringContains('name')),
                 $this->logicalNot($this->stringContains('modified')),
             ))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        $resource->list(QueryBuilder::new()->fields(['id']));
    }

    public function testListDoesNotInjectDefaultFieldsWhenDefaultListFieldsIsEmpty(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalNot($this->stringContains('fields=')))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, []); // no defaults
        $resource->list();
    }

    // ── cursor() ──────────────────────────────────────────────────────────────

    public function testCursorInjectsDefaultFieldsWhenNoneSet(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->stringContains('fields=id%2Cname%2Cmodified'))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        iterator_to_array($resource->cursor()); // consume the generator
    }

    public function testCursorDoesNotInjectDefaultFieldsWhenCallerSetsFields(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('fields=id'),
                 $this->logicalNot($this->stringContains('name')),
             ))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        iterator_to_array($resource->cursor(QueryBuilder::new()->fields(['id'])));
    }

    // ── findModifiedSince() ───────────────────────────────────────────────────

    public function testFindModifiedSinceInjectsDefaultFields(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('changedSince'),
                 $this->stringContains('fields=id%2Cname%2Cmodified'),
             ))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        $resource->findModifiedSince(new \DateTimeImmutable('-1 day'));
    }

    public function testFindModifiedSinceRespectsExplicitFields(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('fields=id'),
                 $this->logicalNot($this->stringContains('name')),
             ))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        $resource->findModifiedSince(
            new \DateTimeImmutable('-1 day'),
            QueryBuilder::new()->fields(['id']),
        );
    }

    // ── findCreatedSince() ────────────────────────────────────────────────────

    public function testFindCreatedSinceInjectsDefaultFields(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('createdSince'),
                 $this->stringContains('fields=id%2Cname%2Cmodified'),
             ))
             ->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        $resource->findCreatedSince(new \DateTimeImmutable('-1 day'));
    }

    // ── Caller's QueryBuilder is not mutated ──────────────────────────────────

    public function testCallerQueryBuilderIsNotMutatedByDefaultFieldInjection(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->method('get')->willReturn($this->emptyListResponse());

        $resource = $this->makeResource($http, ['id', 'name', 'modified']);
        $callerQuery = QueryBuilder::new()->filterEq('status', 'open');

        $this->assertSame([], $callerQuery->getFields(), 'Precondition: caller has no fields set');

        $resource->list($callerQuery);

        // The caller's QueryBuilder must not have been modified
        $this->assertSame([], $callerQuery->getFields(), 'Caller QueryBuilder must not be mutated');
    }

    // ── DocumentResource integration ──────────────────────────────────────────

    public function testDocumentResourceDefaultListFieldsCoversModifiedAndTicket(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('modified'),
                 $this->stringContains('ticket'),
                 $this->stringContains('approved'),
                 $this->stringContains('billable'),
                 $this->stringContains('invoiceNumber'),
                 $this->stringContains('erpReferenceNumber'),
             ))
             ->willReturn(['docBeeDocument' => [], 'totalCount' => 0]);

        $resource = new DocumentResource($http);
        $resource->list();
    }

    public function testDocumentResourceExplicitFieldsOverrideDefault(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('fields=id'),
                 $this->logicalNot($this->stringContains('approved')),
                 $this->logicalNot($this->stringContains('modified')),
             ))
             ->willReturn(['docBeeDocument' => [], 'totalCount' => 0]);

        $resource = new DocumentResource($http);
        $resource->list(QueryBuilder::new()->fields(['id']));
    }

    // ── InvoiceResource integration ───────────────────────────────────────────

    public function testInvoiceResourceDefaultListFieldsCoversDocBeeDocumentAndStatus(): void
    {
        /** @var HttpClientInterface&MockObject $http */
        $http = $this->createMock(HttpClientInterface::class);
        $http->expects($this->once())
             ->method('get')
             ->with($this->logicalAnd(
                 $this->stringContains('docBeeDocument'),
                 $this->stringContains('status'),
                 $this->stringContains('invoiceNumber'),
             ))
             ->willReturn(['invoice' => [], 'totalCount' => 0]);

        $resource = new InvoiceResource($http);
        $resource->list();
    }
}
