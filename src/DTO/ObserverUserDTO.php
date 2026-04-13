<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ObserverUser record.
 */
final class ObserverUserDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $link,
        private ?string $email,
        private ?bool $enabled,
        private ?string $name,
        private ?int $observer,
        private ?string $password,
        private ?string $shorthandName,
        private ?bool $use2FA,
        private ?string $username
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            email: self::toString($data['email'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            name: self::toString($data['name'] ?? null),
            observer: self::toInt($data['observer'] ?? null),
            password: self::toString($data['password'] ?? null),
            shorthandName: self::toString($data['shorthandName'] ?? null),
            use2FA: isset($data['use2FA']) ? self::toBool($data['use2FA']) : null,
            username: self::toString($data['username'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'email' => $this->email,
            'enabled' => $this->enabled,
            'name' => $this->name,
            'observer' => $this->observer,
            'password' => $this->password,
            'shorthandName' => $this->shorthandName,
            'use2FA' => $this->use2FA,
            'username' => $this->username
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getEmail(): ?string { return $this->email; }
    public function getEnabled(): ?bool { return $this->enabled; }
    public function getName(): ?string { return $this->name; }
    public function getObserver(): ?int { return $this->observer; }
    public function getPassword(): ?string { return $this->password; }
    public function getShorthandName(): ?string { return $this->shorthandName; }
    public function getUse2FA(): ?bool { return $this->use2FA; }
    public function getUsername(): ?string { return $this->username; }
}