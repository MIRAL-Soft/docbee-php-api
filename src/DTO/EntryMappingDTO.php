<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee EntryMapping record.
 */
final class EntryMappingDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific entryMapping */
        private readonly ?int $id,
        /** modified timestamp */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** entryStyle */
        private readonly ?int $entryStyle,
        /** protocolTemplateEntry */
        private ?int $protocolTemplateEntry,
        /** isMultiGroupLabel */
        private ?bool $isMultiGroupLabel
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            entryStyle: self::toInt($data['entryStyle'] ?? null),
            protocolTemplateEntry: self::toInt($data['protocolTemplateEntry'] ?? null),
            isMultiGroupLabel: self::toBool($data['isMultiGroupLabel'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'protocolTemplateEntry' => $this->protocolTemplateEntry,
            'isMultiGroupLabel' => $this->isMultiGroupLabel
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getEntryStyle(): ?int { return $this->entryStyle; }
    public function getProtocolTemplateEntry(): ?int { return $this->protocolTemplateEntry; }
    public function getIsMultiGroupLabel(): ?bool { return $this->isMultiGroupLabel; }
}
