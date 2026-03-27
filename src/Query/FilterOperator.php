<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Query;

/**
 * Backed string enum of filter operators supported by the Docbee REST API.
 *
 * Use these cases with {@see QueryBuilder::filter()} to build type-safe
 * query filters without having to remember raw operator strings.
 *
 * ```php
 * QueryBuilder::new()
 *     ->filter('priority', FilterOperator::GT, 2)
 *     ->filter('status',   FilterOperator::EQ, 1)
 *     ->build();
 * ```
 *
 * All shortcut methods (`filterEq`, `filterGt`, etc.) use these cases internally,
 * so direct use of the enum is only needed when calling `filter()` directly.
 */
enum FilterOperator: string
{
    /** Equals (==). */
    case EQ = 'eq';

    /** Not equals (!=). */
    case NEQ = 'neq';

    /** Case-insensitive LIKE match (wildcard: %). */
    case ILIKE = 'ilike';

    /** Greater than (>). */
    case GT = 'gt';

    /** Greater than or equal (>=). */
    case GTE = 'gte';

    /** Less than (<). */
    case LT = 'lt';

    /** Less than or equal (<=). */
    case LTE = 'lte';

    /** Value is contained in a list (IN). */
    case IN = 'in';
}
