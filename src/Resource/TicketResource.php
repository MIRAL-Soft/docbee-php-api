<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketDTO;
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
 * // All tickets for a customer, optionally filtered by status ID
 * $tickets = $resource->findByCustomer(42, statusId: 1);
 *
 * // All tickets with a specific status
 * $tickets = $resource->findByStatus(1);
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
     * @param int      $customerId The customer's numeric ID.
     * @param int|null $statusId   Optional ticket status ID to filter by (use the ID from TicketStatusResource).
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId, ?int $statusId = null): array
    {
        $query = QueryBuilder::new()->filterEq('customer', $customerId);
        if ($statusId !== null) {
            $query->filterEq('status', $statusId);
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
     * Returns all tickets with the given status ID.
     *
     * Retrieve available status IDs via {@see \miralsoft\docbee\api\Resource\TicketStatusResource::listAll()}.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByStatus(int $statusId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('status', $statusId));
    }

}
