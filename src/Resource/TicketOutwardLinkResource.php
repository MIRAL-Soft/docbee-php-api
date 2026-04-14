<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketLinkDTO;

/**
 * Provides access to Docbee outward TicketLink records (sub-resource of ticket).
 *
 * @extends AbstractResource<TicketLinkDTO>
 */
final class TicketOutwardLinkResource extends AbstractResource
{
    protected string $dtoClass = TicketLinkDTO::class;
    protected string $listKey  = 'outwardTicketLink';

    public function __construct(HttpClientInterface $http, int $ticketId)
    {
        $this->endpoint = "v1/ticket/{$ticketId}/outwardLink";
        parent::__construct($http);
    }
}
