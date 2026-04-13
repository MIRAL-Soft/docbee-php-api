<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SelectionCategory record.
 */
final class SelectionCategoryDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?array $customFields,
        private ?string $detailsPattern,
        private ?string $name,
        private ?bool $uniqueValues,
        private ?bool $withScanCode
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
            detailsPattern: self::toString($data['detailsPattern'] ?? null),
            name: self::toString($data['name'] ?? null),
            uniqueValues: isset($data['uniqueValues']) ? self::toBool($data['uniqueValues']) : null,
            withScanCode: isset($data['withScanCode']) ? self::toBool($data['withScanCode']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'detailsPattern' => $this->detailsPattern,
            'name' => $this->name,
            'uniqueValues' => $this->uniqueValues,
            'withScanCode' => $this->withScanCode
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getDetailsPattern(): ?string { return $this->detailsPattern; }
    public function getName(): ?string { return $this->name; }
    public function getUniqueValues(): ?bool { return $this->uniqueValues; }
    public function getWithScanCode(): ?bool { return $this->withScanCode; }
}