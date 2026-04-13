<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee WorkLog entry on a task.
 */
final class WorkLogDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific workLog */
        private readonly ?int    $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** factor */
        private readonly ?float  $factor,
        /** agreement identifier */
        private readonly ?int    $agreement,
        /** invoice time */
        private readonly ?int    $invoiceTime,
        /** invoice time with agreement */
        private readonly ?int    $invoiceTimeWithAgreement,
        /** revised time */
        private readonly ?int    $revisedTime,
        /** contingent time */
        private readonly ?int    $contingentTime,
        /** price */
        private readonly ?float  $price,
        /** internal price */
        private readonly ?float  $internalPrice,
        /** revised price */
        private readonly ?float  $revisedPrice,
        /** contingent price */
        private readonly ?float  $contingentPrice,
        /** invoice price */
        private readonly ?float  $invoicePrice,
        /** invoice price with agreement */
        private readonly ?float  $invoicePriceWithAgreement,
        /** erp amount */
        private readonly ?float  $erpAmount,
        /** started */
        private ?string          $started,
        /** time */
        private ?int             $time,
        /** user identifier */
        private ?int             $worker,
        /** comment */
        private ?string          $comment,
        /** remainingEstimate of the task */
        private ?int             $remainingEstimate,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                       self::toInt($data['id'] ?? null),
            created:                  self::toString($data['created'] ?? null),
            modified:                 self::toString($data['modified'] ?? null),
            link:                     self::toString($data['link'] ?? null),
            factor:                   isset($data['factor']) ? (float) $data['factor'] : null,
            agreement:                self::toInt($data['agreement'] ?? null),
            invoiceTime:              self::toInt($data['invoiceTime'] ?? null),
            invoiceTimeWithAgreement: self::toInt($data['invoiceTimeWithAgreement'] ?? null),
            revisedTime:              self::toInt($data['revisedTime'] ?? null),
            contingentTime:           self::toInt($data['contingentTime'] ?? null),
            price:                    isset($data['price']) ? (float) $data['price'] : null,
            internalPrice:            isset($data['internalPrice']) ? (float) $data['internalPrice'] : null,
            revisedPrice:             isset($data['revisedPrice']) ? (float) $data['revisedPrice'] : null,
            contingentPrice:          isset($data['contingentPrice']) ? (float) $data['contingentPrice'] : null,
            invoicePrice:             isset($data['invoicePrice']) ? (float) $data['invoicePrice'] : null,
            invoicePriceWithAgreement: isset($data['invoicePriceWithAgreement']) ? (float) $data['invoicePriceWithAgreement'] : null,
            erpAmount:                isset($data['erpAmount']) ? (float) $data['erpAmount'] : null,
            started:                  self::toString($data['started'] ?? null),
            time:                     self::toInt($data['time'] ?? null),
            worker:                   self::toInt($data['worker'] ?? null),
            comment:                  self::toString($data['comment'] ?? null),
            remainingEstimate:        self::toInt($data['remainingEstimate'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'started'          => $this->started,
            'time'             => $this->time,
            'worker'           => $this->worker,
            'comment'          => $this->comment,
            'remainingEstimate' => $this->remainingEstimate,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int                          { return $this->id; }
    public function getCreated(): ?string                  { return $this->created; }
    public function getModified(): ?string                 { return $this->modified; }
    public function getLink(): ?string                     { return $this->link; }
    public function getFactor(): ?float                    { return $this->factor; }
    public function getAgreement(): ?int                   { return $this->agreement; }
    public function getInvoiceTime(): ?int                 { return $this->invoiceTime; }
    public function getInvoiceTimeWithAgreement(): ?int    { return $this->invoiceTimeWithAgreement; }
    public function getRevisedTime(): ?int                 { return $this->revisedTime; }
    public function getContingentTime(): ?int              { return $this->contingentTime; }
    public function getPrice(): ?float                     { return $this->price; }
    public function getInternalPrice(): ?float             { return $this->internalPrice; }
    public function getRevisedPrice(): ?float              { return $this->revisedPrice; }
    public function getContingentPrice(): ?float           { return $this->contingentPrice; }
    public function getInvoicePrice(): ?float              { return $this->invoicePrice; }
    public function getInvoicePriceWithAgreement(): ?float { return $this->invoicePriceWithAgreement; }
    public function getErpAmount(): ?float                 { return $this->erpAmount; }
    public function getStarted(): ?string                  { return $this->started; }
    public function getTime(): ?int                        { return $this->time; }
    public function getWorker(): ?int                      { return $this->worker; }
    public function getComment(): ?string                  { return $this->comment; }
    public function getRemainingEstimate(): ?int           { return $this->remainingEstimate; }
}
