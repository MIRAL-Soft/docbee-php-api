<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketStatusDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ticket status definitions.
 *
 * @extends AbstractResource<TicketStatusDTO>
 *
 * **Docbee filter quirks (confirmed by live tests):**
 *
 * - `name-eq=` is silently ignored on this endpoint.  {@see findByName()} uses a
 *   cursor scan + client-side exact match.
 *
 * - `isClosed` is NOT a real API field — it does not appear in any response.
 *   "Closed" statuses are identified by `behaviour === 'CLOSED'`.
 *   {@see findClosed()} requests `fields=id,name,behaviour` and filters client-side.
 */
final class TicketStatusResource extends AbstractResource
{
    protected string $endpoint = 'ticketStatus';
    protected string $dtoClass = TicketStatusDTO::class;
    protected string $listKey  = 'ticketStatus';

    /** Status behaviour value for open / in-progress statuses. */
    public const string BEHAVIOUR_NORMAL = 'NORMAL';

    /** Status behaviour value for closed / finished statuses. */
    public const string BEHAVIOUR_CLOSED = 'CLOSED';

    /** Status behaviour value for paused / on-hold statuses. */
    public const string BEHAVIOUR_PAUSED = 'PAUSED';

    /**
     * Finds a ticket status by its exact name.
     *
     * The Docbee API does not honour `name-eq=` on this endpoint — it is silently
     * ignored and returns the unfiltered list.  This method performs a cursor scan
     * (typically 1 page for tenants with ≤ 100 statuses) and applies an exact
     * client-side match.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): TicketStatusDTO
    {
        foreach ($this->cursor() as $status) {
            if ($status->getName() === $name) {
                return $status;
            }
        }

        throw new NotFoundException(
            message:    "TicketStatus with name '{$name}' not found.",
            statusCode: 404,
            requestUrl: $this->endpoint,
        );
    }

    /**
     * Returns only statuses whose behaviour is {@see BEHAVIOUR_CLOSED}.
     *
     * The field `isClosed` does not exist in the Docbee API — previously used
     * `filterEq('isClosed', true)` was silently ignored (returned all statuses).
     * "Closed" is determined by `behaviour === 'CLOSED'`; this method requests
     * `fields=id,name,behaviour` and filters client-side.
     *
     * @return list<TicketStatusDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findClosed(): array
    {
        $results = [];

        foreach ($this->cursor(QueryBuilder::new()->fields(['id', 'name', 'behaviour'])) as $status) {
            if ($status->getBehaviour() === self::BEHAVIOUR_CLOSED) {
                $results[] = $status;
            }
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
