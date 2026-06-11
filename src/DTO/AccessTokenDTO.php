<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AccessToken record.
 */
final class AccessTokenDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific access token */
        private readonly ?int $id,
        /** last used date */
        private ?string $lastUsedDate,
        /** lifetime refresh flag */
        private ?bool $lifeTimeRefresh,
        /** token value */
        private ?string $token,
        /** token lifetime in seconds */
        private ?int $lifeTime,
        /** token name */
        private ?string $name,
        /** expiry date */
        private ?string $expireDate
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            lastUsedDate: self::toString($data['lastUsedDate'] ?? null),
            lifeTimeRefresh: isset($data['lifeTimeRefresh']) ? self::toBool($data['lifeTimeRefresh']) : null,
            token: self::toString($data['token'] ?? null),
            lifeTime: self::toInt($data['lifeTime'] ?? null),
            name: self::toString($data['name'] ?? null),
            expireDate: self::toString($data['expireDate'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'lastUsedDate' => $this->lastUsedDate,
            'lifeTimeRefresh' => $this->lifeTimeRefresh,
            'token' => $this->token,
            'lifeTime' => $this->lifeTime,
            'name' => $this->name,
            'expireDate' => $this->expireDate,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLastUsedDate(): ?string { return $this->lastUsedDate; }
    public function isLifeTimeRefresh(): ?bool { return $this->lifeTimeRefresh; }
    public function getToken(): ?string { return $this->token; }
    public function getLifeTime(): ?int { return $this->lifeTime; }
    public function getName(): ?string { return $this->name; }
    public function getExpireDate(): ?string { return $this->expireDate; }
}
