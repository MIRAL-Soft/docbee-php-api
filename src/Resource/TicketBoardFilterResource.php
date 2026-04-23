<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketBoardFilterDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketBoardFilter records (sub-resource).
 *
 * @extends AbstractResource<TicketBoardFilterDTO>
 */
final class TicketBoardFilterResource extends AbstractResource
{
    protected string $dtoClass = TicketBoardFilterDTO::class;
    protected string $listKey  = 'ticketBoardFilter';

    public function __construct(HttpClientInterface $http, int $boardId)
    {
        $this->endpoint = "ticketBoard/{$boardId}/filter";
        parent::__construct($http);
    }
}