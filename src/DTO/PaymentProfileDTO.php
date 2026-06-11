<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PaymentProfile record.
 */
final class PaymentProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific paymentProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** defaultPrice */
        private ?float $defaultPrice,
        /** isDefault */
        private ?bool $isDefault,
        /** name */
        private ?string $name,
        /** type */
        private ?string $type
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            defaultPrice: self::toFloat($data['defaultPrice'] ?? null),
            isDefault: isset($data['isDefault']) ? self::toBool($data['isDefault']) : null,
            name: self::toString($data['name'] ?? null),
            type: self::toString($data['type'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'defaultPrice' => $this->defaultPrice,
            'isDefault' => $this->isDefault,
            'name' => $this->name,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getDefaultPrice(): ?float { return $this->defaultPrice; }
    public function getIsDefault(): ?bool { return $this->isDefault; }
    public function getName(): ?string { return $this->name; }
    public function getType(): ?string { return $this->type; }
}