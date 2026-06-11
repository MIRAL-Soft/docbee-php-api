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
     * The Docbee API provides **no** server-side filter for `erpReferenceNumber` on the
     * `/ticket` endpoint — `erpReferenceNumber`, `erpReferenceNumber-eq` and the plain form
     * are all silently ignored (they return the entire dataset).  There is also no ERP
     * `creatorSources` shortcut (ERP-referenced tickets are not necessarily ERP-sourced).
     *
     * **Implemented strategy (live-verified 2026-05-23, pcs tenant, 55 952 tickets):**
     * 1. Call the global search endpoint `GET /search/{value}` — it returns a compact list
     *    of candidate **ticket IDs** (plus other entity types) in ~0.7 s warm, far faster
     *    than the `/ticket?search=` list endpoint (~2.5–3.3 s, does not warm up).
     * 2. Fetch those few candidates via `GET /ticket?ids=…&fields=id,erpReferenceNumber`
     *    (one bounded request, ~70 ms).
     * 3. Filter client-side for an exact match — search is a broad full-text operation that
     *    also matches the term in title/description/etc.
     *
     * **Performance:** ~0.8 s end-to-end regardless of tenant size, versus ~2.5–4.8 s for
     * the previous `/ticket?search=` approach — a ~3–4× speed-up, identical results
     * (the two search backends return the same candidate set, live-verified).
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
        $candidateIds = $this->searchTicketIds($erpReferenceNumber);
        if (empty($candidateIds)) {
            return [];
        }

        return array_values(array_filter(
            $this->fetchTicketsByIds($candidateIds, ['id', 'erpReferenceNumber']),
            fn(TicketDTO $t) => $t->getErpReferenceNumber() === $erpReferenceNumber,
        ));
    }

    /**
     * Batch variant of {@see findByErpReferenceNumber()} — resolves many ERP reference
     * numbers to their tickets in one pass.
     *
     * Runs one global search per value (the search step is per-term and unavoidable), but
     * collects all candidate IDs and fetches them in a **single** chunked `/ticket?ids=`
     * request, then exact-matches each value.  Useful in delta runs that resolve many
     * Weclapp order numbers at once.
     *
     * ```php
     * $map = $resource->findByErpReferenceNumbers(['4993', 'WO-12345']);
     * // ['4993' => [TicketDTO …], 'WO-12345' => [TicketDTO …]]
     * $ticket = $map['4993'][0] ?? null;
     * ```
     *
     * @param  list<string> $erpReferenceNumbers
     * @return array<string, list<TicketDTO>> Map of value → exactly-matching tickets.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByErpReferenceNumbers(array $erpReferenceNumbers): array
    {
        $values = array_values(array_unique(array_filter(
            $erpReferenceNumbers,
            static fn($v) => is_string($v) && $v !== '',
        )));
        if (empty($values)) {
            return [];
        }

        // 1. One global search per value → candidate ticket IDs.
        $idsByValue = [];
        $allIds     = [];
        foreach ($values as $value) {
            $ids               = $this->searchTicketIds($value);
            $idsByValue[$value] = $ids;
            foreach ($ids as $id) {
                $allIds[$id] = true;
            }
        }

        // 2. Fetch every unique candidate ticket once (chunked at the /ticket page cap).
        $byId = [];
        foreach ($this->fetchTicketsByIds(array_keys($allIds), ['id', 'erpReferenceNumber']) as $ticket) {
            $byId[$ticket->getId()] = $ticket;
        }

        // 3. Exact-match each value against its own candidate set.
        $result = [];
        foreach ($values as $value) {
            $matches = [];
            foreach ($idsByValue[$value] as $id) {
                $ticket = $byId[$id] ?? null;
                if ($ticket !== null && $ticket->getErpReferenceNumber() === $value) {
                    $matches[] = $ticket;
                }
            }
            $result[$value] = $matches;
        }
        return $result;
    }

    /**
     * Returns candidate ticket IDs for a free-text term via the global `GET /search/{term}`
     * endpoint, which is markedly faster than the `/ticket?search=` list endpoint.
     *
     * @return list<int>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    private function searchTicketIds(string $term): array
    {
        $response = $this->http->get('search/' . rawurlencode($term));
        $ids      = $response['tickets'] ?? [];
        return is_array($ids) ? array_values(array_map('intval', $ids)) : [];
    }

    /**
     * Fetches tickets by their IDs, chunked at 50 (the `/ticket` endpoint's page-size cap).
     *
     * @param  list<int>    $ids
     * @param  list<string> $fields
     * @return list<TicketDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    private function fetchTicketsByIds(array $ids, array $fields): array
    {
        $out = [];
        foreach (array_chunk($ids, 50) as $chunk) {
            $page = $this->list(
                QueryBuilder::new()
                    ->param('ids', implode(',', $chunk))
                    ->fields($fields)
                    ->limit(50)
                    ->pageSize(50),
            );
            foreach ($page as $ticket) {
                $out[] = $ticket;
            }
        }
        return $out;
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
    /** @throws \InvalidArgumentException when $number is empty. */
    public function findByNumber(string $number): TicketDTO
    {
        if (trim($number) === '') {
            throw new \InvalidArgumentException('findByNumber(): number must not be empty.');
        }
        return TicketDTO::fromArray($this->http->get("{$this->endpoint}/findByNumber/" . rawurlencode($number)));
    }
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
