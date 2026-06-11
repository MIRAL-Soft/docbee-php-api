<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplateAction record.
 */
final class ProtocolTemplateActionDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplateAction */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** group */
        private readonly ?int $group,
        /** groupEntryMapping */
        private readonly ?int $groupEntryMapping,
        /** groupContainer */
        private readonly ?int $groupContainer,
        /** conditionCollection */
        private readonly ?int $conditionCollection,
        /** name */
        private ?string $name,
        /** @var array|null list of conditions */
        private ?array $conditions,
        /** @var array|null list of reactions */
        private ?array $reactions,
        /** @var array|null list of conditionCollections */
        private ?array $conditionCollections
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            group: self::toInt($data['group'] ?? null),
            groupEntryMapping: self::toInt($data['groupEntryMapping'] ?? null),
            groupContainer: self::toInt($data['groupContainer'] ?? null),
            conditionCollection: self::toInt($data['conditionCollection'] ?? null),
            name: self::toString($data['name'] ?? null),
            conditions: isset($data['conditions']) && is_array($data['conditions']) ? $data['conditions'] : null,
            reactions: isset($data['reactions']) && is_array($data['reactions']) ? $data['reactions'] : null,
            conditionCollections: isset($data['conditionCollections']) && is_array($data['conditionCollections']) ? $data['conditionCollections'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'conditions' => $this->conditions,
            'reactions' => $this->reactions,
            'conditionCollections' => $this->conditionCollections
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getGroup(): ?int { return $this->group; }
    public function getGroupEntryMapping(): ?int { return $this->groupEntryMapping; }
    public function getGroupContainer(): ?int { return $this->groupContainer; }
    public function getConditionCollection(): ?int { return $this->conditionCollection; }
    public function getName(): ?string { return $this->name; }
    public function getConditions(): ?array { return $this->conditions; }
    public function getReactions(): ?array { return $this->reactions; }
    public function getConditionCollections(): ?array { return $this->conditionCollections; }
}
