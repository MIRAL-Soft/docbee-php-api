<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MapView record.
 */
final class MapViewDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific map view */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** type */
        private ?string $type,
        /** is favourite flag */
        private ?bool $isFavorit,
        /** fields configuration */
        private ?array $fields,
        /** filters configuration */
        private ?array $filters,
        /** limit options configuration */
        private ?array $limitOptions
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            type: self::toString($data['type'] ?? null),
            isFavorit: isset($data['isFavorit']) ? self::toBool($data['isFavorit']) : null,
            fields: isset($data['fields']) && is_array($data['fields']) ? $data['fields'] : null,
            filters: isset($data['filters']) && is_array($data['filters']) ? $data['filters'] : null,
            limitOptions: isset($data['limitOptions']) && is_array($data['limitOptions']) ? $data['limitOptions'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'type' => $this->type,
            'isFavorit' => $this->isFavorit,
            'fields' => $this->fields,
            'filters' => $this->filters,
            'limitOptions' => $this->limitOptions,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getType(): ?string { return $this->type; }
    public function isFavorit(): ?bool { return $this->isFavorit; }
    public function getFields(): ?array { return $this->fields; }
    public function getFilters(): ?array { return $this->filters; }
    public function getLimitOptions(): ?array { return $this->limitOptions; }
}
