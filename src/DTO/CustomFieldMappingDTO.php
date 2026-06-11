<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a CustomFieldMapping entry within a Docbee category's customFields array.
 */
final class CustomFieldMappingDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier */
        private readonly ?int    $id,
        /** name */
        private readonly ?string $name,
        /** indicates if this is customer info data */
        private readonly ?bool   $isCustomerInfoData,
        /** important */
        private readonly ?bool   $important,
        /** modified date */
        private readonly ?string $modified,
        /** type */
        private ?string          $type,
        /** selectionCategory identifier */
        private ?int             $selectionCategory,
        /** observerCategory identifier */
        private ?int             $observerCategory,
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                 self::toInt($data['id'] ?? null),
            name:               self::toString($data['name'] ?? null),
            isCustomerInfoData: isset($data['isCustomerInfoData']) ? self::toBool($data['isCustomerInfoData']) : null,
            important:          isset($data['important']) ? self::toBool($data['important']) : null,
            modified:           self::toString($data['modified'] ?? null),
            type:               self::toString($data['type'] ?? null),
            selectionCategory:  self::toInt($data['selectionCategory'] ?? null),
            observerCategory:   self::toInt($data['observerCategory'] ?? null),
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'type'              => $this->type,
            'selectionCategory' => $this->selectionCategory,
            'observerCategory'  => $this->observerCategory,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int              { return $this->id; }
    public function getName(): ?string         { return $this->name; }
    public function isCustomerInfoData(): ?bool { return $this->isCustomerInfoData; }
    public function isImportant(): ?bool       { return $this->important; }
    public function getModified(): ?string     { return $this->modified; }
    public function getType(): ?string         { return $this->type; }
    public function getSelectionCategory(): ?int { return $this->selectionCategory; }
    public function getObserverCategory(): ?int  { return $this->observerCategory; }
}
