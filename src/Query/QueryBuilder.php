<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Query;

use DateTimeInterface;
use InvalidArgumentException;

/**
 * Fluent query builder for the Docbee REST API.
 *
 * Constructs URL query strings with filters, sorting, and pagination.
 * All methods return the same instance for chaining.
 *
 * ```php
 * $query = QueryBuilder::new()
 *     ->filterEq('status', 'open')
 *     ->filterGt('priority', 2)
 *     ->modifiedSince(new DateTimeImmutable('-1 hour'))
 *     ->sort('createdAt', 'desc')
 *     ->limit(50)
 *     ->offset(0)
 *     ->build();
 * // Produces: ?status-eq=open&priority-gt=2&changedSince=...&sort=createdAt-desc&limit=50&offset=0
 * ```
 */
final class QueryBuilder
{
    /**
     * Maximum number of records per page for a single {@see list()} call.
     *
     * **Endpoint-specific server limits (live-verified, 2026-05-23):**
     * - `/invoice`          – silently returns 0 items for `limit > 100`. Hard cap = 100.
     * - `/docBeeDocument`   – honours up to at least 500 per page.
     *
     * This constant governs {@see limit()} which is used by `list()`.  For cursor-based
     * pagination use {@see pageSize()} instead — it bypasses this cap and lets each
     * resource set its own endpoint-appropriate page size.
     */
    private const MAX_LIMIT = 100;

    /** @var array<string, string> */
    private array $filters = [];

    /** @var list<string> */
    private array $sorts = [];

    private int $limit  = 100;
    private int $offset = 0;

    /**
     * Per-page size used by {@see AbstractResource::cursor()} when set.
     * Null means "use the resource's $defaultPageSize".
     * NOT capped by MAX_LIMIT — endpoint-specific limits are the caller's responsibility.
     */
    private ?int $pageSize = null;

    /** @var list<string> */
    private array $fields = [];

    /** Named constructor – preferred over `new QueryBuilder()`. */
    public static function new(): self
    {
        return new self();
    }

    // -------------------------------------------------------------------------
    // Generic filter
    // -------------------------------------------------------------------------

    /**
     * Adds a filter with an explicit operator.
     *
     * Accepts a {@see FilterOperator} enum case (preferred) or a raw string.
     *
     * @param FilterOperator|string $operator
     * @param mixed                 $value
     */
    public function filter(string $field, FilterOperator|string $operator, mixed $value): self
    {
        if (trim($field) === '') {
            throw new InvalidArgumentException('QueryBuilder: filter field name must not be empty.');
        }
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $field)) {
            throw new InvalidArgumentException(
                "QueryBuilder: invalid filter field name '{$field}'. " .
                'Field names must start with a letter and contain only letters, digits, and underscores.'
            );
        }

        $op = $operator instanceof FilterOperator ? $operator->value : $operator;
        $this->filters["{$field}-{$op}"] = $this->normalizeValue($value);
        return $this;
    }

    // -------------------------------------------------------------------------
    // Shortcut filters
    // -------------------------------------------------------------------------

    /** Adds an equals filter: `field-eq=value`. */
    public function filterEq(string $field, mixed $value): self
    {
        return $this->filter($field, FilterOperator::EQ, $value);
    }

    /** Adds a not-equals filter: `field-neq=value`. */
    public function filterNeq(string $field, mixed $value): self
    {
        return $this->filter($field, FilterOperator::NEQ, $value);
    }

    /** Adds a case-insensitive LIKE filter: `field-ilike=value`. */
    public function filterIlike(string $field, string $value): self
    {
        return $this->filter($field, FilterOperator::ILIKE, $value);
    }

    /** Adds a greater-than filter: `field-gt=value`. */
    public function filterGt(string $field, mixed $value): self
    {
        return $this->filter($field, FilterOperator::GT, $value);
    }

    /** Adds a greater-than-or-equal filter: `field-gte=value`. */
    public function filterGte(string $field, mixed $value): self
    {
        return $this->filter($field, FilterOperator::GTE, $value);
    }

    /** Adds a less-than filter: `field-lt=value`. */
    public function filterLt(string $field, mixed $value): self
    {
        return $this->filter($field, FilterOperator::LT, $value);
    }

    /** Adds a less-than-or-equal filter: `field-lte=value`. */
    public function filterLte(string $field, mixed $value): self
    {
        return $this->filter($field, FilterOperator::LTE, $value);
    }

    /**
     * Adds an IN filter: `field-in=v1,v2,v3`.
     *
     * @param list<mixed> $values
     */
    public function filterIn(string $field, array $values): self
    {
        $this->filters["{$field}-in"] = implode(',', array_map(
            fn($v) => $this->normalizeValue($v),
            $values,
        ));
        return $this;
    }

    // -------------------------------------------------------------------------
    // Plain parameters (no operator suffix)
    // -------------------------------------------------------------------------

    /**
     * Adds a query parameter with a plain key — no operator suffix is appended.
     *
     * Most Docbee API filters follow the `field-eq=value` convention handled by
     * {@see filterEq()} and friends.  A small number of endpoints accept the field
     * name as-is (e.g. `customer=42` rather than `customer-eq=42`).  Use this
     * method when the API ignores the `-eq` variant and requires the bare name.
     *
     * Example endpoints where this is required:
     *   - GET /customerContact  (parameters: `customer`, `customerLocation`)
     *   - GET /customerLocation (parameter:  `customer`)
     *
     * @param mixed $value Scalar value; booleans are normalised to '1'/'0'.
     */
    public function param(string $key, mixed $value): self
    {
        $this->filters[$key] = $this->normalizeValue($value);
        return $this;
    }

    /**
     * Adds a full-text search filter using the Docbee `search` parameter.
     *
     * The Docbee API applies the search string across multiple fields at once.
     * For the `/customer` endpoint it matches against the customer's **name**
     * and the **customer number** displayed in the Docbee UI — making this the
     * correct way to look up a customer when you only know the UI number.
     *
     * Example:
     * ```php
     * // Find by name
     * QueryBuilder::new()->search('Testfirma');
     * // Find by the customer number shown in the Docbee UI
     * QueryBuilder::new()->search('12355');
     * ```
     */
    public function search(string $query): self
    {
        $this->filters['search'] = $query;
        return $this;
    }

    // -------------------------------------------------------------------------
    // Timestamp helpers
    // -------------------------------------------------------------------------

    /**
     * Filters for records modified after the given date/time.
     * Maps to the `changedSince` query parameter.
     *
     * @note The Docbee API requires the exact format `YYYY-MM-DDTHH:mm:ss.mmmZ`
     *       (ISO 8601 UTC with millisecond precision).  Any other format — plain
     *       ISO without timezone, epoch milliseconds, date-only — yields HTTP 400
     *       with "changedSince has invalid date format".  The `.000Z` suffix is
     *       appended automatically here; no caller adjustment is needed.
     */
    public function modifiedSince(DateTimeInterface $since): self
    {
        $this->filters['changedSince'] = $since->format('Y-m-d\TH:i:s') . '.000Z';
        return $this;
    }

    /**
     * Filters for records created after the given date/time.
     * Maps to the `createdSince` query parameter.
     *
     * @note See {@see modifiedSince()} — the same date-format requirement applies.
     */
    public function createdSince(DateTimeInterface $since): self
    {
        $this->filters['createdSince'] = $since->format('Y-m-d\TH:i:s') . '.000Z';
        return $this;
    }

    // -------------------------------------------------------------------------
    // Sorting
    // -------------------------------------------------------------------------

    /**
     * Adds a sort directive.
     *
     * @param string $direction 'asc' or 'desc'
     * @throws InvalidArgumentException on empty field name or invalid direction.
     */
    public function sort(string $field, string $direction = 'asc'): self
    {
        if (trim($field) === '') {
            throw new InvalidArgumentException('QueryBuilder: sort field name must not be empty.');
        }
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $field)) {
            throw new InvalidArgumentException(
                "QueryBuilder: invalid sort field name '{$field}'. " .
                'Field names must start with a letter and contain only letters, digits, and underscores.'
            );
        }
        $dir = strtolower($direction);
        if (!in_array($dir, ['asc', 'desc'], true)) {
            throw new InvalidArgumentException("Sort direction must be 'asc' or 'desc', got '{$direction}'.");
        }
        $this->sorts[] = "{$field}-{$dir}";
        return $this;
    }

    /** Sorts by last-modified date, newest first. */
    public function sortByModified(string $direction = 'desc'): self
    {
        return $this->sort('changedAt', $direction);
    }

    /** Sorts by creation date, newest first. */
    public function sortByCreated(string $direction = 'desc'): self
    {
        return $this->sort('createdAt', $direction);
    }

    // -------------------------------------------------------------------------
    // Pagination
    // -------------------------------------------------------------------------

    /**
     * Sets the maximum number of records to return per request.
     * Values below 1 are clamped to 1; values above {@see MAX_LIMIT} are capped.
     */
    public function limit(int $limit): self
    {
        $this->limit = max(1, min($limit, self::MAX_LIMIT));
        return $this;
    }

    /** Sets the number of records to skip (zero-based). */
    public function offset(int $offset): self
    {
        $this->offset = max(0, $offset);
        return $this;
    }

    // -------------------------------------------------------------------------
    // Field selection
    // -------------------------------------------------------------------------

    /**
     * Restricts the returned fields to the given list.
     * Reduces payload size for large collections.
     *
     * @param list<string> $fields
     */
    public function fields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Returns the currently configured field list (empty when not set).
     *
     * @return list<string>
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Sets the page size used by {@see AbstractResource::cursor()} when iterating large
     * datasets.  This is semantically distinct from {@see limit()}:
     *
     * - `limit(N)` controls how many records a single `list()` call fetches (one request).
     * - `pageSize(N)` controls the internal per-request chunk size that `cursor()` uses
     *   while auto-paginating through the full result set.
     *
     * Increasing the page size reduces the number of HTTP round trips for large datasets.
     * Values above 100 are accepted by the Docbee API (live-verified):
     *
     * ```php
     * // Fetch 4 000 invoices in ~8 requests instead of ~80
     * foreach ($client->invoices()->cursor(QueryBuilder::new()->pageSize(500)) as $inv) { … }
     * ```
     *
     * Values are clamped to [1, {@see MAX_LIMIT}].
     */
    public function pageSize(int $size): self
    {
        // Intentionally NOT capped at MAX_LIMIT — endpoints like /docBeeDocument accept
        // 500+ records per page.  /invoice caps at 100 server-side (live-verified).
        // The resource is responsible for choosing an appropriate page size.
        $this->pageSize = max(1, $size);
        return $this;
    }

    /**
     * Returns the configured page size, or null when not explicitly set.
     * `null` instructs {@see AbstractResource::cursor()} to use its own default.
     */
    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    /**
     * Returns the configured limit (records per `list()` request).
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    // -------------------------------------------------------------------------
    // Build
    // -------------------------------------------------------------------------

    /**
     * Builds the complete query string including pagination.
     *
     * @return string Query string starting with '?' or empty string if no params.
     */
    public function build(): string
    {
        $params = $this->filters;
        $params['limit']  = (string) $this->limit;
        $params['offset'] = (string) $this->offset;

        if (!empty($this->sorts)) {
            $params['sort'] = implode(',', $this->sorts);
        }
        if (!empty($this->fields)) {
            $params['fields'] = implode(',', $this->fields);
        }

        return '?' . http_build_query($params);
    }

    /**
     * Builds a query string for one cursor page with an **unclamped** page size.
     *
     * Used internally by {@see AbstractResource::cursor()} so that resources which
     * support larger-than-100 pages (e.g. `DocumentResource` with up to 500/page)
     * can benefit from fewer round trips without the {@see MAX_LIMIT} cap of
     * {@see limit()} interfering.
     *
     * Do **not** call this method directly — use {@see pageSize()} in your QueryBuilder
     * and let `cursor()` pick it up automatically.
     *
     * @internal
     */
    public function buildForPage(int $pageSize, int $offset): string
    {
        $params = $this->filters;
        $params['limit']  = (string) max(1, $pageSize);
        $params['offset'] = (string) max(0, $offset);

        if (!empty($this->sorts)) {
            $params['sort'] = implode(',', $this->sorts);
        }
        if (!empty($this->fields)) {
            $params['fields'] = implode(',', $this->fields);
        }

        return '?' . http_build_query($params);
    }

    /**
     * Builds a query string suitable for count requests (no pagination).
     *
     * @return string Query string starting with '?' or empty string.
     */
    public function buildForCount(): string
    {
        $params = $this->filters;
        $params['limit']  = '0';
        $params['offset'] = '0';

        return '?' . http_build_query($params);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** Converts a PHP value to its string representation for the query string. */
    private function normalizeValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        return (string) $value;
    }
}
