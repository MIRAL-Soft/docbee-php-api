<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketBoardField record.
 */
final class TicketBoardFieldDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?int $customField,
        private ?string $protocolColumnName,
        private ?int $sortOrder,
        private ?string $sortType,
        private ?string $type
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            customField: self::toInt($data['customField'] ?? null),
            protocolColumnName: self::toString($data['protocolColumnName'] ?? null),
            sortOrder: self::toInt($data['sortOrder'] ?? null),
            sortType: self::toString($data['sortType'] ?? null),
            type: self::toString($data['type'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customField' => $this->customField,
            'protocolColumnName' => $this->protocolColumnName,
            'sortOrder' => $this->sortOrder,
            'sortType' => $this->sortType,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomField(): ?int { return $this->customField; }
    public function getProtocolColumnName(): ?string { return $this->protocolColumnName; }
    public function getSortOrder(): ?int { return $this->sortOrder; }
    public function getSortType(): ?string { return $this->sortType; }
    public function getType(): ?string { return $this->type; }
}