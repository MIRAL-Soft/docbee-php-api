<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Contingent record.
 */
final class ContingentDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $customer,
        private readonly ?string $type,
        private readonly ?string $behavior,
        private readonly ?float $moneyStat,
        private readonly ?int $timeStat,
        private readonly array $items,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            type: self::toString($data['type'] ?? null),
            behavior: self::toString($data['behavior'] ?? null),
            moneyStat: self::toFloat($data['moneyStat'] ?? null),
            timeStat: self::toInt($data['timeStat'] ?? null),
            items: $data['items'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customer' => $this->customer,
            'type' => $this->type,
            'behavior' => $this->behavior,
            'moneyStat' => $this->moneyStat,
            'timeStat' => $this->timeStat,
            'items' => $this->items,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getType(): ?string { return $this->type; }
    public function getBehavior(): ?string { return $this->behavior; }
    public function getMoneyStat(): ?float { return $this->moneyStat; }
    public function getTimeStat(): ?int { return $this->timeStat; }
    public function getItems(): array { return $this->items; }
}