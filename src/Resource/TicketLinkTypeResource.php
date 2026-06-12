<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketLinkTypeDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketLinkType records.
 *
 * @extends AbstractResource<TicketLinkTypeDTO>
 */
final class TicketLinkTypeResource extends AbstractResource
{
    protected string $endpoint = 'ticketLinkType';
    protected string $dtoClass = TicketLinkTypeDTO::class;
    protected string $listKey  = 'ticketLinkType';

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}