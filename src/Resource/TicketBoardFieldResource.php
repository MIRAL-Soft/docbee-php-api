<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketBoardFieldDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketBoardField records (sub-resource).
 *
 * @extends AbstractResource<TicketBoardFieldDTO>
 */
final class TicketBoardFieldResource extends AbstractResource
{
    protected string $dtoClass = TicketBoardFieldDTO::class;
    protected string $listKey  = 'ticketBoardField';

    public function __construct(HttpClientInterface $http, int $boardId)
    {
        $this->endpoint = "ticketBoard/{$boardId}/field";
        parent::__construct($http);
    }
}