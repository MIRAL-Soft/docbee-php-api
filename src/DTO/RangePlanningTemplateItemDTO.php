<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee RangePlanningTemplateItem record.
 */
final class RangePlanningTemplateItemDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific rangePlanningTemplateItem */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** customer */
        private ?int $customer,
        /** customerLocation */
        private ?int $customerLocation,
        /** customerContact */
        private ?int $customerContact,
        /** personInCharge */
        private ?int $personInCharge,
        /** requestedTime */
        private ?int $requestedTime,
        /**
         * @var array<int|string, mixed>|null list of elements
         */
        private ?array $elements
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            requestedTime: self::toInt($data['requestedTime'] ?? null),
            elements: isset($data['elements']) && is_array($data['elements']) ? $data['elements'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'customer' => $this->customer,
            'customerLocation' => $this->customerLocation,
            'customerContact' => $this->customerContact,
            'personInCharge' => $this->personInCharge,
            'requestedTime' => $this->requestedTime,
            'elements' => $this->elements
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getRequestedTime(): ?int { return $this->requestedTime; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getElements(): ?array { return $this->elements; }
}
