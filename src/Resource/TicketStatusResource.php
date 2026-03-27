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
 */
final class TicketStatusResource extends AbstractResource
{
    protected string $endpoint = 'ticketstatus';
    protected string $dtoClass = TicketStatusDTO::class;
    protected string $listKey  = 'ticketStatus';

    /**
     * Finds a ticket status by its exact name.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): TicketStatusDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('name', $name)->limit(1));
        if (empty($results)) {
            throw new NotFoundException("TicketStatus with name '{$name}' not found.", 404);
        }
        return $results[0];
    }

    /**
     * Returns only "closed" statuses.
     *
     * @return list<TicketStatusDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findClosed(): array
    {
        return $this->list(QueryBuilder::new()->filterEq('isClosed', true));
    }
}
