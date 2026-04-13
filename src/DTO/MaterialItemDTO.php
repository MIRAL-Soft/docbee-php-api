<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MaterialItem record.
 *
 * Maps to the `materialItem` API endpoint.
 */
final class MaterialItemDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $name,
        private readonly ?bool   $deactivated,
        private readonly ?string $number,
        private readonly ?bool   $hasSerialNumber,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:              self::toInt($data['id'] ?? null),
            name:            self::toString($data['name'] ?? null),
            deactivated:     isset($data['deactivated']) ? self::toBool($data['deactivated']) : null,
            number:          self::toString($data['number'] ?? null),
            hasSerialNumber: isset($data['hasSerialNumber']) ? self::toBool($data['hasSerialNumber']) : null,
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name'            => $this->name,
            'deactivated'     => $this->deactivated,
            'number'          => $this->number,
            'hasSerialNumber' => $this->hasSerialNumber,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int              { return $this->id; }
    public function getName(): ?string         { return $this->name; }
    public function isDeactivated(): ?bool     { return $this->deactivated; }
    public function getNumber(): ?string       { return $this->number; }
    public function hasSerialNumber(): ?bool   { return $this->hasSerialNumber; }
}
