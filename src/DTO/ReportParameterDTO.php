<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ReportParameter record.
 */
final class ReportParameterDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific reportParameter */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** typeName */
        private ?string $typeName,
        /** type */
        private ?string $type,
        /**
         * @var array<int|string, mixed>|null ids
         */
        private ?array $ids
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            typeName: self::toString($data['typeName'] ?? null),
            type: self::toString($data['type'] ?? null),
            ids: isset($data['ids']) && is_array($data['ids']) ? $data['ids'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'typeName' => $this->typeName,
            'type' => $this->type,
            'ids' => $this->ids
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getTypeName(): ?string { return $this->typeName; }
    public function getType(): ?string { return $this->type; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getIds(): ?array { return $this->ids; }
}
