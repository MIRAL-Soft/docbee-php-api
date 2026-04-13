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
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?array $fields,
        private readonly ?array $filters,
        private ?string $name,
        private ?bool $shared,
        private ?int $subType,
        private ?string $type,
        private ?int $user
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            fields: isset($data['fields']) && is_array($data['fields']) ? $data['fields'] : null,
            filters: isset($data['filters']) && is_array($data['filters']) ? $data['filters'] : null,
            name: self::toString($data['name'] ?? null),
            shared: isset($data['shared']) ? self::toBool($data['shared']) : null,
            subType: self::toInt($data['subType'] ?? null),
            type: self::toString($data['type'] ?? null),
            user: self::toInt($data['user'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'shared' => $this->shared,
            'subType' => $this->subType,
            'type' => $this->type,
            'user' => $this->user
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getFields(): ?array { return $this->fields; }
    public function getFilters(): ?array { return $this->filters; }
    public function getName(): ?string { return $this->name; }
    public function getShared(): ?bool { return $this->shared; }
    public function getSubType(): ?int { return $this->subType; }
    public function getType(): ?string { return $this->type; }
    public function getUser(): ?int { return $this->user; }
}