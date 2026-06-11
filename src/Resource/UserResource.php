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
     * Delegates to the dedicated `/user/findFirstByEmail/{email}` endpoint which
     * performs a server-side exact match.  The previously used `email-eq=` filter
     * parameter is silently ignored by the Docbee API (returns unfiltered results).
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByEmail(string $email): UserDTO
    {
        return $this->findFirstByEmail($email);
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

    /** Returns the currently authenticated user. */
    public function me(): UserDTO
    {
        return UserDTO::fromArray($this->http->get('user/me'));
    }

    /**
     * Resets two-factor authentication for a specific user (admin).
     *
     * Maps to `PUT user/{id}/reset2FA`.
     */
    public function reset2FA(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/reset2FA", []);
    }

    /**
     * @deprecated Misleading name — this method has always called `PUT user/{id}/reset2FA`
     *             and therefore resets **two-factor authentication**, NOT the password.
     *             Use {@see reset2FA()} for the identical behaviour, or
     *             {@see changePassword()} to actually change a user's password
     *             (the API has no per-user password-*reset* endpoint).
     *             This alias will be removed in the next major version.
     */
    public function resetPasswordForUser(int $id): void
    {
        @trigger_error(
            'UserResource::resetPasswordForUser() is deprecated: it resets 2FA, not the password. '
            . 'Use reset2FA() or changePassword() instead.',
            E_USER_DEPRECATED,
        );
        $this->reset2FA($id);
    }

    /**
     * Find user by email address via API endpoint.
     *
     * The email is URL-encoded — emails legitimately contain `+` and other
     * URL-significant characters that must not alter the request path.
     *
     * @throws \InvalidArgumentException when $email is empty.
     */
    public function findFirstByEmail(string $email): UserDTO
    {
        if (trim($email) === '') {
            throw new \InvalidArgumentException('findFirstByEmail(): email must not be empty.');
        }
        return UserDTO::fromArray($this->http->get("{$this->endpoint}/findFirstByEmail/" . rawurlencode($email)));
    }

    /**
     * Find user by external ERP number.
     *
     * @throws \InvalidArgumentException when $number is empty.
     */
    public function findFirstByExternalErpNumber(string $number): UserDTO
    {
        if (trim($number) === '') {
            throw new \InvalidArgumentException('findFirstByExternalErpNumber(): number must not be empty.');
        }
        return UserDTO::fromArray(
            $this->http->get("{$this->endpoint}/findFirstByExternalErpNumber/" . rawurlencode($number))
        );
    }

    /** Get user settings for current user. */
    public function getSettings(): array
    {
        return $this->http->get('user/me/settings');
    }

    /** Update user settings for current user. */
    public function updateSettings(array $data): array
    {
        return $this->http->put('user/me/settings', $data);
    }

    /** Update profile image for current user. */
    public function updateProfileImage(array $data): void
    {
        $this->http->put('user/me/profileImage', $data);
    }

    /** Change password for current user. */
    public function changeMyPassword(array $data): void
    {
        $this->http->post('user/me/changePassword', $data);
    }

    /** Change password for a specific user (admin). */
    public function changePassword(array $data): void
    {
        $this->http->post("{$this->endpoint}/changePassword", $data);
    }

    /** Disable 2FA for the currently authenticated user. */
    public function disableMe2FA(): void
    {
        $this->http->put('user/me/disable2FA', []);
    }

    /** Register 2FA for the currently authenticated user. */
    public function registerMe2FA(array $data = []): void
    {
        $this->http->put('user/me/register2FA', $data);
    }
}
