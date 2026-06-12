<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DepartmentProfile record.
 */
final class DepartmentProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific departmentProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /**
         * department identifiers
         * @var array<int|string, mixed>|null
         */
        private ?array $links,
        /** name */
        private ?string $name
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            links: isset($data['links']) && is_array($data['links']) ? $data['links'] : null,
            name: self::toString($data['name'] ?? null)
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'links' => $this->links,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getLinks(): ?array { return $this->links; }
    public function getName(): ?string { return $this->name; }
}