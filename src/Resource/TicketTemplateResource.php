<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketTemplate records.
 *
 * @extends AbstractResource<TicketTemplateDTO>
 */
final class TicketTemplateResource extends AbstractResource
{
    protected string $endpoint = 'v1/ticketTemplate';
    protected string $dtoClass = TicketTemplateDTO::class;
    protected string $listKey  = 'ticketTemplate';

    public function clone(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/clone", []); }
    public function createPayloadForTicket(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/createPayloadForTicket"); }
}