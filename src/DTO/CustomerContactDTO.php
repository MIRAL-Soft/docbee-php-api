<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a contact person for a Docbee customer.
 */
final class CustomerContactDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?int    $customer,
        private readonly ?string $name,
        private readonly ?string $firstName,
        private readonly ?string $email,
        private readonly ?string $phone,
        private readonly ?string $mobile,
        private readonly ?string $position,
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
            firstName: self::toString($data['firstName'] ?? null),
            email:     self::toString($data['email'] ?? null),
            phone:     self::toString($data['phone'] ?? null),
            mobile:    self::toString($data['mobile'] ?? null),
            position:  self::toString($data['position'] ?? null),
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
            'customer'  => $this->customer,
            'name'      => $this->name,
            'firstName' => $this->firstName,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'mobile'    => $this->mobile,
            'position'  => $this->position,
            'notes'     => $this->notes,
            'active'    => $this->active,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int           { return $this->id; }
    public function getCustomer(): ?int     { return $this->customer; }
    public function getName(): ?string      { return $this->name; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function getEmail(): ?string     { return $this->email; }
    public function getPhone(): ?string     { return $this->phone; }
    public function getMobile(): ?string    { return $this->mobile; }
    public function getPosition(): ?string  { return $this->position; }
    public function getNotes(): ?string     { return $this->notes; }
    public function isActive(): ?bool       { return $this->active; }
    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function getChangedAt(): ?string { return $this->changedAt; }
}
