<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ticket priority level.
 */
final class PriorityDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $name,
        private readonly ?int    $level,
        private readonly ?string $color,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    /** @inheritDoc */
    public static function fromArray(array $data): static
    {
        return new self(
            id:        self::toInt($data['id'] ?? null),
            name:      self::toString($data['name'] ?? null),
            level:     self::toInt($data['level'] ?? null),
            color:     self::toString($data['color'] ?? null),
            createdAt: self::toString($data['createdAt'] ?? null),
            changedAt: self::toString($data['changedAt'] ?? null),
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return array_filter([
            'name'  => $this->name,
            'level' => $this->level,
            'color' => $this->color,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int           { return $this->id; }
    public function getName(): ?string      { return $this->name; }
    public function getLevel(): ?int        { return $this->level; }
    public function getColor(): ?string     { return $this->color; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getChangedAt(): ?string { return $this->changedAt; }
}
