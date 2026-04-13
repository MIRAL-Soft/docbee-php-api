<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Invoice record.
 */
final class InvoiceDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific invoice */
        private readonly ?int $id,
        /** agreementInvoice identifier */
        private readonly ?int $agreementInvoice,
        /** docBeeDocument identifier */
        private readonly ?int $docBeeDocument,
        /** docBeeDocument status */
        private readonly ?string $status,
        /** billable */
        private ?bool $billable,
        /** invoiceNumber */
        private ?string $invoiceNumber
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            agreementInvoice: self::toInt($data['agreementInvoice'] ?? null),
            docBeeDocument: self::toInt($data['docBeeDocument'] ?? null),
            status: self::toString($data['status'] ?? null),
            billable: isset($data['billable']) ? self::toBool($data['billable']) : null,
            invoiceNumber: self::toString($data['invoiceNumber'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'billable' => $this->billable,
            'invoiceNumber' => $this->invoiceNumber
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getAgreementInvoice(): ?int { return $this->agreementInvoice; }
    public function getDocBeeDocument(): ?int { return $this->docBeeDocument; }
    public function getStatus(): ?string { return $this->status; }
    public function getBillable(): ?bool { return $this->billable; }
    public function getInvoiceNumber(): ?string { return $this->invoiceNumber; }
}