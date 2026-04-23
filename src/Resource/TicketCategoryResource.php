<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketCategory records.
 *
 * @extends AbstractResource<TicketCategoryDTO>
 */
final class TicketCategoryResource extends AbstractResource
{
    protected string $endpoint = 'ticketCategory';
    protected string $dtoClass = TicketCategoryDTO::class;
    protected string $listKey  = 'ticketCategory';

    public function getCustomFields(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/customFields"); }
    public function updateCustomFields(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/customFields", $data); }
}