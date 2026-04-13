<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Timer record.
 */
final class TimerDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific invoice */
        private readonly ?int $id,
        /** captured time in milliseconds */
        private ?int $capturedTime,
        /** customer identifier */
        private ?int $customer,
        /** customerLocation identifier */
        private ?int $customerLocation,
        /** customerObject identifier */
        private ?int $customerObject,
        /** name */
        private ?string $name,
        /** running start date */
        private ?string $runningStartDate,
        /** status */
        private ?string $status,
        /** the index of the status */
        private ?int $statusOrder,
        /** task identifier */
        private ?int $task,
        /** timerIdentifier */
        private ?string $timerIdentifier,
        /** user identifier */
        private ?int $user
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            capturedTime: self::toInt($data['capturedTime'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerObject: self::toInt($data['customerObject'] ?? null),
            name: self::toString($data['name'] ?? null),
            runningStartDate: self::toString($data['runningStartDate'] ?? null),
            status: self::toString($data['status'] ?? null),
            statusOrder: self::toInt($data['statusOrder'] ?? null),
            task: self::toInt($data['task'] ?? null),
            timerIdentifier: self::toString($data['timerIdentifier'] ?? null),
            user: self::toInt($data['user'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'capturedTime' => $this->capturedTime,
            'customer' => $this->customer,
            'customerLocation' => $this->customerLocation,
            'customerObject' => $this->customerObject,
            'name' => $this->name,
            'runningStartDate' => $this->runningStartDate,
            'status' => $this->status,
            'statusOrder' => $this->statusOrder,
            'task' => $this->task,
            'timerIdentifier' => $this->timerIdentifier,
            'user' => $this->user
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCapturedTime(): ?int { return $this->capturedTime; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerObject(): ?int { return $this->customerObject; }
    public function getName(): ?string { return $this->name; }
    public function getRunningStartDate(): ?string { return $this->runningStartDate; }
    public function getStatus(): ?string { return $this->status; }
    public function getStatusOrder(): ?int { return $this->statusOrder; }
    public function getTask(): ?int { return $this->task; }
    public function getTimerIdentifier(): ?string { return $this->timerIdentifier; }
    public function getUser(): ?int { return $this->user; }
}