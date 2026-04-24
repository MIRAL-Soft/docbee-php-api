<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource\Concerns;

/**
 * Marks a resource as not supporting the Docbee `search` query parameter.
 *
 * The Docbee REST API only provides a `search` parameter on a subset of
 * endpoints.  Applying this trait to a resource class overrides the default
 * {@see \miralsoft\docbee\api\Resource\AbstractResource::search()} with a
 * version that throws immediately, preventing the silent "returns all records"
 * failure that would otherwise occur when `search` is sent to an endpoint that
 * does not recognise it.
 *
 * Usage: add `use NotSearchable;` to every resource class whose endpoint is
 * NOT listed as supporting `search` in the Docbee OpenAPI specification.
 */
trait NotSearchable
{
    /**
     * Not supported — this endpoint has no `search` parameter.
     *
     * The Docbee API silently ignores unknown query parameters, so calling
     * this method without the trait would return **all** records instead of
     * a filtered subset, which is almost certainly not what the caller wants.
     *
     * If you need to filter records of this resource, use {@see list()} with
     * an appropriate {@see \miralsoft\docbee\api\Query\QueryBuilder} filter,
     * or use {@see listAll()} and filter the result client-side.
     *
     * @throws \BadMethodCallException always — search is not supported here.
     *
     * @param string $query Unused.
     * @return never
     */
    public function search(string $query): array
    {
        throw new \BadMethodCallException(
            sprintf(
                '%s does not support search(). The Docbee API endpoint "%s" has no `search` parameter. '
                . 'Use list() with a QueryBuilder filter or listAll() + client-side filtering instead.',
                static::class,
                $this->endpoint,
            )
        );
    }
}
