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
    /** Maximum number of records per page allowed by the Docbee API. */
    private const MAX_LIMIT = 100;

    /** @var array<string, string> */
    private array $filters = [];

    /** @var list<string> */
    private array $sorts = [];

    private int $limit  = 50;
    private int $offset = 0;

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
    // Timestamp helpers
    // -------------------------------------------------------------------------

    /**
     * Filters for records modified after the given date/time.
     * Maps to the `changedSince` query parameter.
     */
    public function modifiedSince(DateTimeInterface $since): self
    {
        $this->filters['changedSince'] = $since->format('Y-m-d\TH:i:s');
        return $this;
    }

    /**
     * Filters for records created after the given date/time.
     * Maps to the `createdSince` query parameter.
     */
    public function createdSince(DateTimeInterface $since): self
    {
        $this->filters['createdSince'] = $since->format('Y-m-d\TH:i:s');
        return $this;
    }

    // -------------------------------------------------------------------------
    // Sorting
    // -------------------------------------------------------------------------

    /**
     * Adds a sort directive.
     *
     * @param string $direction 'asc' or 'desc'
     * @throws InvalidArgumentException on invalid direction.
     */
    public function sort(string $field, string $direction = 'asc'): self
    {
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
     * Capped at {@see MAX_LIMIT}.
     */
    public function limit(int $limit): self
    {
        $this->limit = min($limit, self::MAX_LIMIT);
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
