<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplateConditionCollection record.
 */
final class ProtocolTemplateConditionCollectionDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplateConditionCollection */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** superordinate */
        private readonly ?int $superordinate,
        /** treeLevel */
        private readonly ?int $treeLevel,
        /** @var array|null list of conditions */
        private readonly ?array $conditions,
        /** type */
        private ?string $type
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            superordinate: self::toInt($data['superordinate'] ?? null),
            treeLevel: self::toInt($data['treeLevel'] ?? null),
            conditions: isset($data['conditions']) && is_array($data['conditions']) ? $data['conditions'] : null,
            type: self::toString($data['type'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getSuperordinate(): ?int { return $this->superordinate; }
    public function getTreeLevel(): ?int { return $this->treeLevel; }
    public function getConditions(): ?array { return $this->conditions; }
    public function getType(): ?string { return $this->type; }
}
