<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ServiceType record.
 */
final class ServiceTypeDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific serviceType */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** deactivated */
        private ?bool $deactivated,
        /** name */
        private ?string $name,
        /** number */
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
            name: self::toString($data['name'] ?? null),
            number: self::toString($data['number'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'deactivated' => $this->deactivated,
            'name' => $this->name,
            'number' => $this->number
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function getName(): ?string { return $this->name; }
    public function getNumber(): ?string { return $this->number; }
}