<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MessageTemplate record.
 */
final class MessageTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $isDefault,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            isDefault: self::toBool($data['isDefault'] ?? false),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'isDefault' => $this->isDefault,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isIsDefault(): bool { return $this->isDefault; }
}