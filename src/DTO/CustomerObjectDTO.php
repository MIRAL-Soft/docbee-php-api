<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomerObject record.
 */
final class CustomerObjectDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?int $customer,
        private readonly ?int $customerContact,
        private readonly ?int $customerLocation,
        private ?string $acquisitionDate,
        private ?array $customFields,
        private ?string $details,
        private ?string $extendedName,
        private ?string $itChassisType,
        private ?string $itExternalAddress,
        private ?string $itIpAddress,
        private ?string $itMac1Address,
        private ?string $itMac2Address,
        private ?string $itMac3Address,
        private ?string $itOs,
        private ?int $itProcessorCount,
        private ?string $itRole,
        private ?string $itServicePack,
        private ?string $itTotalMemory,
        private ?string $number,
        private ?int $object,
        private ?int $parentCustomerObject,
        private ?string $scanCode
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            acquisitionDate: self::toString($data['acquisitionDate'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            details: self::toString($data['details'] ?? null),
            extendedName: self::toString($data['extendedName'] ?? null),
            itChassisType: self::toString($data['itChassisType'] ?? null),
            itExternalAddress: self::toString($data['itExternalAddress'] ?? null),
            itIpAddress: self::toString($data['itIpAddress'] ?? null),
            itMac1Address: self::toString($data['itMac1Address'] ?? null),
            itMac2Address: self::toString($data['itMac2Address'] ?? null),
            itMac3Address: self::toString($data['itMac3Address'] ?? null),
            itOs: self::toString($data['itOs'] ?? null),
            itProcessorCount: self::toInt($data['itProcessorCount'] ?? null),
            itRole: self::toString($data['itRole'] ?? null),
            itServicePack: self::toString($data['itServicePack'] ?? null),
            itTotalMemory: self::toString($data['itTotalMemory'] ?? null),
            number: self::toString($data['number'] ?? null),
            object: self::toInt($data['object'] ?? null),
            parentCustomerObject: self::toInt($data['parentCustomerObject'] ?? null),
            scanCode: self::toString($data['scanCode'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'acquisitionDate' => $this->acquisitionDate,
            'customFields' => $this->customFields,
            'details' => $this->details,
            'extendedName' => $this->extendedName,
            'itChassisType' => $this->itChassisType,
            'itExternalAddress' => $this->itExternalAddress,
            'itIpAddress' => $this->itIpAddress,
            'itMac1Address' => $this->itMac1Address,
            'itMac2Address' => $this->itMac2Address,
            'itMac3Address' => $this->itMac3Address,
            'itOs' => $this->itOs,
            'itProcessorCount' => $this->itProcessorCount,
            'itRole' => $this->itRole,
            'itServicePack' => $this->itServicePack,
            'itTotalMemory' => $this->itTotalMemory,
            'number' => $this->number,
            'object' => $this->object,
            'parentCustomerObject' => $this->parentCustomerObject,
            'scanCode' => $this->scanCode
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getAcquisitionDate(): ?string { return $this->acquisitionDate; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getDetails(): ?string { return $this->details; }
    public function getExtendedName(): ?string { return $this->extendedName; }
    public function getItChassisType(): ?string { return $this->itChassisType; }
    public function getItExternalAddress(): ?string { return $this->itExternalAddress; }
    public function getItIpAddress(): ?string { return $this->itIpAddress; }
    public function getItMac1Address(): ?string { return $this->itMac1Address; }
    public function getItMac2Address(): ?string { return $this->itMac2Address; }
    public function getItMac3Address(): ?string { return $this->itMac3Address; }
    public function getItOs(): ?string { return $this->itOs; }
    public function getItProcessorCount(): ?int { return $this->itProcessorCount; }
    public function getItRole(): ?string { return $this->itRole; }
    public function getItServicePack(): ?string { return $this->itServicePack; }
    public function getItTotalMemory(): ?string { return $this->itTotalMemory; }
    public function getNumber(): ?string { return $this->number; }
    public function getObject(): ?int { return $this->object; }
    public function getParentCustomerObject(): ?int { return $this->parentCustomerObject; }
    public function getScanCode(): ?string { return $this->scanCode; }
}