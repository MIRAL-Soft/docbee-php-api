<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketBoardProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee TicketBoardProfile records.
 *
 * @extends AbstractResource<TicketBoardProfileDTO>
 */
final class TicketBoardProfileResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'ticketBoardProfile';
    protected string $dtoClass = TicketBoardProfileDTO::class;
    protected string $listKey  = 'ticketBoardProfile';
}