<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee WorkingDays configuration.
 */
final class WorkingDaysDTO extends AbstractDTO
{
    public function __construct(
        /** calendar region */
        private ?string $calendarRegion,
        /** list of working holidays */
        private ?array $workingHolidays,
        /** list of working hours */
        private ?array $workingHours
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            calendarRegion: self::toString($data['calendarRegion'] ?? null),
            workingHolidays: isset($data['workingHolidays']) && is_array($data['workingHolidays']) ? $data['workingHolidays'] : null,
            workingHours: isset($data['workingHours']) && is_array($data['workingHours']) ? $data['workingHours'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        // UpdateWorkingDays accepts workingHolidays/workingHours — both were parsed
        // by fromArray() but lost on the way back (round-trip data loss).
        return array_filter([
            'calendarRegion'  => $this->calendarRegion,
            'workingHolidays' => $this->workingHolidays,
            'workingHours'    => $this->workingHours,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return null; }
    public function getCalendarRegion(): ?string { return $this->calendarRegion; }
    public function getWorkingHolidays(): ?array { return $this->workingHolidays; }
    public function getWorkingHours(): ?array { return $this->workingHours; }
}
