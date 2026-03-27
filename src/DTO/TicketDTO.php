<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee support ticket.
 */
final class TicketDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $title,
        private readonly ?string $description,
        private readonly ?int    $customer,
        private readonly ?int    $customerContact,
        private readonly ?int    $customerLocation,
        private readonly ?int    $assignedUser,
        private readonly ?int    $status,
        private readonly ?int    $priority,
        private readonly ?int    $serviceType,
        private readonly ?int    $requestType,
        private readonly ?string $orderId,
        private readonly ?string $dueDate,
        private readonly ?string $closedAt,
        private readonly ?string $createdAt,
        private readonly ?string $changedAt,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:               self::toInt($data['id'] ?? null),
            title:            self::toString($data['title'] ?? null),
            description:      self::toString($data['description'] ?? null),
            customer:         self::toInt($data['customer'] ?? null),
            customerContact:  self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            assignedUser:     self::toInt($data['assignedUser'] ?? null),
            status:           self::toInt($data['status'] ?? null),
            priority:         self::toInt($data['priority'] ?? null),
            serviceType:      self::toInt($data['serviceType'] ?? null),
            requestType:      self::toInt($data['requestType'] ?? null),
            orderId:          self::toString($data['orderId'] ?? null),
            dueDate:          self::toString($data['dueDate'] ?? null),
            closedAt:         self::toString($data['closedAt'] ?? null),
            createdAt:        self::toString($data['createdAt'] ?? null),
            changedAt:        self::toString($data['changedAt'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'title'            => $this->title,
            'description'      => $this->description,
            'customer'         => $this->customer,
            'customerContact'  => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'assignedUser'     => $this->assignedUser,
            'status'           => $this->status,
            'priority'         => $this->priority,
            'serviceType'      => $this->serviceType,
            'requestType'      => $this->requestType,
            'orderId'          => $this->orderId,
            'dueDate'          => $this->dueDate,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int               { return $this->id; }
    public function getTitle(): ?string         { return $this->title; }
    public function getDescription(): ?string   { return $this->description; }
    public function getCustomer(): ?int         { return $this->customer; }
    public function getCustomerContact(): ?int  { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getAssignedUser(): ?int     { return $this->assignedUser; }
    public function getStatus(): ?int           { return $this->status; }
    public function getPriority(): ?int         { return $this->priority; }
    public function getServiceType(): ?int      { return $this->serviceType; }
    public function getRequestType(): ?int      { return $this->requestType; }
    public function getOrderId(): ?string       { return $this->orderId; }
    public function getDueDate(): ?string       { return $this->dueDate; }
    public function getClosedAt(): ?string      { return $this->closedAt; }
    public function getCreatedAt(): ?string     { return $this->createdAt; }
    public function getChangedAt(): ?string     { return $this->changedAt; }
}
