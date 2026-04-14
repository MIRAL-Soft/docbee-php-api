<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PriceCalculationBehavior record.
 */
final class PriceCalculationBehaviorDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific priceCalculationBehavior */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** factor */
        private ?float $factor,
        /** onHoliday */
        private ?bool $onHoliday,
        /** onWeekDayMo */
        private ?bool $onWeekDayMo,
        /** onWeekDayTu */
        private ?bool $onWeekDayTu,
        /** onWeekDayWe */
        private ?bool $onWeekDayWe,
        /** onWeekDayTh */
        private ?bool $onWeekDayTh,
        /** onWeekDayFr */
        private ?bool $onWeekDayFr,
        /** onWeekDaySa */
        private ?bool $onWeekDaySa,
        /** onWeekDaySu */
        private ?bool $onWeekDaySu,
        /** from */
        private ?int $from,
        /** till */
        private ?int $till,
        /** time */
        private ?int $time,
        /** type */
        private ?string $type,
        /** @var array|null list of serviceTypes */
        private ?array $serviceTypes,
        /** price */
        private ?int $price
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            factor: self::toFloat($data['factor'] ?? null),
            onHoliday: self::toBool($data['onHoliday'] ?? null),
            onWeekDayMo: self::toBool($data['onWeekDayMo'] ?? null),
            onWeekDayTu: self::toBool($data['onWeekDayTu'] ?? null),
            onWeekDayWe: self::toBool($data['onWeekDayWe'] ?? null),
            onWeekDayTh: self::toBool($data['onWeekDayTh'] ?? null),
            onWeekDayFr: self::toBool($data['onWeekDayFr'] ?? null),
            onWeekDaySa: self::toBool($data['onWeekDaySa'] ?? null),
            onWeekDaySu: self::toBool($data['onWeekDaySu'] ?? null),
            from: self::toInt($data['from'] ?? null),
            till: self::toInt($data['till'] ?? null),
            time: self::toInt($data['time'] ?? null),
            type: self::toString($data['type'] ?? null),
            serviceTypes: isset($data['serviceTypes']) && is_array($data['serviceTypes']) ? $data['serviceTypes'] : null,
            price: self::toInt($data['price'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'factor' => $this->factor,
            'onHoliday' => $this->onHoliday,
            'onWeekDayMo' => $this->onWeekDayMo,
            'onWeekDayTu' => $this->onWeekDayTu,
            'onWeekDayWe' => $this->onWeekDayWe,
            'onWeekDayTh' => $this->onWeekDayTh,
            'onWeekDayFr' => $this->onWeekDayFr,
            'onWeekDaySa' => $this->onWeekDaySa,
            'onWeekDaySu' => $this->onWeekDaySu,
            'from' => $this->from,
            'till' => $this->till,
            'time' => $this->time,
            'type' => $this->type,
            'serviceTypes' => $this->serviceTypes,
            'price' => $this->price
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getFactor(): ?float { return $this->factor; }
    public function getOnHoliday(): ?bool { return $this->onHoliday; }
    public function getOnWeekDayMo(): ?bool { return $this->onWeekDayMo; }
    public function getOnWeekDayTu(): ?bool { return $this->onWeekDayTu; }
    public function getOnWeekDayWe(): ?bool { return $this->onWeekDayWe; }
    public function getOnWeekDayTh(): ?bool { return $this->onWeekDayTh; }
    public function getOnWeekDayFr(): ?bool { return $this->onWeekDayFr; }
    public function getOnWeekDaySa(): ?bool { return $this->onWeekDaySa; }
    public function getOnWeekDaySu(): ?bool { return $this->onWeekDaySu; }
    public function getFrom(): ?int { return $this->from; }
    public function getTill(): ?int { return $this->till; }
    public function getTime(): ?int { return $this->time; }
    public function getType(): ?string { return $this->type; }
    public function getServiceTypes(): ?array { return $this->serviceTypes; }
    public function getPrice(): ?int { return $this->price; }
}
