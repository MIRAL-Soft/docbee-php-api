<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\LoginResultDTO;

/** Provides access to Docbee authentication endpoints. */
final class AuthResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /** Login and get access token. */
    public function login(array $credentials): LoginResultDTO
    {
        return LoginResultDTO::fromArray($this->http->post('v1/login', $credentials));
    }

    /** Logout and invalidate token. */
    public function logout(): void
    {
        $this->http->post('v1/logout');
    }

    /** Request password reset email. */
    public function forgotPassword(string $username): void
    {
        $this->http->post('v1/forgotPassword', ['username' => $username]);
    }

    /** Reset password with hash code. */
    public function resetPassword(string $hashCode, string $newPassword, string $code = ''): void
    {
        $this->http->post('v1/resetPassword', ['hashCode' => $hashCode, 'newPassword' => $newPassword, 'code' => $code]);
    }
}
