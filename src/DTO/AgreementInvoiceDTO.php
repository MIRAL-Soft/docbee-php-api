<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AgreementInvoice record.
 */
final class AgreementInvoiceDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $fromDate,
        private readonly ?string $tillDate,
        private readonly ?float $amount,
        private readonly ?string $invoiceNumber,
        private readonly ?int $agreement,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            fromDate: self::toString($data['fromDate'] ?? null),
            tillDate: self::toString($data['tillDate'] ?? null),
            amount: self::toFloat($data['amount'] ?? null),
            invoiceNumber: self::toString($data['invoiceNumber'] ?? null),
            agreement: self::toInt($data['agreement'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'fromDate' => $this->fromDate,
            'tillDate' => $this->tillDate,
            'amount' => $this->amount,
            'invoiceNumber' => $this->invoiceNumber,
            'agreement' => $this->agreement,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getFromDate(): ?string { return $this->fromDate; }
    public function getTillDate(): ?string { return $this->tillDate; }
    public function getAmount(): ?float { return $this->amount; }
    public function getInvoiceNumber(): ?string { return $this->invoiceNumber; }
    public function getAgreement(): ?int { return $this->agreement; }
}