<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ReportElement record.
 */
final class ReportElementDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific reportElement */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** @var array|null settings */
        private ?array $settings,
        /** @var array|null viewConfiguration */
        private ?array $viewConfiguration
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            settings: isset($data['settings']) && is_array($data['settings']) ? $data['settings'] : null,
            viewConfiguration: isset($data['viewConfiguration']) && is_array($data['viewConfiguration']) ? $data['viewConfiguration'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'settings' => $this->settings,
            'viewConfiguration' => $this->viewConfiguration
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getSettings(): ?array { return $this->settings; }
    public function getViewConfiguration(): ?array { return $this->viewConfiguration; }
}
