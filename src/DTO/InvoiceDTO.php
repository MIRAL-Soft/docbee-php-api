<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Invoice record.
 */
final class InvoiceDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $docBeeDocument,
        private readonly ?int $agreementInvoice,
        private readonly ?string $status,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            docBeeDocument: self::toInt($data['docBeeDocument'] ?? null),
            agreementInvoice: self::toInt($data['agreementInvoice'] ?? null),
            status: self::toString($data['status'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'docBeeDocument' => $this->docBeeDocument,
            'agreementInvoice' => $this->agreementInvoice,
            'status' => $this->status,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getDocBeeDocument(): ?int { return $this->docBeeDocument; }
    public function getAgreementInvoice(): ?int { return $this->agreementInvoice; }
    public function getStatus(): ?string { return $this->status; }
}