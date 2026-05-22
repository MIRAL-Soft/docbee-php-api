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

    /** Default page size for list requests. */
    private const DEFAULT_PAGE_SIZE = 50;

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
     * **Docbee API field behaviour:** The default list response omits many DTO fields.
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
        $qs       = ($query ?? QueryBuilder::new())->build();
        $response = $this->http->get("{$this->endpoint}{$qs}");
        $items    = $response[$this->listKey] ?? [];

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
     * The same field-selection caveat as {@see list()} applies: many DTO fields are
     * omitted from default responses and silently return `null` unless requested via
     * `QueryBuilder::new()->fields([...])`.
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
     * The same field-selection caveat as {@see list()} applies: many DTO fields are
     * omitted from default responses and silently return `null` unless requested via
     * `QueryBuilder::new()->fields([...])`.
     *
     * @return Generator<int, T, void, void>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function cursor(?QueryBuilder $query = null): Generator
    {
        $offset    = 0;
        $pageSize  = self::DEFAULT_PAGE_SIZE;
        $baseQuery = $query ?? QueryBuilder::new();

        do {
            $pageQuery = (clone $baseQuery)->limit($pageSize)->offset($offset);
            $qs        = $pageQuery->build();
            $response  = $this->http->get("{$this->endpoint}{$qs}");
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
        } while ($offset < $total);
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
     * $changed = $client->tickets()->findModifiedSince(new DateTimeImmutable('-1 hour'));
     * foreach ($changed as $ticket) {
     *     sync($ticket);
     * }
     * ```
     *
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findModifiedSince(DateTimeInterface $since, ?QueryBuilder $query = null): array
    {
        $q = ($query ?? QueryBuilder::new())->modifiedSince($since);
        return $this->listAll($q);
    }

    /**
     * Returns records created after the given date/time.
     *
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findCreatedSince(DateTimeInterface $since, ?QueryBuilder $query = null): array
    {
        $q = ($query ?? QueryBuilder::new())->createdSince($since);
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
