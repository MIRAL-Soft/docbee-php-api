<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee WorkingHoliday record.
 */
final class WorkingHolidayDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific working holiday */
        private readonly ?int $id,
        /** start date */
        private ?string $startDate,
        /** end date */
        private ?string $endDate,
        /** holiday name */
        private ?string $name
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            startDate: self::toString($data['startDate'] ?? null),
            endDate: self::toString($data['endDate'] ?? null),
            name: self::toString($data['name'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'name' => $this->name,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getStartDate(): ?string { return $this->startDate; }
    public function getEndDate(): ?string { return $this->endDate; }
    public function getName(): ?string { return $this->name; }
}
