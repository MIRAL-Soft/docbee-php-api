<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Agreement record.
 */
final class AgreementDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $finished,
        private readonly ?string $type,
        private readonly array $periods,
        private readonly array $components,
        private readonly array $invoices,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            finished: self::toBool($data['finished'] ?? false),
            type: self::toString($data['type'] ?? null),
            periods: $data['periods'] ?? [],
            components: $data['components'] ?? [],
            invoices: $data['invoices'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'finished' => $this->finished,
            'type' => $this->type,
            'periods' => $this->periods,
            'components' => $this->components,
            'invoices' => $this->invoices,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isFinished(): bool { return $this->finished; }
    public function getType(): ?string { return $this->type; }
    public function getPeriods(): array { return $this->periods; }
    public function getComponents(): array { return $this->components; }
    public function getInvoices(): array { return $this->invoices; }
}