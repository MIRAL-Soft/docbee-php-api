<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee customer record.
 *
 * Maps to the `customer` API endpoint.
 */
final class CustomerDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $name,
        private readonly ?string $customerNumber,
        private readonly ?string $email,
        private readonly ?string $phone,
        private readonly ?string $mobile,
        private readonly ?string $fax,
        private readonly ?string $website,
        private readonly ?string $street,
        private readonly ?string $zip,
        private readonly ?string $city,
        private readonly ?string $country,
        private readonly ?string $notes,
        private readonly ?int    $status,
        private readonly ?bool   $active,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    /** @inheritDoc */
    public static function fromArray(array $data): static
    {
        return new self(
            id:             self::toInt($data['id'] ?? null),
            name:           self::toString($data['name'] ?? null),
            customerNumber: self::toString($data['customerNumber'] ?? null),
            email:          self::toString($data['email'] ?? null),
            phone:          self::toString($data['phone'] ?? null),
            mobile:         self::toString($data['mobile'] ?? null),
            fax:            self::toString($data['fax'] ?? null),
            website:        self::toString($data['website'] ?? null),
            street:         self::toString($data['street'] ?? null),
            zip:            self::toString($data['zip'] ?? null),
            city:           self::toString($data['city'] ?? null),
            country:        self::toString($data['country'] ?? null),
            notes:          self::toString($data['notes'] ?? null),
            status:         self::toInt($data['status'] ?? null),
            active:         isset($data['active']) ? self::toBool($data['active']) : null,
            createdAt:      self::toString($data['createdAt'] ?? null),
            changedAt:      self::toString($data['changedAt'] ?? null),
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return array_filter([
            'name'           => $this->name,
            'customerNumber' => $this->customerNumber,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'mobile'         => $this->mobile,
            'fax'            => $this->fax,
            'website'        => $this->website,
            'street'         => $this->street,
            'zip'            => $this->zip,
            'city'           => $this->city,
            'country'        => $this->country,
            'notes'          => $this->notes,
            'status'         => $this->status,
            'active'         => $this->active,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int            { return $this->id; }
    public function getName(): ?string       { return $this->name; }
    public function getCustomerNumber(): ?string { return $this->customerNumber; }
    public function getEmail(): ?string      { return $this->email; }
    public function getPhone(): ?string      { return $this->phone; }
    public function getMobile(): ?string     { return $this->mobile; }
    public function getFax(): ?string        { return $this->fax; }
    public function getWebsite(): ?string    { return $this->website; }
    public function getStreet(): ?string     { return $this->street; }
    public function getZip(): ?string        { return $this->zip; }
    public function getCity(): ?string       { return $this->city; }
    public function getCountry(): ?string    { return $this->country; }
    public function getNotes(): ?string      { return $this->notes; }
    public function getStatus(): ?int        { return $this->status; }
    public function isActive(): ?bool        { return $this->active; }
    public function getCreatedAt(): ?string  { return $this->createdAt; }
    public function getChangedAt(): ?string  { return $this->changedAt; }
}
