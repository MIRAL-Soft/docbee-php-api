<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplate record.
 */
final class ProtocolTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $released,
        private readonly ?string $releasedDate,
        private readonly ?int $revision,
        private readonly ?int $component,
        private readonly array $groups,
        private readonly array $groupContainers,
        private readonly array $actions,
        private readonly array $groupStyles,
        private readonly array $entryStyles,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            released: self::toBool($data['released'] ?? false),
            releasedDate: self::toString($data['releasedDate'] ?? null),
            revision: self::toInt($data['revision'] ?? null),
            component: self::toInt($data['component'] ?? null),
            groups: $data['groups'] ?? [],
            groupContainers: $data['groupContainers'] ?? [],
            actions: $data['actions'] ?? [],
            groupStyles: $data['groupStyles'] ?? [],
            entryStyles: $data['entryStyles'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'released' => $this->released,
            'releasedDate' => $this->releasedDate,
            'revision' => $this->revision,
            'component' => $this->component,
            'groups' => $this->groups,
            'groupContainers' => $this->groupContainers,
            'actions' => $this->actions,
            'groupStyles' => $this->groupStyles,
            'entryStyles' => $this->entryStyles,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isReleased(): bool { return $this->released; }
    public function getReleasedDate(): ?string { return $this->releasedDate; }
    public function getRevision(): ?int { return $this->revision; }
    public function getComponent(): ?int { return $this->component; }
    public function getGroups(): array { return $this->groups; }
    public function getGroupContainers(): array { return $this->groupContainers; }
    public function getActions(): array { return $this->actions; }
    public function getGroupStyles(): array { return $this->groupStyles; }
    public function getEntryStyles(): array { return $this->entryStyles; }
}