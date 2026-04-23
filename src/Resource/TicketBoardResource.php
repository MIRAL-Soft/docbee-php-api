<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketBoardDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketBoard records.
 *
 * @extends AbstractResource<TicketBoardDTO>
 */
final class TicketBoardResource extends AbstractResource
{
    protected string $endpoint = 'ticketBoard';
    protected string $dtoClass = TicketBoardDTO::class;
    protected string $listKey  = 'ticketBoard';

    public function setFavorite(int $id): void { $this->http->put("{$this->endpoint}/{$id}/setFavorite", []); }
    public function unsetFavorite(int $id): void { $this->http->put("{$this->endpoint}/{$id}/unsetFavorite", []); }
}