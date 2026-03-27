<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee document (protocol / service report).
 */
final class DocumentDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?int    $customer,
        private readonly ?int    $customerContact,
        private readonly ?int    $customerLocation,
        private readonly ?int    $template,
        private readonly ?string $title,
        private readonly ?string $status,
        private readonly ?string $notes,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    /** @inheritDoc */
    public static function fromArray(array $data): static
    {
        return new self(
            id:               self::toInt($data['id'] ?? null),
            customer:         self::toInt($data['customer'] ?? null),
            customerContact:  self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            template:         self::toInt($data['template'] ?? null),
            title:            self::toString($data['title'] ?? null),
            status:           self::toString($data['status'] ?? null),
            notes:            self::toString($data['notes'] ?? null),
            createdAt:        self::toString($data['createdAt'] ?? null),
            changedAt:        self::toString($data['changedAt'] ?? null),
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return array_filter([
            'customer'         => $this->customer,
            'customerContact'  => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'template'         => $this->template,
            'title'            => $this->title,
            'status'           => $this->status,
            'notes'            => $this->notes,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int               { return $this->id; }
    public function getCustomer(): ?int         { return $this->customer; }
    public function getCustomerContact(): ?int  { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getTemplate(): ?int         { return $this->template; }
    public function getTitle(): ?string         { return $this->title; }
    public function getStatus(): ?string        { return $this->status; }
    public function getNotes(): ?string         { return $this->notes; }
    public function getCreatedAt(): ?string     { return $this->createdAt; }
    public function getChangedAt(): ?string     { return $this->changedAt; }
}
