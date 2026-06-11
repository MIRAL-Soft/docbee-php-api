<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TableConfigStorageFilter entry on a board or table config.
 */
final class TableConfigStorageFilterDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific filter */
        private readonly ?int    $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** type */
        private ?string          $type,
        /** visible */
        private ?bool            $visible,
        /** withData */
        private ?bool            $withData,
        /** filterData */
        private ?string          $filterData,
        /** selectionCategory identifier */
        private ?int             $selectionCategory,
        /** selectionCategoryName */
        private ?string          $selectionCategoryName,
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                    self::toInt($data['id'] ?? null),
            modified:              self::toString($data['modified'] ?? null),
            link:                  self::toString($data['link'] ?? null),
            type:                  self::toString($data['type'] ?? null),
            visible:               isset($data['visible']) ? self::toBool($data['visible']) : null,
            withData:              isset($data['withData']) ? self::toBool($data['withData']) : null,
            filterData:            self::toString($data['filterData'] ?? null),
            selectionCategory:     self::toInt($data['selectionCategory'] ?? null),
            selectionCategoryName: self::toString($data['selectionCategoryName'] ?? null),
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'type'                  => $this->type,
            'visible'               => $this->visible,
            'withData'              => $this->withData,
            'filterData'            => $this->filterData,
            'selectionCategory'     => $this->selectionCategory,
            'selectionCategoryName' => $this->selectionCategoryName,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int                      { return $this->id; }
    public function getModified(): ?string             { return $this->modified; }
    public function getLink(): ?string                 { return $this->link; }
    public function getType(): ?string                 { return $this->type; }
    public function isVisible(): ?bool                 { return $this->visible; }
    public function isWithData(): ?bool                { return $this->withData; }
    public function getFilterData(): ?string           { return $this->filterData; }
    public function getSelectionCategory(): ?int       { return $this->selectionCategory; }
    public function getSelectionCategoryName(): ?string { return $this->selectionCategoryName; }
}
