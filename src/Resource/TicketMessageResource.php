<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketMessageDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketMessage records (sub-resource).
 *
 * @extends AbstractResource<TicketMessageDTO>
 */
final class TicketMessageResource extends AbstractResource
{
    protected string $dtoClass = TicketMessageDTO::class;
    protected string $listKey  = 'ticketMessage';

    public function __construct(HttpClientInterface $http, int $ticketId)
    {
        $this->endpoint = "v1/ticket/{$ticketId}/message";
        parent::__construct($http);
    }
}