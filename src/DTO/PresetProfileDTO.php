<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PresetProfile record.
 */
final class PresetProfileDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly array $users,
        private readonly array $presetValues,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            users: $data['users'] ?? [],
            presetValues: $data['presetValues'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'users' => $this->users,
            'presetValues' => $this->presetValues,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getUsers(): array { return $this->users; }
    public function getPresetValues(): array { return $this->presetValues; }
}