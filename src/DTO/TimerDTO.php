<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Timer record.
 */
final class TimerDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $user,
        private readonly ?string $status,
        private readonly ?int $statusOrder,
        private readonly ?string $runningStartDate,
        private readonly ?int $capturedTime,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            user: self::toInt($data['user'] ?? null),
            status: self::toString($data['status'] ?? null),
            statusOrder: self::toInt($data['statusOrder'] ?? null),
            runningStartDate: self::toString($data['runningStartDate'] ?? null),
            capturedTime: self::toInt($data['capturedTime'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'user' => $this->user,
            'status' => $this->status,
            'statusOrder' => $this->statusOrder,
            'runningStartDate' => $this->runningStartDate,
            'capturedTime' => $this->capturedTime,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?int { return $this->user; }
    public function getStatus(): ?string { return $this->status; }
    public function getStatusOrder(): ?int { return $this->statusOrder; }
    public function getRunningStartDate(): ?string { return $this->runningStartDate; }
    public function getCapturedTime(): ?int { return $this->capturedTime; }
}