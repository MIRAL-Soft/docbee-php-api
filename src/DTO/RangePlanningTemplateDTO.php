<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee RangePlanningTemplate record.
 */
final class RangePlanningTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific rangePlanningTemplate */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** rangePlanningName */
        private ?string $rangePlanningName,
        /** personInCharge */
        private ?int $personInCharge,
        /** mode */
        private ?string $mode,
        /** @var array|null list of items */
        private ?array $items
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            rangePlanningName: self::toString($data['rangePlanningName'] ?? null),
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            mode: self::toString($data['mode'] ?? null),
            items: isset($data['items']) && is_array($data['items']) ? $data['items'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'rangePlanningName' => $this->rangePlanningName,
            'personInCharge' => $this->personInCharge,
            'mode' => $this->mode,
            'items' => $this->items
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getRangePlanningName(): ?string { return $this->rangePlanningName; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getMode(): ?string { return $this->mode; }
    public function getItems(): ?array { return $this->items; }
}
