<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee PresetValue record.
 */
final class PresetValueDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?string $group,
        private ?string $code,
        private ?string $value,
        private ?bool $valueSearchable
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            group: self::toString($data['group'] ?? null),
            code: self::toString($data['code'] ?? null),
            value: self::toString($data['value'] ?? null),
            valueSearchable: isset($data['valueSearchable']) ? self::toBool($data['valueSearchable']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'code' => $this->code,
            'value' => $this->value,
            'valueSearchable' => $this->valueSearchable
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getGroup(): ?string { return $this->group; }
    public function getCode(): ?string { return $this->code; }
    public function getValue(): ?string { return $this->value; }
    public function getValueSearchable(): ?bool { return $this->valueSearchable; }
}