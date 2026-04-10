<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TimeRecord record.
 */
final class TimeRecordDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $enabled,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            enabled: self::toBool($data['enabled'] ?? false),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'enabled' => $this->enabled,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isEnabled(): bool { return $this->enabled; }
}