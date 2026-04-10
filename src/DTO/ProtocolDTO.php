<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Protocol record.
 */
final class ProtocolDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $protocolNumber,
        private readonly ?string $finishedDate,
        private readonly ?string $endDate,
        private readonly bool $finished,
        private readonly bool $canceled,
        private readonly ?string $canceledDate,
        private readonly ?int $protocolTemplate,
        private readonly ?string $webLink,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            protocolNumber: self::toString($data['protocolNumber'] ?? null),
            finishedDate: self::toString($data['finishedDate'] ?? null),
            endDate: self::toString($data['endDate'] ?? null),
            finished: self::toBool($data['finished'] ?? false),
            canceled: self::toBool($data['canceled'] ?? false),
            canceledDate: self::toString($data['canceledDate'] ?? null),
            protocolTemplate: self::toInt($data['protocolTemplate'] ?? null),
            webLink: self::toString($data['webLink'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'protocolNumber' => $this->protocolNumber,
            'finishedDate' => $this->finishedDate,
            'endDate' => $this->endDate,
            'finished' => $this->finished,
            'canceled' => $this->canceled,
            'canceledDate' => $this->canceledDate,
            'protocolTemplate' => $this->protocolTemplate,
            'webLink' => $this->webLink,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getProtocolNumber(): ?string { return $this->protocolNumber; }
    public function getFinishedDate(): ?string { return $this->finishedDate; }
    public function getEndDate(): ?string { return $this->endDate; }
    public function isFinished(): bool { return $this->finished; }
    public function isCanceled(): bool { return $this->canceled; }
    public function getCanceledDate(): ?string { return $this->canceledDate; }
    public function getProtocolTemplate(): ?int { return $this->protocolTemplate; }
    public function getWebLink(): ?string { return $this->webLink; }
}