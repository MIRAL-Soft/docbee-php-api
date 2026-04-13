<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee RuleEngineAction record.
 */
final class RuleEngineActionDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ruleEngineAction */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** active */
        private ?bool $active,
        /** conditions */
        private ?array $conditions,
        /** name */
        private ?string $description,
        /** name */
        private ?string $name,
        /** reactions */
        private ?array $reactions,
        private mixed $recurrence,
        /** settings */
        private ?array $settings,
        /** trigger */
        private ?string $trigger
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            active: isset($data['active']) ? self::toBool($data['active']) : null,
            conditions: isset($data['conditions']) && is_array($data['conditions']) ? $data['conditions'] : null,
            description: self::toString($data['description'] ?? null),
            name: self::toString($data['name'] ?? null),
            reactions: isset($data['reactions']) && is_array($data['reactions']) ? $data['reactions'] : null,
            recurrence: $data['recurrence'] ?? null,
            settings: isset($data['settings']) && is_array($data['settings']) ? $data['settings'] : null,
            trigger: self::toString($data['trigger'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'active' => $this->active,
            'conditions' => $this->conditions,
            'description' => $this->description,
            'name' => $this->name,
            'reactions' => $this->reactions,
            'recurrence' => $this->recurrence,
            'settings' => $this->settings,
            'trigger' => $this->trigger
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function isActive(): ?bool { return $this->active; }
    public function getConditions(): ?array { return $this->conditions; }
    public function getDescription(): ?string { return $this->description; }
    public function getName(): ?string { return $this->name; }
    public function getReactions(): ?array { return $this->reactions; }
    public function getRecurrence(): mixed { return $this->recurrence; }
    public function getSettings(): ?array { return $this->settings; }
    public function getTrigger(): ?string { return $this->trigger; }
}