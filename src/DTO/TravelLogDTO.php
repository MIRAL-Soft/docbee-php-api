<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TravelLog entry on a document.
 */
final class TravelLogDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific travelLog */
        private readonly ?int    $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** invoice time */
        private readonly ?int    $invoiceTime,
        /** revised time */
        private readonly ?int    $revisedTime,
        /** price */
        private readonly ?float  $price,
        /** internal price */
        private readonly ?float  $internalPrice,
        /** revised price */
        private readonly ?float  $revisedPrice,
        /** invoice price */
        private readonly ?float  $invoicePrice,
        /** erp amount */
        private readonly ?float  $erpAmount,
        /** distance */
        private ?float           $distance,
        /** travelType identifier */
        private ?int             $travelType,
        /** factor */
        private ?float           $factor,
        /** started */
        private ?string          $started,
        /** time */
        private ?int             $time,
        /** user identifier */
        private ?int             $worker,
        /** comment */
        private ?string          $comment,
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:           self::toInt($data['id'] ?? null),
            created:      self::toString($data['created'] ?? null),
            modified:     self::toString($data['modified'] ?? null),
            link:         self::toString($data['link'] ?? null),
            invoiceTime:  self::toInt($data['invoiceTime'] ?? null),
            revisedTime:  self::toInt($data['revisedTime'] ?? null),
            price:        isset($data['price']) ? (float) $data['price'] : null,
            internalPrice: isset($data['internalPrice']) ? (float) $data['internalPrice'] : null,
            revisedPrice: isset($data['revisedPrice']) ? (float) $data['revisedPrice'] : null,
            invoicePrice: isset($data['invoicePrice']) ? (float) $data['invoicePrice'] : null,
            erpAmount:    isset($data['erpAmount']) ? (float) $data['erpAmount'] : null,
            distance:     isset($data['distance']) ? (float) $data['distance'] : null,
            travelType:   self::toInt($data['travelType'] ?? null),
            factor:       isset($data['factor']) ? (float) $data['factor'] : null,
            started:      self::toString($data['started'] ?? null),
            time:         self::toInt($data['time'] ?? null),
            worker:       self::toInt($data['worker'] ?? null),
            comment:      self::toString($data['comment'] ?? null),
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'distance'   => $this->distance,
            'travelType' => $this->travelType,
            'factor'     => $this->factor,
            'started'    => $this->started,
            'time'       => $this->time,
            'worker'     => $this->worker,
            'comment'    => $this->comment,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int             { return $this->id; }
    public function getCreated(): ?string     { return $this->created; }
    public function getModified(): ?string    { return $this->modified; }
    public function getLink(): ?string        { return $this->link; }
    public function getInvoiceTime(): ?int    { return $this->invoiceTime; }
    public function getRevisedTime(): ?int    { return $this->revisedTime; }
    public function getPrice(): ?float        { return $this->price; }
    public function getInternalPrice(): ?float { return $this->internalPrice; }
    public function getRevisedPrice(): ?float { return $this->revisedPrice; }
    public function getInvoicePrice(): ?float { return $this->invoicePrice; }
    public function getErpAmount(): ?float    { return $this->erpAmount; }
    public function getDistance(): ?float     { return $this->distance; }
    public function getTravelType(): ?int     { return $this->travelType; }
    public function getFactor(): ?float       { return $this->factor; }
    public function getStarted(): ?string     { return $this->started; }
    public function getTime(): ?int           { return $this->time; }
    public function getWorker(): ?int         { return $this->worker; }
    public function getComment(): ?string     { return $this->comment; }
}
