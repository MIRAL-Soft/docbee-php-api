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
    protected string $endpoint = 'v1/ticketBoard';
    protected string $dtoClass = TicketBoardDTO::class;
    protected string $listKey  = 'ticketBoard';
}