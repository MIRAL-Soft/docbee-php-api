<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DashboardWidget record.
 */
final class DashboardWidgetDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific dashboardWidget */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** withEmail */
        private ?bool $forcedSubscribed,
        /** name */
        private ?string $name,
        /** map of settings */
        private ?array $settings,
        /** type */
        private ?string $type,
        /** user id */
        private ?int $user,
        /** validForUser */
        private ?bool $validForUser,
        private mixed $viewConfiguration
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            forcedSubscribed: isset($data['forcedSubscribed']) ? self::toBool($data['forcedSubscribed']) : null,
            name: self::toString($data['name'] ?? null),
            settings: isset($data['settings']) && is_array($data['settings']) ? $data['settings'] : null,
            type: self::toString($data['type'] ?? null),
            user: self::toInt($data['user'] ?? null),
            validForUser: isset($data['validForUser']) ? self::toBool($data['validForUser']) : null,
            viewConfiguration: $data['viewConfiguration'] ?? null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'forcedSubscribed' => $this->forcedSubscribed,
            'name' => $this->name,
            'settings' => $this->settings,
            'type' => $this->type,
            'user' => $this->user,
            'validForUser' => $this->validForUser,
            'viewConfiguration' => $this->viewConfiguration
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getForcedSubscribed(): ?bool { return $this->forcedSubscribed; }
    public function getName(): ?string { return $this->name; }
    public function getSettings(): ?array { return $this->settings; }
    public function getType(): ?string { return $this->type; }
    public function getUser(): ?int { return $this->user; }
    public function getValidForUser(): ?bool { return $this->validForUser; }
    public function getViewConfiguration(): mixed { return $this->viewConfiguration; }
}