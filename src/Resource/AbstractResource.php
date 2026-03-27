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
     * @return T
     * @throws NotFoundException        when the record does not exist.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function find(int $id): AbstractDTO
    {
        $response = $this->http->get("{$this->endpoint}/{$id}");

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
     * @return list<T>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function list(?QueryBuilder $query = null): array
    {
        $qs       = ($query ?? QueryBuilder::new())->build();
        $response = $this->http->get("{$this->endpoint}{$qs}");
        $items    = $response[$this->listKey] ?? [];

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
