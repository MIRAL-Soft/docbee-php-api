<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ObserverCategory record.
 */
final class ObserverCategoryDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific observerCategory */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** list of customFields */
        private readonly ?array $customFields,
        /** deactivated */
        private ?bool $deactivated,
        /** detailsPattern */
        private ?string $detailsPattern,
        /** name */
        private ?string $name
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            deactivated: isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            detailsPattern: self::toString($data['detailsPattern'] ?? null),
            name: self::toString($data['name'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'deactivated' => $this->deactivated,
            'detailsPattern' => $this->detailsPattern,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function isDeactivated(): ?bool { return $this->deactivated; }
    public function getDetailsPattern(): ?string { return $this->detailsPattern; }
    public function getName(): ?string { return $this->name; }
}