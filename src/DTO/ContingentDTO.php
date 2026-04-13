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
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?string $behavior,
        private readonly ?int $customer,
        private readonly ?array $items,
        private readonly ?float $moneyStat,
        private readonly ?int $timeStat,
        private readonly ?string $type,
        private ?bool $deactivated,
        private ?array $files,
        private ?float $moneyThreshold,
        private ?string $name,
        private ?bool $showOnInvoice,
        private ?int $timeThreshold,
        private ?bool $visibleForCustomer
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            behavior: self::toString($data['behavior'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            items: isset($data['items']) && is_array($data['items']) ? $data['items'] : null,
            moneyStat: self::toFloat($data['moneyStat'] ?? null),
            timeStat: self::toInt($data['timeStat'] ?? null),
            type: self::toString($data['type'] ?? null),
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            files: isset($data['files']) && is_array($data['files']) ? $data['files'] : null,
            moneyThreshold: self::toFloat($data['moneyThreshold'] ?? null),
            name: self::toString($data['name'] ?? null),
            showOnInvoice: isset($data['showOnInvoice']) ? self::toBool($data['showOnInvoice']) : null,
            timeThreshold: self::toInt($data['timeThreshold'] ?? null),
            visibleForCustomer: isset($data['visibleForCustomer']) ? self::toBool($data['visibleForCustomer']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'deactivated' => $this->deactivated,
            'files' => $this->files,
            'moneyThreshold' => $this->moneyThreshold,
            'name' => $this->name,
            'showOnInvoice' => $this->showOnInvoice,
            'timeThreshold' => $this->timeThreshold,
            'visibleForCustomer' => $this->visibleForCustomer
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getBehavior(): ?string { return $this->behavior; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getItems(): ?array { return $this->items; }
    public function getMoneyStat(): ?float { return $this->moneyStat; }
    public function getTimeStat(): ?int { return $this->timeStat; }
    public function getType(): ?string { return $this->type; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function getFiles(): ?array { return $this->files; }
    public function getMoneyThreshold(): ?float { return $this->moneyThreshold; }
    public function getName(): ?string { return $this->name; }
    public function getShowOnInvoice(): ?bool { return $this->showOnInvoice; }
    public function getTimeThreshold(): ?int { return $this->timeThreshold; }
    public function getVisibleForCustomer(): ?bool { return $this->visibleForCustomer; }
}