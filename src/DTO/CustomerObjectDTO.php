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
        private readonly ?int $customer,
        private readonly ?int $customerLocation,
        private readonly ?int $customerContact,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customer' => $this->customer,
            'customerLocation' => $this->customerLocation,
            'customerContact' => $this->customerContact,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
}