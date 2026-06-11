<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee login response.
 */
final class LoginResultDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing the user */
        private readonly ?int $id,
        /** access token value */
        private readonly ?string $access_token,
        /** username */
        private readonly ?string $username,
        /** expiry date */
        private readonly ?string $expires_date,
        /** token type */
        private readonly ?string $token_type,
        /** token name */
        private readonly ?string $token_name
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            access_token: self::toString($data['access_token'] ?? null),
            username: self::toString($data['username'] ?? null),
            expires_date: self::toString($data['expires_date'] ?? null),
            token_type: self::toString($data['token_type'] ?? null),
            token_name: self::toString($data['token_name'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return [];
    }

    public function getId(): ?int { return $this->id; }
    public function getAccessToken(): ?string { return $this->access_token; }
    public function getUsername(): ?string { return $this->username; }
    public function getExpiresDate(): ?string { return $this->expires_date; }
    public function getTokenType(): ?string { return $this->token_type; }
    public function getTokenName(): ?string { return $this->token_name; }
}
