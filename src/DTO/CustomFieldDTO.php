<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomField record.
 */
final class CustomFieldDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific customField */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** data encrypted */
        private readonly ?bool $dataEncrypted,
        /** erp custom field name */
        private readonly ?string $erpCustomFieldName,
        /** observerCategory identifier */
        private readonly ?int $observerCategory,
        /** parentType */
        private readonly ?string $parentType,
        /** placeholder name */
        private readonly ?string $placeholderName,
        /** selectionCategory identifier */
        private readonly ?int $selectionCategory,
        /** showAtTicketData, Deprecated: Renamed to isCustomerInfoData */
        private readonly ?bool $showAtTicketData,
        /** type */
        private readonly ?string $type,
        /** with advanced permission */
        private ?bool $advancedPermission,
        /** deactivated */
        private ?bool $deactivated,
        private ?array $editPermissionUserProfiles,
        /** is important */
        private ?bool $important,
        /** is customer info data */
        private ?bool $isCustomerInfoData,
        /** name */
        private ?string $name,
        /** is searchable */
        private ?bool $searchable,
        private ?array $showPermissionUserProfiles,
        /** visible for customer */
        private ?bool $visibleForCustomer
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            dataEncrypted: isset($data['dataEncrypted']) ? self::toBool($data['dataEncrypted']) : null,
            erpCustomFieldName: self::toString($data['erpCustomFieldName'] ?? null),
            observerCategory: self::toInt($data['observerCategory'] ?? null),
            parentType: self::toString($data['parentType'] ?? null),
            placeholderName: self::toString($data['placeholderName'] ?? null),
            selectionCategory: self::toInt($data['selectionCategory'] ?? null),
            showAtTicketData: isset($data['showAtTicketData']) ? self::toBool($data['showAtTicketData']) : null,
            type: self::toString($data['type'] ?? null),
            advancedPermission: isset($data['advancedPermission']) ? self::toBool($data['advancedPermission']) : null,
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            editPermissionUserProfiles: isset($data['editPermissionUserProfiles']) && is_array($data['editPermissionUserProfiles']) ? $data['editPermissionUserProfiles'] : null,
            important: isset($data['important']) ? self::toBool($data['important']) : null,
            isCustomerInfoData: isset($data['isCustomerInfoData']) ? self::toBool($data['isCustomerInfoData']) : null,
            name: self::toString($data['name'] ?? null),
            searchable: isset($data['searchable']) ? self::toBool($data['searchable']) : null,
            showPermissionUserProfiles: isset($data['showPermissionUserProfiles']) && is_array($data['showPermissionUserProfiles']) ? $data['showPermissionUserProfiles'] : null,
            visibleForCustomer: isset($data['visibleForCustomer']) ? self::toBool($data['visibleForCustomer']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'parentType'                 => $this->parentType,
            'type'                       => $this->type,
            'advancedPermission'         => $this->advancedPermission,
            'deactivated'                => $this->deactivated,
            'editPermissionUserProfiles' => $this->editPermissionUserProfiles,
            'important'                  => $this->important,
            'isCustomerInfoData'         => $this->isCustomerInfoData,
            'name'                       => $this->name,
            'searchable'                 => $this->searchable,
            'showPermissionUserProfiles' => $this->showPermissionUserProfiles,
            'visibleForCustomer'         => $this->visibleForCustomer,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getDataEncrypted(): ?bool { return $this->dataEncrypted; }
    public function getErpCustomFieldName(): ?string { return $this->erpCustomFieldName; }
    public function getObserverCategory(): ?int { return $this->observerCategory; }
    public function getParentType(): ?string { return $this->parentType; }
    public function getPlaceholderName(): ?string { return $this->placeholderName; }
    public function getSelectionCategory(): ?int { return $this->selectionCategory; }
    public function getShowAtTicketData(): ?bool { return $this->showAtTicketData; }
    public function getType(): ?string { return $this->type; }
    public function getAdvancedPermission(): ?bool { return $this->advancedPermission; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function getEditPermissionUserProfiles(): ?array { return $this->editPermissionUserProfiles; }
    public function getImportant(): ?bool { return $this->important; }
    public function getIsCustomerInfoData(): ?bool { return $this->isCustomerInfoData; }
    public function getName(): ?string { return $this->name; }
    public function getSearchable(): ?bool { return $this->searchable; }
    public function getShowPermissionUserProfiles(): ?array { return $this->showPermissionUserProfiles; }
    public function getVisibleForCustomer(): ?bool { return $this->visibleForCustomer; }
}