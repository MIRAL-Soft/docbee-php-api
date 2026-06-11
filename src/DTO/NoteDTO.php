<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Note record.
 */
final class NoteDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific note */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** The text content of the note */
        private ?string $note
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            note: self::toString($data['note'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'note' => $this->note
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getNote(): ?string { return $this->note; }
}