<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ObserverCategory record.
 */
final class ObserverCategoryDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?array $customFields,
        private ?bool $deactivated,
        private ?string $detailsPattern,
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