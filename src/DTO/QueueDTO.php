<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Queue record.
 */
final class QueueDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $link,
        private ?bool $enabled,
        private ?string $name
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            name: self::toString($data['name'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'enabled' => $this->enabled,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getEnabled(): ?bool { return $this->enabled; }
    public function getName(): ?string { return $this->name; }
}