<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AgreementInvoice record.
 */
final class AgreementInvoiceDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific component */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** agreement identifier */
        private readonly ?int $agreement,
        /** moneyThreshold */
        private ?float $amount,
        /** startDate */
        private ?string $fromDate,
        /** invoiceNumber */
        private ?string $invoiceNumber,
        /** startDate */
        private ?string $tillDate
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            agreement: self::toInt($data['agreement'] ?? null),
            amount: self::toFloat($data['amount'] ?? null),
            fromDate: self::toString($data['fromDate'] ?? null),
            invoiceNumber: self::toString($data['invoiceNumber'] ?? null),
            tillDate: self::toString($data['tillDate'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'fromDate' => $this->fromDate,
            'invoiceNumber' => $this->invoiceNumber,
            'tillDate' => $this->tillDate
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getAgreement(): ?int { return $this->agreement; }
    public function getAmount(): ?float { return $this->amount; }
    public function getFromDate(): ?string { return $this->fromDate; }
    public function getInvoiceNumber(): ?string { return $this->invoiceNumber; }
    public function getTillDate(): ?string { return $this->tillDate; }
}