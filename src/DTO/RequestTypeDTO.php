<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee request type (channel through which a ticket was created).
 */
final class RequestTypeDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private ?string       $name,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:   self::toInt($data['id'] ?? null),
            name: self::toString($data['name'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int      { return $this->id; }
    public function getName(): ?string { return $this->name; }
}
