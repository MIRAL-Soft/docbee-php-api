<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents Docbee CustomerSettings.
 */
final class CustomerSettingsDTO extends AbstractDTO
{
    public function __construct(
        /** customer ID value (server-generated) */
        private readonly ?int $customerIdValue,
        /** customer mode */
        private ?string $customerMode,
        /** customer info data mode */
        private ?string $customerInfoDataMode,
        /** customer location info data mode */
        private ?string $customerLocationInfoDataMode,
        /** customer contact info data mode */
        private ?string $customerContactInfoDataMode,
        /** create dynamic customer location cards service link flag */
        private ?bool $createDynamicCustomerLocationCardsServiceLink,
        /** reverse customer selection flag */
        private ?bool $reverseCustomerSelection,
        /** customer create dynamic link flag */
        private ?bool $customerCreateDynamicLink,
        /** contact create dynamic link flag */
        private ?bool $contactCreateDynamicLink,
        /** location create dynamic link flag */
        private ?bool $locationCreateDynamicLink,
        /** with sync to app flag */
        private ?bool $withSyncToAppFlag,
        /** with customer ID generator flag */
        private ?bool $withCustomerIdGenerator,
        /** customer ID prefix */
        private ?string $customerIdPrefix,
        /** default customer reference */
        private ?int $defaultCustomer
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            customerIdValue: self::toInt($data['customerIdValue'] ?? null),
            customerMode: self::toString($data['customerMode'] ?? null),
            customerInfoDataMode: self::toString($data['customerInfoDataMode'] ?? null),
            customerLocationInfoDataMode: self::toString($data['customerLocationInfoDataMode'] ?? null),
            customerContactInfoDataMode: self::toString($data['customerContactInfoDataMode'] ?? null),
            createDynamicCustomerLocationCardsServiceLink: isset($data['createDynamicCustomerLocationCardsServiceLink']) ? self::toBool($data['createDynamicCustomerLocationCardsServiceLink']) : null,
            reverseCustomerSelection: isset($data['reverseCustomerSelection']) ? self::toBool($data['reverseCustomerSelection']) : null,
            customerCreateDynamicLink: isset($data['customerCreateDynamicLink']) ? self::toBool($data['customerCreateDynamicLink']) : null,
            contactCreateDynamicLink: isset($data['contactCreateDynamicLink']) ? self::toBool($data['contactCreateDynamicLink']) : null,
            locationCreateDynamicLink: isset($data['locationCreateDynamicLink']) ? self::toBool($data['locationCreateDynamicLink']) : null,
            withSyncToAppFlag: isset($data['withSyncToAppFlag']) ? self::toBool($data['withSyncToAppFlag']) : null,
            withCustomerIdGenerator: isset($data['withCustomerIdGenerator']) ? self::toBool($data['withCustomerIdGenerator']) : null,
            customerIdPrefix: self::toString($data['customerIdPrefix'] ?? null),
            defaultCustomer: self::toInt($data['defaultCustomer'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'customerMode' => $this->customerMode,
            'customerInfoDataMode' => $this->customerInfoDataMode,
            'customerLocationInfoDataMode' => $this->customerLocationInfoDataMode,
            'customerContactInfoDataMode' => $this->customerContactInfoDataMode,
            'createDynamicCustomerLocationCardsServiceLink' => $this->createDynamicCustomerLocationCardsServiceLink,
            'reverseCustomerSelection' => $this->reverseCustomerSelection,
            'customerCreateDynamicLink' => $this->customerCreateDynamicLink,
            'contactCreateDynamicLink' => $this->contactCreateDynamicLink,
            'locationCreateDynamicLink' => $this->locationCreateDynamicLink,
            'withSyncToAppFlag' => $this->withSyncToAppFlag,
            'withCustomerIdGenerator' => $this->withCustomerIdGenerator,
            'customerIdPrefix' => $this->customerIdPrefix,
            'defaultCustomer' => $this->defaultCustomer,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return null; }
    public function getCustomerIdValue(): ?int { return $this->customerIdValue; }
    public function getCustomerMode(): ?string { return $this->customerMode; }
    public function getCustomerInfoDataMode(): ?string { return $this->customerInfoDataMode; }
    public function getCustomerLocationInfoDataMode(): ?string { return $this->customerLocationInfoDataMode; }
    public function getCustomerContactInfoDataMode(): ?string { return $this->customerContactInfoDataMode; }
    public function isCreateDynamicCustomerLocationCardsServiceLink(): ?bool { return $this->createDynamicCustomerLocationCardsServiceLink; }
    public function isReverseCustomerSelection(): ?bool { return $this->reverseCustomerSelection; }
    public function isCustomerCreateDynamicLink(): ?bool { return $this->customerCreateDynamicLink; }
    public function isContactCreateDynamicLink(): ?bool { return $this->contactCreateDynamicLink; }
    public function isLocationCreateDynamicLink(): ?bool { return $this->locationCreateDynamicLink; }
    public function isWithSyncToAppFlag(): ?bool { return $this->withSyncToAppFlag; }
    public function isWithCustomerIdGenerator(): ?bool { return $this->withCustomerIdGenerator; }
    public function getCustomerIdPrefix(): ?string { return $this->customerIdPrefix; }
    public function getDefaultCustomer(): ?int { return $this->defaultCustomer; }
}
