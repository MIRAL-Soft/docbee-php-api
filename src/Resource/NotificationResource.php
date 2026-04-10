<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\NotificationDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Notification records.
 *
 * @extends AbstractResource<NotificationDTO>
 */
final class NotificationResource extends AbstractResource
{
    protected string $endpoint = 'v1/notification';
    protected string $dtoClass = NotificationDTO::class;
    protected string $listKey  = 'notification';
}