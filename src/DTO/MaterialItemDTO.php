<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MaterialItem record.
 */
final class MaterialItemDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?bool $deactivated,
        private ?bool $hasSerialNumber,
        private ?string $name,
        private ?string $number
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            hasSerialNumber: isset($data['hasSerialNumber']) ? self::toBool($data['hasSerialNumber']) : null,
            name: self::toString($data['name'] ?? null),
            number: self::toString($data['number'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'deactivated' => $this->deactivated,
            'hasSerialNumber' => $this->hasSerialNumber,
            'name' => $this->name,
            'number' => $this->number
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function hasSerialNumber(): ?bool { return $this->hasSerialNumber; }
    public function getName(): ?string { return $this->name; }
    public function getNumber(): ?string { return $this->number; }
}