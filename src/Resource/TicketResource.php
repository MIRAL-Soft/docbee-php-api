<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee support tickets.
 *
 * ```php
 * $resource = $client->tickets();
 *
 * // Get a single ticket
 * $ticket = $resource->find(123);
 *
 * // All open tickets for a customer
 * $tickets = $resource->findByCustomer(42, status: 'open');
 *
 * // Delta-sync: tickets changed in the last 15 minutes
 * $changed = $resource->findModifiedSince(new DateTimeImmutable('-15 minutes'));
 * ```
 *
 * @extends AbstractResource<TicketDTO>
 */
final class TicketResource extends AbstractResource
{
    protected string $endpoint = 'ticket';
    protected string $dtoClass = TicketDTO::class;
    protected string $listKey  = 'ticket';

    /**
     * Returns all tickets for a given customer.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId, ?string $status = null): array
    {
        $query = QueryBuilder::new()->filterEq('customer', $customerId);
        if ($status !== null) {
            $query->filterEq('status', $status);
        }
        return $this->listAll($query);
    }

    /**
     * Returns tickets linked to a specific ERP order ID.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByOrderId(string $orderId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('orderId', $orderId));
    }

    /**
     * Returns tickets assigned to a specific user.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByAssignedUser(int $userId): array
    {
        return $this->list(QueryBuilder::new()->filterEq('assignedUser', $userId));
    }

    /**
     * Returns all open tickets.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findOpen(): array
    {
        return $this->list(QueryBuilder::new()->filterEq('status', 'open'));
    }
}
