<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketBoardColumnDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketBoardColumn records (sub-resource).
 *
 * @extends AbstractResource<TicketBoardColumnDTO>
 */
final class TicketBoardColumnResource extends AbstractResource
{
    protected string $dtoClass = TicketBoardColumnDTO::class;
    protected string $listKey  = 'ticketBoardColumn';

    public function __construct(HttpClientInterface $http, int $boardId)
    {
        $this->endpoint = "ticketBoard/{$boardId}/column";
        parent::__construct($http);
    }
}