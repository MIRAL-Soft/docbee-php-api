<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DueDateColor record.
 */
final class DueDateColorDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific dueDateColor */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** HTML color code in hexadecimal representation */
        private ?string $color,
        /** name */
        private ?string $name,
        /** time in milliseconds */
        private ?int $timeOffset
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            color: self::toString($data['color'] ?? null),
            name: self::toString($data['name'] ?? null),
            timeOffset: self::toInt($data['timeOffset'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'color' => $this->color,
            'name' => $this->name,
            'timeOffset' => $this->timeOffset
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getColor(): ?string { return $this->color; }
    public function getName(): ?string { return $this->name; }
    public function getTimeOffset(): ?int { return $this->timeOffset; }
}