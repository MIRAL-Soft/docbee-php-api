<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Object record.
 */
final class ObjectDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific object */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** name */
        private ?string $name,
        /** object category reference */
        private ?int $objectCategory,
        /** object number */
        private ?string $objectNumber,
        /** deactivated */
        private ?bool $deactivated
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            name: self::toString($data['name'] ?? null),
            objectCategory: self::toInt($data['objectCategory'] ?? null),
            objectNumber: self::toString($data['objectNumber'] ?? null),
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'objectCategory' => $this->objectCategory,
            'objectNumber' => $this->objectNumber,
            'deactivated' => $this->deactivated,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getName(): ?string { return $this->name; }
    public function getObjectCategory(): ?int { return $this->objectCategory; }
    public function getObjectNumber(): ?string { return $this->objectNumber; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
}
