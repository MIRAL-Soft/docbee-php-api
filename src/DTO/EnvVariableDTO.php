<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee EnvVariable record.
 */
final class EnvVariableDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific envVariable */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** Name of the envVariable */
        private ?string $name,
        /** Secret status */
        private ?bool $secret,
        /** Value of the envVariable (If the value is stored as secret no value will returned) */
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