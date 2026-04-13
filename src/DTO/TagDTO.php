<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Tag record.
 */
final class TagDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?string $color,
        private ?bool $deactivated,
        private ?string $name
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            color: self::toString($data['color'] ?? null),
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            name: self::toString($data['name'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'color' => $this->color,
            'deactivated' => $this->deactivated,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getColor(): ?string { return $this->color; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function getName(): ?string { return $this->name; }
}