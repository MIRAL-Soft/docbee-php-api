<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Department record.
 */
final class DepartmentDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific department */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** calendarRegion */
        private ?string $calendarRegion,
        /** name */
        private ?string $name
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            calendarRegion: self::toString($data['calendarRegion'] ?? null),
            name: self::toString($data['name'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'calendarRegion' => $this->calendarRegion,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCalendarRegion(): ?string { return $this->calendarRegion; }
    public function getName(): ?string { return $this->name; }
}