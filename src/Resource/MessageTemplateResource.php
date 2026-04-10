<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MessageTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee MessageTemplate records.
 *
 * @extends AbstractResource<MessageTemplateDTO>
 */
final class MessageTemplateResource extends AbstractResource
{
    protected string $endpoint = 'v1/messageTemplate';
    protected string $dtoClass = MessageTemplateDTO::class;
    protected string $listKey  = 'messageTemplate';
}