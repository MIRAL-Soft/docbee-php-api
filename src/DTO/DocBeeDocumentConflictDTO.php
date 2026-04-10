<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeDocumentConflict record.
 */
final class DocBeeDocumentConflictDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $text,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            text: self::toString($data['text'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'text' => $this->text,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getText(): ?string { return $this->text; }
}