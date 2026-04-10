<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee UserActivity record.
 */
final class UserActivityDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $processed,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            processed: self::toBool($data['processed'] ?? false),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'processed' => $this->processed,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isProcessed(): bool { return $this->processed; }
}