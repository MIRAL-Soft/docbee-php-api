<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\UserActivityDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee UserActivity records.
 *
 * @extends AbstractResource<UserActivityDTO>
 */
final class UserActivityResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'userActivity';
    protected string $dtoClass = UserActivityDTO::class;
    protected string $listKey  = 'userActivity';
}