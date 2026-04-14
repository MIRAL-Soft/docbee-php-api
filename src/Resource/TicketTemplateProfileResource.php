<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TicketTemplateProfileDTO;

/**
 * Provides access to Docbee TicketTemplateProfile records.
 *
 * @extends AbstractResource<TicketTemplateProfileDTO>
 */
final class TicketTemplateProfileResource extends AbstractResource
{
    protected string $endpoint = 'v1/ticketTemplateProfile';
    protected string $dtoClass = TicketTemplateProfileDTO::class;
    protected string $listKey  = 'ticketTemplateProfile';
}
