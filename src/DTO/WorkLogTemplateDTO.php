<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee WorkLogTemplate entry (template variant of WorkLog).
 *
 * Differs significantly from WorkLogDTO: only worker/time/startNow/startTime;
 * no invoice, agreement, or price fields.
 */
final class WorkLogTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific workLogTemplate */
        private readonly ?int    $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** user identifier */
        private ?int             $worker,
        /** time in minutes */
        private ?int             $time,
        /** start now flag */
        private ?bool            $startNow,
        /** start time */
        private ?string          $startTime,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:        self::toInt($data['id'] ?? null),
            created:   self::toString($data['created'] ?? null),
            modified:  self::toString($data['modified'] ?? null),
            link:      self::toString($data['link'] ?? null),
            worker:    self::toInt($data['worker'] ?? null),
            time:      self::toInt($data['time'] ?? null),
            startNow:  isset($data['startNow']) ? self::toBool($data['startNow']) : null,
            startTime: self::toString($data['startTime'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'worker'    => $this->worker,
            'time'      => $this->time,
            'startNow'  => $this->startNow,
            'startTime' => self::toInt($this->startTime), // spec: integer (ms of day) — string was rejected/coerced server-side
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int           { return $this->id; }
    public function getCreated(): ?string   { return $this->created; }
    public function getModified(): ?string  { return $this->modified; }
    public function getLink(): ?string      { return $this->link; }
    public function getWorker(): ?int       { return $this->worker; }
    public function getTime(): ?int         { return $this->time; }
    public function isStartNow(): ?bool     { return $this->startNow; }
    public function getStartTime(): ?string { return $this->startTime; }
}
