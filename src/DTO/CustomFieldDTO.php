<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomField record.
 */
final class CustomFieldDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $dataEncrypted,
        private readonly ?string $placeholderName,
        private readonly ?string $erpCustomFieldName,
        private readonly ?string $type,
        private readonly ?string $parentType,
        private readonly ?int $selectionCategory,
        private readonly ?int $observerCategory,
        private readonly bool $showAtTicketData,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            dataEncrypted: self::toBool($data['dataEncrypted'] ?? false),
            placeholderName: self::toString($data['placeholderName'] ?? null),
            erpCustomFieldName: self::toString($data['erpCustomFieldName'] ?? null),
            type: self::toString($data['type'] ?? null),
            parentType: self::toString($data['parentType'] ?? null),
            selectionCategory: self::toInt($data['selectionCategory'] ?? null),
            observerCategory: self::toInt($data['observerCategory'] ?? null),
            showAtTicketData: self::toBool($data['showAtTicketData'] ?? false),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'dataEncrypted' => $this->dataEncrypted,
            'placeholderName' => $this->placeholderName,
            'erpCustomFieldName' => $this->erpCustomFieldName,
            'type' => $this->type,
            'parentType' => $this->parentType,
            'selectionCategory' => $this->selectionCategory,
            'observerCategory' => $this->observerCategory,
            'showAtTicketData' => $this->showAtTicketData,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isDataEncrypted(): bool { return $this->dataEncrypted; }
    public function getPlaceholderName(): ?string { return $this->placeholderName; }
    public function getErpCustomFieldName(): ?string { return $this->erpCustomFieldName; }
    public function getType(): ?string { return $this->type; }
    public function getParentType(): ?string { return $this->parentType; }
    public function getSelectionCategory(): ?int { return $this->selectionCategory; }
    public function getObserverCategory(): ?int { return $this->observerCategory; }
    public function isShowAtTicketData(): bool { return $this->showAtTicketData; }
}