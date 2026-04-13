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
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?string $text
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            text: self::toString($data['text'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getText(): ?string { return $this->text; }
}