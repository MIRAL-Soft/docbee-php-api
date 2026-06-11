<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Customer record.
 */
final class CustomerDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific customer */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** Unique identifier representing the default customerLocation */
        private readonly ?int $defaultCustomerLocation,
        /** companyData identifier */
        private ?int $companyData,
        /** @var CustomFieldValueDTO[]|null list of customFieldValues */
        private ?array $customFields,
        /** Unique customer id for a customer. Mostly defined by ERP */
        private ?string $customerId,
        /** customerStatus identifier */
        private ?int $customerStatus,
        /** Additional information about the customer */
        private ?string $info,
        /** If these customer is an inhouse customer */
        private ?bool $inhouse,
        /** Display name of customer */
        private ?string $name,
        /** Customer short name */
        private ?string $shortName,
        /** If this contact is synced to app. The customer setting withSyncToAppFlag needs to be set to use this property. Otherwise all contacts are synced. */
        private ?bool $syncToApp,
        /** Display warning message of customer. E.g if customer has terminated the contract */
        private ?string $warning,
        /** Wildcard email address. For Example "docbee.com" to automatically assign tickets to customer */
        private ?string $wildcardAddress
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            defaultCustomerLocation: self::toInt($data['defaultCustomerLocation'] ?? null),
            companyData: self::toInt($data['companyData'] ?? null),
            customFields: self::toDtoList($data['customFields'] ?? null, CustomFieldValueDTO::class),
            customerId: self::toString($data['customerId'] ?? null),
            customerStatus: self::toInt($data['customerStatus'] ?? null),
            info: self::toString($data['info'] ?? null),
            inhouse: isset($data['inhouse']) ? self::toBool($data['inhouse']) : null,
            name: self::toString($data['name'] ?? null),
            shortName: self::toString($data['shortName'] ?? null),
            syncToApp: isset($data['syncToApp']) ? self::toBool($data['syncToApp']) : null,
            warning: self::toString($data['warning'] ?? null),
            wildcardAddress: self::toString($data['wildcardAddress'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'companyData' => $this->companyData,
            'customFields' => $this->customFields,
            'customerId' => $this->customerId,
            'customerStatus' => $this->customerStatus,
            'info' => $this->info,
            'inhouse' => $this->inhouse,
            'name' => $this->name,
            'shortName' => $this->shortName,
            'syncToApp' => $this->syncToApp,
            'warning' => $this->warning,
            'wildcardAddress' => $this->wildcardAddress
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getDefaultCustomerLocation(): ?int { return $this->defaultCustomerLocation; }
    public function getCompanyData(): ?int { return $this->companyData; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getCustomerId(): ?string { return $this->customerId; }
    public function getCustomerStatus(): ?int { return $this->customerStatus; }
    public function getInfo(): ?string { return $this->info; }
    public function isInhouse(): ?bool { return $this->inhouse; }
    public function getName(): ?string { return $this->name; }
    public function getShortName(): ?string { return $this->shortName; }
    public function isSyncToApp(): ?bool { return $this->syncToApp; }
    public function getWarning(): ?string { return $this->warning; }
    public function getWildcardAddress(): ?string { return $this->wildcardAddress; }
}