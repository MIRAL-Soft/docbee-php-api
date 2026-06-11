<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TaskTemplateProfile record.
 */
final class TaskTemplateProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific taskTemplateProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** @var array|null list of taskTemplates */
        private ?array $taskTemplates
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            taskTemplates: isset($data['taskTemplates']) && is_array($data['taskTemplates']) ? $data['taskTemplates'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'taskTemplates' => $this->taskTemplates
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getTaskTemplates(): ?array { return $this->taskTemplates; }
}
