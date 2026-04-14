<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee GroupContainer record.
 */
final class GroupContainerDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific groupContainer */
        private readonly ?int $id,
        /** modified timestamp */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** type */
        private readonly ?string $type,
        /** name */
        private ?string $name,
        /** icon */
        private ?int $icon,
        /** position */
        private ?int $position,
        /** orientation */
        private ?string $orientation,
        /** pdfOrientation */
        private ?string $pdfOrientation
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            type: self::toString($data['type'] ?? null),
            name: self::toString($data['name'] ?? null),
            icon: self::toInt($data['icon'] ?? null),
            position: self::toInt($data['position'] ?? null),
            orientation: self::toString($data['orientation'] ?? null),
            pdfOrientation: self::toString($data['pdfOrientation'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'icon' => $this->icon,
            'position' => $this->position,
            'orientation' => $this->orientation,
            'pdfOrientation' => $this->pdfOrientation
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getType(): ?string { return $this->type; }
    public function getName(): ?string { return $this->name; }
    public function getIcon(): ?int { return $this->icon; }
    public function getPosition(): ?int { return $this->position; }
    public function getOrientation(): ?string { return $this->orientation; }
    public function getPdfOrientation(): ?string { return $this->pdfOrientation; }
}
