<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketBoardProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketBoardProfile records.
 *
 * @extends AbstractResource<TicketBoardProfileDTO>
 */
final class TicketBoardProfileResource extends AbstractResource
{
    protected string $endpoint = 'v1/ticketBoardProfile';
    protected string $dtoClass = TicketBoardProfileDTO::class;
    protected string $listKey  = 'ticketBoardProfile';
}