<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomerStatus record.
 *
 * Maps to the `customerStatus` API endpoint.
 */
final class CustomerStatusDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $name,
        private readonly ?bool   $selectable,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:         self::toInt($data['id'] ?? null),
            name:       self::toString($data['name'] ?? null),
            selectable: isset($data['selectable']) ? self::toBool($data['selectable']) : null,
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name'       => $this->name,
            'selectable' => $this->selectable,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int        { return $this->id; }
    public function getName(): ?string   { return $this->name; }
    public function isSelectable(): ?bool { return $this->selectable; }
}
