<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Query;

/**
 * Enumeration of filter operators supported by the Docbee REST API.
 *
 * Use these constants with {@see QueryBuilder::filter()} to build type-safe
 * query filters without having to remember raw operator strings.
 *
 * ```php
 * QueryBuilder::new()
 *     ->filter('status', FilterOperator::EQ, 'open')
 *     ->filter('priority', FilterOperator::GT, 2)
 *     ->build();
 * ```
 */
final class FilterOperator
{
    /** Equals (==). */
    public const EQ = 'eq';

    /** Not equals (!=). */
    public const NEQ = 'neq';

    /** Case-insensitive LIKE match (wildcard: %). */
    public const ILIKE = 'ilike';

    /** Greater than (>). */
    public const GT = 'gt';

    /** Greater than or equal (>=). */
    public const GTE = 'gte';

    /** Less than (<). */
    public const LT = 'lt';

    /** Less than or equal (<=). */
    public const LTE = 'lte';

    /** Value is contained in a list (IN). */
    public const IN = 'in';

    /** Private constructor – this class is not meant to be instantiated. */
    private function __construct() {}
}
