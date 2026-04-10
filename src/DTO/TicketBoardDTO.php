<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketBoard record.
 */
final class TicketBoardDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $name,
        private readonly array $columns,
        private readonly array $fields,
        private readonly array $filters,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            name: self::toString($data['name'] ?? null),
            columns: $data['columns'] ?? [],
            fields: $data['fields'] ?? [],
            filters: $data['filters'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'columns' => $this->columns,
            'fields' => $this->fields,
            'filters' => $this->filters,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getColumns(): array { return $this->columns; }
    public function getFields(): array { return $this->fields; }
    public function getFilters(): array { return $this->filters; }
}