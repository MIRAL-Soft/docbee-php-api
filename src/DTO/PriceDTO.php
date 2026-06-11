<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Price record.
 */
final class PriceDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific price */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** price */
        private ?float $price,
        /** internalPrice */
        private ?float $internalPrice,
        /** priceType */
        private ?string $priceType,
        /** travelType */
        private ?int $travelType,
        /** serviceType */
        private ?int $serviceType
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            price: self::toFloat($data['price'] ?? null),
            internalPrice: self::toFloat($data['internalPrice'] ?? null),
            priceType: self::toString($data['priceType'] ?? null),
            travelType: self::toInt($data['travelType'] ?? null),
            serviceType: self::toInt($data['serviceType'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'price' => $this->price,
            'internalPrice' => $this->internalPrice,
            'priceType' => $this->priceType,
            'travelType' => $this->travelType,
            'serviceType' => $this->serviceType
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getPrice(): ?float { return $this->price; }
    public function getInternalPrice(): ?float { return $this->internalPrice; }
    public function getPriceType(): ?string { return $this->priceType; }
    public function getTravelType(): ?int { return $this->travelType; }
    public function getServiceType(): ?int { return $this->serviceType; }
}
