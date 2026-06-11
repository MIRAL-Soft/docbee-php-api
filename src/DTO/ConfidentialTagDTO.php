<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ConfidentialTag record.
 */
final class ConfidentialTagDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific confidentialTag */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** allow owner of an object with a confidentialTag to access that object */
        private ?bool $addOwnerAccess,
        /** name */
        private ?string $name
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            addOwnerAccess: isset($data['addOwnerAccess']) ? self::toBool($data['addOwnerAccess']) : null,
            name: self::toString($data['name'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'addOwnerAccess' => $this->addOwnerAccess,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getAddOwnerAccess(): ?bool { return $this->addOwnerAccess; }
    public function getName(): ?string { return $this->name; }
}