<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PresetProfile record.
 */
final class PresetProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific preset profile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /**
         * presetValue identifiers
         * @var array<int|string, mixed>|null
         */
        private readonly ?array $presetValues,
        /**
         * user identifiers
         * @var array<int|string, mixed>|null
         */
        private readonly ?array $users,
        /** name */
        private ?string $name
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            presetValues: isset($data['presetValues']) && is_array($data['presetValues']) ? $data['presetValues'] : null,
            users: isset($data['users']) && is_array($data['users']) ? $data['users'] : null,
            name: self::toString($data['name'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getPresetValues(): ?array { return $this->presetValues; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getUsers(): ?array { return $this->users; }
    public function getName(): ?string { return $this->name; }
}