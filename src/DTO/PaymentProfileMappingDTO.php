<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PaymentProfileMapping record.
 */
final class PaymentProfileMappingDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific paymentProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** customer identifier */
        private ?int $customer,
        /** customerProfile identifier */
        private ?int $customerProfile,
        /** paymentProfile identifier */
        private ?int $paymentProfile,
        /** serviceProvider identifier */
        private ?int $serviceProvider,
        /** started */
        private ?string $started
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerProfile: self::toInt($data['customerProfile'] ?? null),
            paymentProfile: self::toInt($data['paymentProfile'] ?? null),
            serviceProvider: self::toInt($data['serviceProvider'] ?? null),
            started: self::toString($data['started'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customer' => $this->customer,
            'customerProfile' => $this->customerProfile,
            'paymentProfile' => $this->paymentProfile,
            'serviceProvider' => $this->serviceProvider,
            'started' => $this->started
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerProfile(): ?int { return $this->customerProfile; }
    public function getPaymentProfile(): ?int { return $this->paymentProfile; }
    public function getServiceProvider(): ?int { return $this->serviceProvider; }
    public function getStarted(): ?string { return $this->started; }
}