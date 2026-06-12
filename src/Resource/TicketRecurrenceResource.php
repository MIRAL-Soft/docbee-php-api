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
    protected string $endpoint = 'ticketRecurrence';
    protected string $dtoClass = TicketRecurrenceDTO::class;
    protected string $listKey  = 'ticketRecurrence';

    public function export(int $exportProfileId): string { return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}"); }

    /**
     * @param int[] $ids
     */
    public function exportByIds(int $exportProfileId, array $ids): string { return $this->postExportByIds("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids); }

    /**
     * @return array<string, mixed>
     */
    public function clone(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/clone", []); }
}