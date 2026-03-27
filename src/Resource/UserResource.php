<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\UserDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee user accounts.
 *
 * @extends AbstractResource<UserDTO>
 */
final class UserResource extends AbstractResource
{
    protected string $endpoint = 'user';
    protected string $dtoClass = UserDTO::class;
    protected string $listKey  = 'user';

    /**
     * Finds a user by their email address.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByEmail(string $email): UserDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('email', $email)->limit(1));
        if (empty($results)) {
            throw new NotFoundException("User with email '{$email}' not found.", 404, $this->endpoint);
        }
        return $results[0];
    }

    /**
     * Finds users whose name contains the given string (case-insensitive).
     *
     * @return list<UserDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): array
    {
        return $this->list(QueryBuilder::new()->filterIlike('name', "%{$name}%"));
    }

    /**
     * Returns only active users.
     *
     * @return list<UserDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findActive(): array
    {
        return $this->list(QueryBuilder::new()->filterEq('active', true));
    }
}
