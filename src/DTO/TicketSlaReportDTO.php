<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketSlaReport entry on a ticket.
 */
final class TicketSlaReportDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier */
        private readonly ?int    $id,
        /** sla identifier */
        private readonly ?int    $sla,
        /** sla running time */
        private readonly ?int    $slaRunning,
        /** finish date */
        private readonly ?string $finishDate,
        /** finish status identifier */
        private readonly ?int    $finishStatusId,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:            self::toInt($data['id'] ?? null),
            sla:           self::toInt($data['sla'] ?? null),
            slaRunning:    self::toInt($data['slaRunning'] ?? null),
            finishDate:    self::toString($data['finishDate'] ?? null),
            finishStatusId: self::toInt($data['finishStatusId'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [];
    }

    public function getId(): ?int             { return $this->id; }
    public function getSla(): ?int            { return $this->sla; }
    public function getSlaRunning(): ?int     { return $this->slaRunning; }
    public function getFinishDate(): ?string  { return $this->finishDate; }
    public function getFinishStatusId(): ?int { return $this->finishStatusId; }
}
