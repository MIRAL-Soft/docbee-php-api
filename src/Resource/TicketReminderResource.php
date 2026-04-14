<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ReminderDTO;

/**
 * Provides access to Docbee Reminder records (sub-resource of ticket).
 *
 * @extends AbstractResource<ReminderDTO>
 */
final class TicketReminderResource extends AbstractResource
{
    protected string $dtoClass = ReminderDTO::class;
    protected string $listKey  = 'reminder';

    public function __construct(HttpClientInterface $http, int $ticketId)
    {
        $this->endpoint = "v1/ticket/{$ticketId}/reminder";
        parent::__construct($http);
    }
}
