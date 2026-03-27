<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee service type (billing category for time tracking).
 */
final class ServiceTypeDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $name,
        private readonly ?string $number,
        private readonly ?string $description,
        private readonly ?bool   $active,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    /** @inheritDoc */
    public static function fromArray(array $data): static
    {
        return new self(
            id:          self::toInt($data['id'] ?? null),
            name:        self::toString($data['name'] ?? null),
            number:      self::toString($data['number'] ?? null),
            description: self::toString($data['description'] ?? null),
            active:      isset($data['active']) ? self::toBool($data['active']) : null,
            createdAt:   self::toString($data['createdAt'] ?? null),
            changedAt:   self::toString($data['changedAt'] ?? null),
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return array_filter([
            'name'        => $this->name,
            'number'      => $this->number,
            'description' => $this->description,
            'active'      => $this->active,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int             { return $this->id; }
    public function getName(): ?string        { return $this->name; }
    public function getNumber(): ?string      { return $this->number; }
    public function getDescription(): ?string { return $this->description; }
    public function isActive(): ?bool         { return $this->active; }
    public function getCreatedAt(): ?string   { return $this->createdAt; }
    public function getChangedAt(): ?string   { return $this->changedAt; }
}
