<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketBoardFilter record.
 */
final class TicketBoardFilterDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ticketBoardFilter */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** filterData */
        private ?string $filterData,
        /** selectionCategory identifier */
        private ?int $selectionCategory,
        /** selectionCategory name */
        private ?string $selectionCategoryName,
        /** filter type */
        private ?string $type,
        /** is filter visible */
        private ?bool $visible,
        /** filter has filterData */
        private ?bool $withData
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            filterData: self::toString($data['filterData'] ?? null),
            selectionCategory: self::toInt($data['selectionCategory'] ?? null),
            selectionCategoryName: self::toString($data['selectionCategoryName'] ?? null),
            type: self::toString($data['type'] ?? null),
            visible: isset($data['visible']) ? self::toBool($data['visible']) : null,
            withData: isset($data['withData']) ? self::toBool($data['withData']) : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'filterData' => $this->filterData,
            'selectionCategory' => $this->selectionCategory,
            'selectionCategoryName' => $this->selectionCategoryName,
            'type' => $this->type,
            'visible' => $this->visible,
            'withData' => $this->withData
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getFilterData(): ?string { return $this->filterData; }
    public function getSelectionCategory(): ?int { return $this->selectionCategory; }
    public function getSelectionCategoryName(): ?string { return $this->selectionCategoryName; }
    public function getType(): ?string { return $this->type; }
    public function getVisible(): ?bool { return $this->visible; }
    public function getWithData(): ?bool { return $this->withData; }
}