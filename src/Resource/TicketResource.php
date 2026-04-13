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
 * // All tickets for a customer, optionally filtered by status
 * $tickets = $resource->findByCustomer(42, ticketStatusId: 1);
 *
 * // All tickets with a specific ticketStatus
 * $tickets = $resource->findByTicketStatus(1);
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
     * @param int      $customerId     The customer's numeric ID.
     * @param int|null $ticketStatusId Optional ticket status ID to filter by.
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId, ?int $ticketStatusId = null): array
    {
        $query = QueryBuilder::new()->filterEq('customer', $customerId);
        if ($ticketStatusId !== null) {
            $query->filterEq('ticketStatus', $ticketStatusId);
        }
        return $this->listAll($query);
    }

    /**
     * Returns all tickets with the given ticketStatus ID.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByTicketStatus(int $ticketStatusId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('ticketStatus', $ticketStatusId));
    }

    /**
     * Returns tickets assigned to a specific owner (user).
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByOwner(int $userId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('owner', $userId));
    }

    /**
     * Returns tickets matching a reference number.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByReferenceNumber(string $referenceNumber): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('referenceNumber', $referenceNumber));
    }

    /**
     * Returns tickets matching an ERP reference number.
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByErpReferenceNumber(string $erpReferenceNumber): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('erpReferenceNumber', $erpReferenceNumber));
    }
}
