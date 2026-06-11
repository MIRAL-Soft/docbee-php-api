<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TableConfigStorageField entry on a board or table config.
 */
final class TableConfigStorageFieldDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific field */
        private readonly ?int    $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** type */
        private ?string          $type,
        /** sortOrder */
        private ?int             $sortOrder,
        /** sortType */
        private ?string          $sortType,
        /** customField identifier */
        private ?int             $customField,
        /** protocolColumnName */
        private ?string          $protocolColumnName,
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                 self::toInt($data['id'] ?? null),
            modified:           self::toString($data['modified'] ?? null),
            link:               self::toString($data['link'] ?? null),
            type:               self::toString($data['type'] ?? null),
            sortOrder:          self::toInt($data['sortOrder'] ?? null),
            sortType:           self::toString($data['sortType'] ?? null),
            customField:        self::toInt($data['customField'] ?? null),
            protocolColumnName: self::toString($data['protocolColumnName'] ?? null),
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'type'               => $this->type,
            'sortOrder'          => $this->sortOrder,
            'sortType'           => $this->sortType,
            'customField'        => $this->customField,
            'protocolColumnName' => $this->protocolColumnName,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int                    { return $this->id; }
    public function getModified(): ?string           { return $this->modified; }
    public function getLink(): ?string               { return $this->link; }
    public function getType(): ?string               { return $this->type; }
    public function getSortOrder(): ?int             { return $this->sortOrder; }
    public function getSortType(): ?string           { return $this->sortType; }
    public function getCustomField(): ?int           { return $this->customField; }
    public function getProtocolColumnName(): ?string { return $this->protocolColumnName; }
}
