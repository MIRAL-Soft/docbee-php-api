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
        private readonly ?string $link,
        private ?array $columns,
        private ?array $fields,
        private ?array $filters,
        private ?string $name
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            columns: isset($data['columns']) && is_array($data['columns']) ? $data['columns'] : null,
            fields: isset($data['fields']) && is_array($data['fields']) ? $data['fields'] : null,
            filters: isset($data['filters']) && is_array($data['filters']) ? $data['filters'] : null,
            name: self::toString($data['name'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'columns' => $this->columns,
            'fields' => $this->fields,
            'filters' => $this->filters,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getColumns(): ?array { return $this->columns; }
    public function getFields(): ?array { return $this->fields; }
    public function getFilters(): ?array { return $this->filters; }
    public function getName(): ?string { return $this->name; }
}