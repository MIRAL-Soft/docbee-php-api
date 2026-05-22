<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use Generator;
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
 *
 * @note The Docbee API interprets `id-eq` as a foreign-key (customer ID) filter
 *       for this resource, not as a primary-key filter. To fetch a single record
 *       by its own ID, use find(int $id) instead of list(filterEq('id', ...)).
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
     * Returns all tickets linked to a specific customer contact.
     *
     * Uses the plain `customerContact=<id>` query parameter required by this endpoint
     * (the standard `customerContact-eq=<id>` operator form is silently ignored by the
     * Docbee API for relation filters).
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomerContact(int $contactId): array
    {
        return $this->listAll(QueryBuilder::new()->param('customerContact', $contactId));
    }

    /**
     * Returns all tickets linked to a specific customer location.
     *
     * Uses the plain `customerLocation=<id>` query parameter required by this endpoint
     * (the standard `customerLocation-eq=<id>` operator form is silently ignored by the
     * Docbee API for relation filters).
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomerLocation(int $locationId): array
    {
        return $this->listAll(QueryBuilder::new()->param('customerLocation', $locationId));
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
     * Returns tickets whose `erpReferenceNumber` matches the given value exactly.
     *
     * The Docbee API provides no server-side filter for `erpReferenceNumber` on the
     * `/ticket` endpoint — neither `erpReferenceNumber-eq` nor the plain parameter form
     * is recognised; both are silently ignored and return the entire dataset (55 000+
     * records for large tenants, taking 5+ minutes).
     *
     * **Implemented strategy (confirmed by live tests):**
     * 1. Issue a server-side `search=<value>` request — Docbee searches across multiple
     *    fields including `erpReferenceNumber`, returning a small hit set (typically
     *    0–10 records) in 2–4 seconds regardless of tenant size.
     * 2. Request `fields=id,erpReferenceNumber` so the field is included in the list
     *    response without extra individual `find()` calls.
     * 3. Filter client-side for an exact string match — `search` is a broad full-text
     *    operation that may also return tickets where the term appears in other fields
     *    (title, description, reference number, …).
     *
     * **Performance:** O(hits from search) API calls, not O(total tickets).
     * Typical timing: ≤ 5 seconds for any tenant size.
     *
     * ```php
     * $tickets = $resource->findByErpReferenceNumber('WO-12345');
     * // Returns only tickets whose erpReferenceNumber === 'WO-12345'
     * ```
     *
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByErpReferenceNumber(string $erpReferenceNumber): array
    {
        // search() is a server-side full-text match.  We include erpReferenceNumber
        // in the field selection so the value comes back in the list response,
        // enabling exact-match filtering without additional find() round-trips.
        $hits = $this->listAll(
            QueryBuilder::new()
                ->search($erpReferenceNumber)
                ->fields(['id', 'erpReferenceNumber']),
        );

        return array_values(
            array_filter(
                $hits,
                fn(TicketDTO $t) => $t->getErpReferenceNumber() === $erpReferenceNumber,
            ),
        );
    }

    /**
     * Returns a generator that yields all tickets that do not have the given status ID.
     *
     * Memory-efficient — auto-paginates and yields one ticket at a time without
     * loading the entire dataset into memory.  Useful for iterating open/active
     * tickets in delta-sync scenarios.
     *
     * ```php
     * foreach ($resource->iterateNonClosed($closedStatusId) as $ticket) {
     *     sync($ticket);
     * }
     * ```
     *
     * @return Generator<int, TicketDTO, void, void>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function iterateNonClosed(int $closedStatusId): Generator
    {
        return $this->cursor(QueryBuilder::new()->filterNeq('ticketStatus', $closedStatusId));
    }

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }
    /**
     * Creates a new ticket from a template.
     *
     * **Required payload key is `templateId`** — the `template` key causes HTTP 400.
     * Alternatively pass `['templateName' => 'My Template']` in `$data`.
     *
     * @param int   $templateId Docbee ticket template ID.
     * @param array $data       Additional fields merged into the request body.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function fromTemplate(int $templateId, array $data = []): TicketDTO
    {
        return TicketDTO::fromArray(
            $this->http->post("{$this->endpoint}/fromTemplate", array_merge(['templateId' => $templateId], $data))
        );
    }
    public function findByNumber(string $number): TicketDTO { return TicketDTO::fromArray($this->http->get("{$this->endpoint}/findByNumber/{$number}")); }
    public function clone(int $id): TicketDTO { return TicketDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/clone", [])); }
    public function merge(int $id, int $sourceTicketId): TicketDTO { return TicketDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/merge/{$sourceTicketId}", [])); }
    public function isMerged(int $id): bool { $r = $this->http->get("{$this->endpoint}/{$id}/isMerged"); return (bool)($r['isMerged'] ?? false); }
    public function getStatusChange(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/statusChange"); }
    public function subscribe(int $id): void { $this->http->put("{$this->endpoint}/{$id}/subscribe", []); }
    public function unsubscribe(int $id): void { $this->http->put("{$this->endpoint}/{$id}/unsubscribe", []); }
    public function poke(int $id, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/poke", $data); }
    public function reply(int $id, int $messageId, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/reply/{$messageId}", $data); }
    public function forward(int $id, int $messageId, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/forward/{$messageId}", $data); }
    public function getMessageData(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/messageData"); }
    public function finishExternalSla(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/finishExternalSla", $data); }
    public function executeAction(int $id, int $actionId, array $data = []): array { return $this->http->post("{$this->endpoint}/{$id}/action/{$actionId}/execute", $data); }
    public function export(int $exportProfileId): string { return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): string { return $this->http->postRaw("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]); }
}
