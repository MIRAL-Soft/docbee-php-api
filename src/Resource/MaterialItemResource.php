<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MaterialItemDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee MaterialItem records.
 *
 * @extends AbstractResource<MaterialItemDTO>
 *
 * **Filter limitations (confirmed against OpenAPI spec 2025.3.0 and live tests):**
 *
 * The `GET /materialItem` endpoint exposes only seven query parameters:
 * `deactivated`, `limit`, `offset`, `fields`, `changedSince`, `sortings`,
 * and `tableSortings`.  There is no server-side filter for `number`, `name`,
 * or any other content field, and the endpoint is not searchable via `search`.
 *
 * As a result, {@see findByNumber()} performs a full client-side cursor scan
 * (O(n) over the entire catalogue).  For production use with large catalogues,
 * maintain your own `number → id` lookup or load the full catalogue once and
 * cache it for the lifetime of the sync run.
 */
final class MaterialItemResource extends AbstractResource
{
    use NotSearchable;

    protected string $endpoint = 'materialItem';
    protected string $dtoClass = MaterialItemDTO::class;
    protected string $listKey  = 'materialItem';

    /**
     * Finds a material item by its `number` field.
     *
     * **Performance warning:** The Docbee API provides no server-side number filter
     * for material items.  This method iterates the entire catalogue via the memory-
     * efficient {@see cursor()} generator and returns the first record whose `number`
     * matches exactly.  For catalogues with thousands of items this may take several
     * seconds and many API round-trips.
     *
     * **Recommendation:** cache the result for the duration of a sync run, or build
     * your own `number → id` map from a single `listAll()` call at startup.
     *
     * ```php
     * $item = $client->materialItems()->findByNumber('SW-1234');
     * if ($item === null) {
     *     // number does not exist in Docbee catalogue
     * }
     * ```
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByNumber(string $number): ?MaterialItemDTO
    {
        foreach ($this->cursor() as $item) {
            if ($item->getNumber() === $number) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Returns all active (non-deactivated) material items.
     *
     * Convenience wrapper that adds `deactivated=0` — the only server-side content
     * filter the endpoint supports.
     *
     * @return list<MaterialItemDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findActive(): array
    {
        return $this->listAll(QueryBuilder::new()->param('deactivated', false));
    }

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
