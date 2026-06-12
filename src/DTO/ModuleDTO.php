<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Module record.
 */
final class ModuleDTO extends AbstractDTO
{
    public function __construct(
        /** enabled state */
        private ?bool $enabled,
        /**
         * module settings
         * @var array<string, mixed>|null
         */
        private ?array $settings
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            settings: isset($data['settings']) && is_array($data['settings']) ? $data['settings'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'enabled' => $this->enabled,
            'settings' => $this->settings,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return null; }
    public function isEnabled(): ?bool { return $this->enabled; }
    /**
     * @return array<string, mixed>|null
     */
    public function getSettings(): ?array { return $this->settings; }
}
