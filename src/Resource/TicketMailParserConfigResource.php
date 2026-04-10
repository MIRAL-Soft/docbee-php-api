<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketMailParserConfigDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketMailParserConfig records.
 *
 * @extends AbstractResource<TicketMailParserConfigDTO>
 */
final class TicketMailParserConfigResource extends AbstractResource
{
    protected string $endpoint = 'v1/ticketMailParserConfig';
    protected string $dtoClass = TicketMailParserConfigDTO::class;
    protected string $listKey  = 'ticketMailParserConfig';
}