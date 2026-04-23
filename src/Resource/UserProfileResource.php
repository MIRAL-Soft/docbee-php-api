<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\UserProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee UserProfile records.
 *
 * @extends AbstractResource<UserProfileDTO>
 */
final class UserProfileResource extends AbstractResource
{
    protected string $endpoint = 'userProfile';
    protected string $dtoClass = UserProfileDTO::class;
    protected string $listKey  = 'userProfile';
}