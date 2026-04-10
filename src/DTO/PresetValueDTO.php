<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PresetValue record.
 */
final class PresetValueDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $group,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            group: self::toString($data['group'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'group' => $this->group,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getGroup(): ?string { return $this->group; }
}