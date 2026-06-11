<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PlanningTime entry on a task.
 */
final class PlanningTimeDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific planningTime */
        private readonly ?int    $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** estimateType */
        private readonly ?string $estimateType,
        /** endDate */
        private readonly ?string $endDate,
        /** startDate */
        private ?string          $startDate,
        /** estimated time per worker */
        private ?int             $estimate,
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:           self::toInt($data['id'] ?? null),
            modified:     self::toString($data['modified'] ?? null),
            link:         self::toString($data['link'] ?? null),
            estimateType: self::toString($data['estimateType'] ?? null),
            endDate:      self::toString($data['endDate'] ?? null),
            startDate:    self::toString($data['startDate'] ?? null),
            estimate:     self::toInt($data['estimate'] ?? null),
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'startDate' => $this->startDate,
            'estimate'  => $this->estimate,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int             { return $this->id; }
    public function getModified(): ?string    { return $this->modified; }
    public function getLink(): ?string        { return $this->link; }
    public function getEstimateType(): ?string { return $this->estimateType; }
    public function getEndDate(): ?string     { return $this->endDate; }
    public function getStartDate(): ?string   { return $this->startDate; }
    public function getEstimate(): ?int       { return $this->estimate; }
}
