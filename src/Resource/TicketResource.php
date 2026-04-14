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

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }
    public function fromTemplate(int $templateId, array $data = []): TicketDTO { return TicketDTO::fromArray($this->http->post("{$this->endpoint}/fromTemplate", array_merge(['template' => $templateId], $data))); }
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
    public function export(int $exportProfileId): array { return $this->http->get("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): array { return $this->http->post("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]); }
}
