<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PlanningTimeTemplate entry (template variant of PlanningTime).
 *
 * Differs from PlanningTimeDTO: uses startTime/startOffset instead of startDate/endDate.
 */
final class PlanningTimeTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific planningTimeTemplate */
        private readonly ?int    $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** start time of day */
        private ?string          $startTime,
        /** start offset in days */
        private ?int             $startOffset,
        /** estimated time per worker */
        private ?int             $estimate,
        /** estimate type */
        private ?string          $estimateType,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:           self::toInt($data['id'] ?? null),
            modified:     self::toString($data['modified'] ?? null),
            link:         self::toString($data['link'] ?? null),
            startTime:    self::toString($data['startTime'] ?? null),
            startOffset:  self::toInt($data['startOffset'] ?? null),
            estimate:     self::toInt($data['estimate'] ?? null),
            estimateType: self::toString($data['estimateType'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'startTime'    => $this->startTime,
            'startOffset'  => $this->startOffset,
            'estimate'     => $this->estimate,
            'estimateType' => $this->estimateType,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int              { return $this->id; }
    public function getModified(): ?string     { return $this->modified; }
    public function getLink(): ?string         { return $this->link; }
    public function getStartTime(): ?string    { return $this->startTime; }
    public function getStartOffset(): ?int     { return $this->startOffset; }
    public function getEstimate(): ?int        { return $this->estimate; }
    public function getEstimateType(): ?string { return $this->estimateType; }
}
