<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SelectionValue record.
 */
final class SelectionValueDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?string $details,
        private ?array $customFields,
        private ?array $filterNames,
        private ?string $name,
        private ?string $scanCode
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            details: self::toString($data['details'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            filterNames: isset($data['filterNames']) && is_array($data['filterNames']) ? $data['filterNames'] : null,
            name: self::toString($data['name'] ?? null),
            scanCode: self::toString($data['scanCode'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customFields' => $this->customFields,
            'filterNames' => $this->filterNames,
            'name' => $this->name,
            'scanCode' => $this->scanCode
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getDetails(): ?string { return $this->details; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getFilterNames(): ?array { return $this->filterNames; }
    public function getName(): ?string { return $this->name; }
    public function getScanCode(): ?string { return $this->scanCode; }
}