<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TableConfigStorage record.
 */
final class TableConfigStorageDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly array $fields,
        private readonly array $filters,
        private readonly ?int $user,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            fields: $data['fields'] ?? [],
            filters: $data['filters'] ?? [],
            user: self::toInt($data['user'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'fields' => $this->fields,
            'filters' => $this->filters,
            'user' => $this->user,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getFields(): array { return $this->fields; }
    public function getFilters(): array { return $this->filters; }
    public function getUser(): ?int { return $this->user; }
}