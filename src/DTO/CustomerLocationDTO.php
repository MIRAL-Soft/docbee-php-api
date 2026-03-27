<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a physical location (branch/site) of a Docbee customer.
 */
final class CustomerLocationDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?int    $customer,
        private readonly ?string $name,
        private readonly ?string $street,
        private readonly ?string $zip,
        private readonly ?string $city,
        private readonly ?string $country,
        private readonly ?string $notes,
        private readonly ?bool   $active,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:        self::toInt($data['id'] ?? null),
            customer:  self::toInt($data['customer'] ?? null),
            name:      self::toString($data['name'] ?? null),
            street:    self::toString($data['street'] ?? null),
            zip:       self::toString($data['zip'] ?? null),
            city:      self::toString($data['city'] ?? null),
            country:   self::toString($data['country'] ?? null),
            notes:     self::toString($data['notes'] ?? null),
            active:    isset($data['active']) ? self::toBool($data['active']) : null,
            createdAt: self::toString($data['createdAt'] ?? null),
            changedAt: self::toString($data['changedAt'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customer' => $this->customer,
            'name'     => $this->name,
            'street'   => $this->street,
            'zip'      => $this->zip,
            'city'     => $this->city,
            'country'  => $this->country,
            'notes'    => $this->notes,
            'active'   => $this->active,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int           { return $this->id; }
    public function getCustomer(): ?int     { return $this->customer; }
    public function getName(): ?string      { return $this->name; }
    public function getStreet(): ?string    { return $this->street; }
    public function getZip(): ?string       { return $this->zip; }
    public function getCity(): ?string      { return $this->city; }
    public function getCountry(): ?string   { return $this->country; }
    public function getNotes(): ?string     { return $this->notes; }
    public function isActive(): ?bool       { return $this->active; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getChangedAt(): ?string { return $this->changedAt; }
}
