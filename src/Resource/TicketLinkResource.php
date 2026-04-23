<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketLinkDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TicketLink records (sub-resource).
 *
 * @extends AbstractResource<TicketLinkDTO>
 */
final class TicketLinkResource extends AbstractResource
{
    protected string $dtoClass = TicketLinkDTO::class;
    protected string $listKey  = 'ticketLink';

    public function __construct(HttpClientInterface $http, int $ticketId)
    {
        $this->endpoint = "ticket/{$ticketId}/link";
        parent::__construct($http);
    }
}