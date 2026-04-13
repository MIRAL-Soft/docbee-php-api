<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee EnvVariable record.
 */
final class EnvVariableDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?string $name,
        private ?bool $secret,
        private ?string $value
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            secret: isset($data['secret']) ? self::toBool($data['secret']) : null,
            value: self::toString($data['value'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'secret' => $this->secret,
            'value' => $this->value
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getSecret(): ?bool { return $this->secret; }
    public function getValue(): ?string { return $this->value; }
}