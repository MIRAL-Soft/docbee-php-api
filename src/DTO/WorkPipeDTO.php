<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee WorkPipe record.
 */
final class WorkPipeDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific work pipe item */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** json object */
        private ?array $data,
        /** work pipe item type */
        private ?string $type
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            data: isset($data['data']) && is_array($data['data']) ? $data['data'] : null,
            type: self::toString($data['type'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'data' => $this->data,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getData(): ?array { return $this->data; }
    public function getType(): ?string { return $this->type; }
}