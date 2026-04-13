<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SelectionValue record.
 */
final class SelectionValueDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific selectionValue */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** details */
        private readonly ?string $details,
        /** @var CustomFieldValueDTO[]|null list of customFieldValues */
        private ?array $customFields,
        /** file identifiers */
        private ?array $filterNames,
        /** name */
        private ?string $name,
        /** scanCode */
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
            customFields: isset($data['customFields']) && is_array($data['customFields'])
                ? array_map(fn($x) => CustomFieldValueDTO::fromArray($x), $data['customFields'])
                : null,
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