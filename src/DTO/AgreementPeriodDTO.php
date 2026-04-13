<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AgreementPeriod record.
 */
final class AgreementPeriodDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific period */
        private readonly ?int    $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** cycleAmount */
        private ?int             $cycleAmount,
        /** startDate */
        private ?string          $startDate,
        /** endDate */
        private ?string          $endDate,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:          self::toInt($data['id'] ?? null),
            modified:    self::toString($data['modified'] ?? null),
            link:        self::toString($data['link'] ?? null),
            cycleAmount: self::toInt($data['cycleAmount'] ?? null),
            startDate:   self::toString($data['startDate'] ?? null),
            endDate:     self::toString($data['endDate'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'cycleAmount' => $this->cycleAmount,
            'startDate'   => $this->startDate,
            'endDate'     => $this->endDate,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int          { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string     { return $this->link; }
    public function getCycleAmount(): ?int { return $this->cycleAmount; }
    public function getStartDate(): ?string { return $this->startDate; }
    public function getEndDate(): ?string  { return $this->endDate; }
}
