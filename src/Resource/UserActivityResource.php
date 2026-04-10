<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\UserActivityDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee UserActivity records.
 *
 * @extends AbstractResource<UserActivityDTO>
 */
final class UserActivityResource extends AbstractResource
{
    protected string $endpoint = 'v1/userActivity';
    protected string $dtoClass = UserActivityDTO::class;
    protected string $listKey  = 'userActivity';
}