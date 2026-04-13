<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a CustomFieldValue entry within a Docbee record's customFields array.
 */
final class CustomFieldValueDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific customFieldValue */
        private readonly ?int    $id,
        /** name */
        private readonly ?string $name,
        /** indicates if this is customer info data */
        private readonly ?bool   $isCustomerInfoData,
        /** important */
        private readonly ?bool   $important,
        /** selectionCategory identifier */
        private readonly ?int    $selectionCategory,
        /** observerCategory identifier */
        private readonly ?int    $observerCategory,
        /** modified date */
        private readonly ?string $modified,
        /** the custom field value */
        private mixed            $value,
        /** type */
        private ?string          $type,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                 self::toInt($data['id'] ?? null),
            name:               self::toString($data['name'] ?? null),
            isCustomerInfoData: isset($data['isCustomerInfoData']) ? self::toBool($data['isCustomerInfoData']) : null,
            important:          isset($data['important']) ? self::toBool($data['important']) : null,
            selectionCategory:  self::toInt($data['selectionCategory'] ?? null),
            observerCategory:   self::toInt($data['observerCategory'] ?? null),
            modified:           self::toString($data['modified'] ?? null),
            value:              $data['value'] ?? null,
            type:               self::toString($data['type'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'value' => $this->value,
            'type'  => $this->type,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int              { return $this->id; }
    public function getName(): ?string         { return $this->name; }
    public function isCustomerInfoData(): ?bool { return $this->isCustomerInfoData; }
    public function isImportant(): ?bool       { return $this->important; }
    public function getSelectionCategory(): ?int { return $this->selectionCategory; }
    public function getObserverCategory(): ?int  { return $this->observerCategory; }
    public function getModified(): ?string     { return $this->modified; }
    public function getValue(): mixed          { return $this->value; }
    public function getType(): ?string         { return $this->type; }
}
