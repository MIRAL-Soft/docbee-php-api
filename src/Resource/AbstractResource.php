<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use DateTimeInterface;
use Generator;
use LogicException;
use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AbstractDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Base class for all Docbee API resource handlers.
 *
 * Provides standard CRUD operations, delta-sync helpers, and a memory-efficient
 * cursor/generator for iterating large datasets.
 *
 * Concrete subclasses must define:
 *  - {@see $endpoint}   – the API path segment (e.g. 'ticket')
 *  - {@see $dtoClass}   – the fully-qualified DTO class name
 *  - {@see $listKey}    – the key used in list responses (e.g. 'ticket')
 *
 * @template T of AbstractDTO
 */
abstract class AbstractResource
{
    /** API path segment, e.g. 'ticket', 'customer'. */
    protected string $endpoint = '';

    /** Fully-qualified DTO class, e.g. TicketDTO::class. */
    protected string $dtoClass = '';

    /**
     * Key used in paginated list responses.
     * E.g. the response `{"ticket": [...]}` uses key 'ticket'.
     */
    protected string $listKey = '';

    /**
     * Default number of records fetched per HTTP request in {@see cursor()}.
     *
     * Raised from 50 → 100 (live-verified 2026-05-23 against pcs tenant):
     * all tested Docbee endpoints accept 100 records per page.  Halves round-trip
     * count vs. the previous default with no other change in behaviour.
     *
     * Subclasses may override this to a higher value when the endpoint supports it.
     * **Do NOT set above 100 on resources backed by `/invoice`** — that endpoint
     * silently returns 0 items for `limit > 100` (live-verified bug).
     *
     * | Endpoint            | Max safe page size | Notes                  |
     * |---------------------|--------------------|------------------------|
     * | `/invoice`          | 100                | >100 → silent empty    |
     * | `/docBeeDocument`   | 500+               | Tested up to 500 ✓     |
     * | All others          | 100 (conservative) | Not individually tested|
     *
     * @var int
     */
    protected int $defaultPageSize = 100;

    /**
     * Default fields to request in {@see find()} when no explicit fields are passed.
     *
     * Subclasses can override this to ensure that fields which the Docbee API omits
     * from the default single-record response are always included automatically.
     * Leave empty to preserve the API's default field selection.
     *
     * @var array<string>
     */
    protected array $findFields = [];

    /**
     * Default fields to request in {@see list()}, {@see cursor()}, and all methods
     * that delegate to them ({@see listAll()}, {@see findModifiedSince()},
     * {@see findCreatedSince()}, {@see search()}) when the caller supplies no
     * explicit `QueryBuilder::fields()` selector.
     *
     * **Why this exists:** The Docbee API list endpoints return a smaller default
     * field set than the single-record endpoint (`GET /<resource>/{id}`).  Without
     * this property, `find($id)` and `findModifiedSince()` return the same entity
     * with a different set of populated DTO fields — a subtle, hard-to-debug
     * inconsistency that causes null-pointer errors in consumer code.
     *
     * Subclasses should set this to the same value as `$findFields` (or a superset)
     * to guarantee that list DTOs are as fully populated as single-record DTOs.
     * Leave empty to preserve the API's narrow default field selection.
     *
     * When the caller explicitly sets fields via `QueryBuilder::fields([...])`,
     * that explicit selection takes precedence and this default is ignored — so
     * callers can still request a slim projection for performance-sensitive scans.
     *
     * @var array<string>
     */
    protected array $defaultListFields = [];

    public function __construct(
        protected readonly HttpClientInterface $http,
    ) {
        // Guard against subclasses that forget to define the required properties.
        // These are caught at construction time rather than silently failing at runtime.
        if ($this->endpoint === '') {
            throw new LogicException(static::class . ' must define a non-empty $endpoint.');
        }
        if ($this->dtoClass === '') {
            throw new LogicException(static::class . ' must define a non-empty $dtoClass.');
        }
        if ($this->listKey === '') {
            throw new LogicException(static::class . ' must define a non-empty $listKey.');
        }
        // Verify that the DTO class actually exists and is an AbstractDTO subclass.
        // Catches typos at construction time instead of on the first API call.
        if (!is_subclass_of($this->dtoClass, AbstractDTO::class)) {
            throw new LogicException(
                static::class . " \$dtoClass '{$this->dtoClass}' must be a subclass of AbstractDTO."
            );
        }
    }

    // -------------------------------------------------------------------------
    // Read operations
    // -------------------------------------------------------------------------

    /**
     * Returns the total number of records matching an optional query.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function count(?QueryBuilder $query = null): int
    {
        $qs       = ($query ?? QueryBuilder::new())->buildForCount();
        $response = $this->http->get("{$this->endpoint}{$qs}");
        return (int) ($response['totalCount'] ?? 0);
    }

    /**
     * Retrieves a single record by its numeric ID.
     *
     * @param array<string> $fields Optional list of field names to include in the response.
     *                              When non-empty, appends ?fields=f1,f2,... to the request URL.
     *                              Useful for reducing payload size on resources with many fields.
     * @return T
     * @throws NotFoundException        when the record does not exist.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     *
     * @note The Docbee API does not return all fields for every resource type.
     *       For tickets, the following fields are known to be absent in GET /ticket/{id} responses:
     *       description, erpReferenceNumber, internalDescription, priority, ticketStatus.
     *       This is a server-side limitation and cannot be worked around via the $fields parameter.
     */
    public function find(int $id, array $fields = []): AbstractDTO
    {
        if (empty($fields) && !empty($this->findFields)) {
            $fields = $this->findFields;
        }
        $qs       = !empty($fields) ? '?' . http_build_query(['fields' => implode(',', $fields)]) : '';
        $response = $this->http->get("{$this->endpoint}/{$id}{$qs}");

        if (empty($response)) {
            throw new NotFoundException(
                message:    "Docbee {$this->endpoint} with ID {$id} not found.",
                statusCode: 404,
                requestUrl: "{$this->endpoint}/{$id}",
            );
        }

        return ($this->dtoClass)::fromArray($response);
    }

    /**
     * Returns a paginated list of records.
     *
     * **Default field selection:** When `$defaultListFields` is configured on the
     * concrete resource (e.g. `DocumentResource`), those fields are requested
     * automatically — even without an explicit `QueryBuilder::fields()` call.
     * This ensures list DTOs are as fully populated as single-record DTOs from
     * {@see find()}.  Callers that need a slim projection can always override by
     * passing an explicit `QueryBuilder::new()->fields([...])`.
     *
     * **Docbee API field behaviour on resources without `$defaultListFields`:**
     * The default list response omits many DTO fields.
     * Getter methods for omitted fields return `null` silently — no error is raised.
     * Use `QueryBuilder::new()->fields([...])` to request specific fields explicitly:
     *
     * ```php
     * // Without fields() — number will be null on every ServiceTypeDTO
     * $types = $client->serviceTypes()->list();
     *
     * // With fields() — number is present
     * $types = $client->serviceTypes()->list(
     *     QueryBuilder::new()->fields(['id', 'name', 'number', 'deactivated'])
     * );
     * ```
     *
     * Known fields that require explicit request in list responses:
     * `ServiceType.number`, `DocBeeDocument.erpReferenceNumber`,
     * `DocBeeDocument.ticket`, `customFields` (use dot-notation
     * `customFields.id,customFields.value` to get values, not just IDs).
     *
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function list(?QueryBuilder $query = null): array
    {
        $baseQuery = $query ?? QueryBuilder::new();
        $baseQuery = $this->applyDefaultListFields($baseQuery);
        $qs        = $baseQuery->build();
        $response  = $this->http->get("{$this->endpoint}{$qs}");
        $items     = $response[$this->listKey] ?? [];

        if (!is_array($items)) {
            return [];
        }

        return array_map(
            fn(array $item) => ($this->dtoClass)::fromArray($item),
            $items,
        );
    }

    /**
     * Fetches ALL records by automatically paginating through the API.
     *
     * Use sparingly on large datasets – prefer {@see cursor()} for memory efficiency.
     *
     * Default field selection is identical to {@see cursor()} — `$defaultListFields`
     * are injected automatically when no explicit `QueryBuilder::fields()` is given.
     *
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function listAll(?QueryBuilder $query = null): array
    {
        $results = [];
        foreach ($this->cursor($query) as $item) {
            $results[] = $item;
        }
        return $results;
    }

    /**
     * Returns a generator that yields records one by one, auto-paginating.
     *
     * Ideal for processing large datasets without loading everything into memory:
     *
     * ```php
     * foreach ($client->tickets()->cursor() as $ticket) {
     *     process($ticket);
     * }
     * ```
     *
     * Default field selection behaviour is identical to {@see list()}: when
     * `$defaultListFields` is configured on the concrete resource, those fields are
     * requested automatically unless the caller already specified an explicit
     * `QueryBuilder::fields([...])` selector.
     *
     * @return Generator<int, T, void, void>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function cursor(?QueryBuilder $query = null): Generator
    {
        $offset    = 0;
        $baseQuery = $this->applyDefaultListFields($query ?? QueryBuilder::new());
        // Determine page size: caller's explicit pageSize() > resource $defaultPageSize.
        // buildForPage() is used instead of ->limit()->build() to bypass the MAX_LIMIT=100
        // cap in limit() — some endpoints (e.g. /docBeeDocument) support larger pages.
        $pageSize  = $baseQuery->getPageSize() ?? $this->defaultPageSize;

        do {
            $qs       = $baseQuery->buildForPage($pageSize, $offset);
            $response = $this->http->get("{$this->endpoint}{$qs}");
            $items     = $response[$this->listKey] ?? [];

            if (!is_array($items)) {
                break;
            }

            foreach ($items as $item) {
                yield ($this->dtoClass)::fromArray($item);
            }

            // Stop early if the API returns an empty page — prevents wasted
            // additional requests when totalCount is stale or inaccurate.
            if (empty($items)) {
                break;
            }

            $offset += $pageSize;
            $total   = (int) ($response['totalCount'] ?? 0);
            // Keep paginating while the page was FULL, even when totalCount is
            // missing or zero — previously a missing totalCount silently stopped
            // after the first page (silent data loss). At worst this costs one
            // extra request that returns an empty page and breaks above.
        } while ($offset < $total || count($items) === $pageSize);
    }

    // -------------------------------------------------------------------------
    // Delta-sync helpers
    // -------------------------------------------------------------------------

    /**
     * Returns records modified after the given date/time.
     *
     * This is the recommended approach for efficient incremental synchronisation:
     *
     * ```php
     * $changed = $client->documents()->findModifiedSince(new DateTimeImmutable('-1 hour'));
     * foreach ($changed as $doc) {
     *     sync($doc);  // getModified(), getTicket(), getApproved() etc. are fully populated
     * }
     * ```
     *
     * Default field selection: when `$defaultListFields` is configured on the resource
     * (e.g. `DocumentResource`), the returned DTOs are as fully populated as those
     * from `find($id)`.  Pass an explicit `QueryBuilder::fields([...])` to override.
     *
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findModifiedSince(DateTimeInterface $since, ?QueryBuilder $query = null): array
    {
        // Clone before adding the filter — QueryBuilder methods mutate the instance,
        // and the caller's builder must not permanently accumulate a changedSince filter.
        $q = ($query !== null ? clone $query : QueryBuilder::new())->modifiedSince($since);
        return $this->listAll($q);
    }

    /**
     * Returns records created after the given date/time.
     *
     * Default field selection: identical to {@see findModifiedSince()} — `$defaultListFields`
     * are injected automatically when the caller passes no explicit field selector.
     *
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findCreatedSince(DateTimeInterface $since, ?QueryBuilder $query = null): array
    {
        // Clone before adding the filter — see findModifiedSince().
        $q = ($query !== null ? clone $query : QueryBuilder::new())->createdSince($since);
        return $this->listAll($q);
    }

    /**
     * Full-text search using the Docbee `search` parameter.
     *
     * The Docbee API applies the search string across multiple fields at once.
     * What is searched depends on the endpoint — typically name, number fields,
     * and other human-visible identifiers.  For example:
     *
     *   - `/customer`         → matches customer name and customer number (UI display number)
     *   - `/customerContact`  → matches contact name, email, phone, …
     *   - `/customerLocation` → matches location name and address fields
     *   - `/ticket`           → matches ticket title, description, reference number, …
     *   - `/user`             → matches user name and email
     *   - `/object`           → matches object name and serial/scan codes
     *
     * Returns all matching records (auto-paginated via {@see listAll()}).
     *
     * @note Not every endpoint supports `search`; those that do are marked in the
     *       OpenAPI specification.  On unsupported endpoints the parameter is silently
     *       ignored and all records are returned.
     *
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function search(string $query): array
    {
        return $this->listAll(QueryBuilder::new()->search($query));
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Posts an export-by-ids request and returns the raw file bytes.
     *
     * Centralises two things every `export*ByIds()` method needs:
     *  - the `{"ids": [...]}` body shape (live-verified: the server rejects a raw
     *    JSON array with HTTP 400, despite the OpenAPI spec claiming otherwise),
     *  - a guard against an empty ID list (depending on the endpoint an empty
     *    list either fails or silently produces an empty/complete export).
     *
     * @param  int[] $ids
     * @throws \InvalidArgumentException when $ids is empty.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    protected function postExportByIds(string $path, array $ids): string
    {
        if (empty($ids)) {
            throw new \InvalidArgumentException(
                static::class . ': $ids must not be empty for an export-by-ids request.'
            );
        }
        return $this->http->postRaw($path, ['ids' => array_values($ids)]);
    }

    /**
     * Returns a QueryBuilder with the resource's `$defaultListFields` injected,
     * but **only** when the caller did not already set an explicit field selector.
     *
     * Clones the builder before mutating it so the caller's original instance is
     * never modified.
     */
    private function applyDefaultListFields(QueryBuilder $query): QueryBuilder
    {
        if (!empty($this->defaultListFields) && empty($query->getFields())) {
            return (clone $query)->fields($this->defaultListFields);
        }
        return $query;
    }

    // -------------------------------------------------------------------------
    // Write operations
    // -------------------------------------------------------------------------

    /**
     * Creates a new record and returns the resulting DTO.
     *
     * @param array<string, mixed> $data
     * @return T
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function create(array $data): AbstractDTO
    {
        $response = $this->http->post($this->endpoint, $data);
        return ($this->dtoClass)::fromArray($response);
    }

    /**
     * Updates an existing record by ID and returns the updated DTO.
     *
     * @param array<string, mixed> $data
     * @return T
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function update(int $id, array $data): AbstractDTO
    {
        $response = $this->http->put("{$this->endpoint}/{$id}", $data);
        return ($this->dtoClass)::fromArray($response);
    }

    /**
     * Deletes a record by ID.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function delete(int $id): void
    {
        $this->http->delete("{$this->endpoint}/{$id}");
    }
}
