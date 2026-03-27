<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee system user (agent/technician).
 */
final class UserDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $name,
        private readonly ?string $firstName,
        private readonly ?string $email,
        private readonly ?bool   $active,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    /** @inheritDoc */
    public static function fromArray(array $data): static
    {
        return new self(
            id:        self::toInt($data['id'] ?? null),
            name:      self::toString($data['name'] ?? null),
            firstName: self::toString($data['firstName'] ?? null),
            email:     self::toString($data['email'] ?? null),
            active:    isset($data['active']) ? self::toBool($data['active']) : null,
            createdAt: self::toString($data['createdAt'] ?? null),
            changedAt: self::toString($data['changedAt'] ?? null),
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return array_filter([
            'name'      => $this->name,
            'firstName' => $this->firstName,
            'email'     => $this->email,
            'active'    => $this->active,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int           { return $this->id; }
    public function getName(): ?string      { return $this->name; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function getEmail(): ?string     { return $this->email; }
    public function isActive(): ?bool       { return $this->active; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getChangedAt(): ?string { return $this->changedAt; }
}
