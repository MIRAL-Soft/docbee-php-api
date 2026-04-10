<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ObjectCategory record.
 */
final class ObjectCategoryDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([

        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
}