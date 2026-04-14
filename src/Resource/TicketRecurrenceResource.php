<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketRecurrenceDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketRecurrence records.
 *
 * @extends AbstractResource<TicketRecurrenceDTO>
 */
final class TicketRecurrenceResource extends AbstractResource
{
    protected string $endpoint = 'v1/ticketRecurrence';
    protected string $dtoClass = TicketRecurrenceDTO::class;
    protected string $listKey  = 'ticketRecurrence';

    public function export(int $exportProfileId): array { return $this->http->get("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): array { return $this->http->post("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]); }
    public function clone(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/clone", []); }
}