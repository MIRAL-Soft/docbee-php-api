<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TravelLogTemplate entry (template variant of TravelLog).
 *
 * This schema differs from TravelLogDTO: it has startNow/startTime instead of
 * invoice/price fields.
 */
final class TravelLogTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific travelLogTemplate */
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
        /** travelType identifier */
        private ?int             $travelType,
        /** distance */
        private ?float           $distance,
        /** comment */
        private ?string          $comment,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:         self::toInt($data['id'] ?? null),
            created:    self::toString($data['created'] ?? null),
            modified:   self::toString($data['modified'] ?? null),
            link:       self::toString($data['link'] ?? null),
            worker:     self::toInt($data['worker'] ?? null),
            time:       self::toInt($data['time'] ?? null),
            startNow:   isset($data['startNow']) ? self::toBool($data['startNow']) : null,
            startTime:  self::toString($data['startTime'] ?? null),
            travelType: self::toInt($data['travelType'] ?? null),
            distance:   isset($data['distance']) ? (float) $data['distance'] : null,
            comment:    self::toString($data['comment'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'worker'     => $this->worker,
            'time'       => $this->time,
            'startNow'   => $this->startNow,
            'startTime'  => $this->startTime,
            'travelType' => $this->travelType,
            'distance'   => $this->distance,
            'comment'    => $this->comment,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int          { return $this->id; }
    public function getCreated(): ?string  { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string     { return $this->link; }
    public function getWorker(): ?int      { return $this->worker; }
    public function getTime(): ?int        { return $this->time; }
    public function isStartNow(): ?bool    { return $this->startNow; }
    public function getStartTime(): ?string { return $this->startTime; }
    public function getTravelType(): ?int  { return $this->travelType; }
    public function getDistance(): ?float  { return $this->distance; }
    public function getComment(): ?string  { return $this->comment; }
}
