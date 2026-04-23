<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketLinkDTO;

/**
 * Provides access to Docbee inward TicketLink records (sub-resource of ticket).
 *
 * @extends AbstractResource<TicketLinkDTO>
 */
final class TicketInwardLinkResource extends AbstractResource
{
    protected string $dtoClass = TicketLinkDTO::class;
    protected string $listKey  = 'inwardTicketLink';

    public function __construct(HttpClientInterface $http, int $ticketId)
    {
        $this->endpoint = "ticket/{$ticketId}/inwardLink";
        parent::__construct($http);
    }
}
