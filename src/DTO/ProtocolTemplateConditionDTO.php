<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplateCondition record.
 */
final class ProtocolTemplateConditionDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplateCondition */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** customFieldType */
        private readonly ?string $customFieldType,
        /** type */
        private ?string $type,
        /** comparator */
        private ?string $comparator,
        /** group */
        private ?int $group,
        /** groupEntryMapping */
        private ?int $groupEntryMapping,
        /** customField */
        private ?int $customField,
        /** value */
        private ?string $value
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            customFieldType: self::toString($data['customFieldType'] ?? null),
            type: self::toString($data['type'] ?? null),
            comparator: self::toString($data['comparator'] ?? null),
            group: self::toInt($data['group'] ?? null),
            groupEntryMapping: self::toInt($data['groupEntryMapping'] ?? null),
            customField: self::toInt($data['customField'] ?? null),
            value: self::toString($data['value'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'comparator' => $this->comparator,
            'group' => $this->group,
            'groupEntryMapping' => $this->groupEntryMapping,
            'customField' => $this->customField,
            'value' => $this->value
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomFieldType(): ?string { return $this->customFieldType; }
    public function getType(): ?string { return $this->type; }
    public function getComparator(): ?string { return $this->comparator; }
    public function getGroup(): ?int { return $this->group; }
    public function getGroupEntryMapping(): ?int { return $this->groupEntryMapping; }
    public function getCustomField(): ?int { return $this->customField; }
    public function getValue(): ?string { return $this->value; }
}
