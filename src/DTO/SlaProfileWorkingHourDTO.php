<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SlaProfileWorkingHour record.
 */
final class SlaProfileWorkingHourDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private ?string $dayOfWeek,
        private ?int $from,
        private ?int $till
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            dayOfWeek: self::toString($data['dayOfWeek'] ?? null),
            from: self::toInt($data['from'] ?? null),
            till: self::toInt($data['till'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'dayOfWeek' => $this->dayOfWeek,
            'from' => $this->from,
            'till' => $this->till
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getDayOfWeek(): ?string { return $this->dayOfWeek; }
    public function getFrom(): ?int { return $this->from; }
    public function getTill(): ?int { return $this->till; }
}