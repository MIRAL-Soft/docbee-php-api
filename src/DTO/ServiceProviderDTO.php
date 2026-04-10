<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ServiceProvider record.
 */
final class ServiceProviderDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $shortName,
        private readonly ?string $email,
        private readonly ?string $number,
        private readonly bool $deactivated,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            shortName: self::toString($data['shortName'] ?? null),
            email: self::toString($data['email'] ?? null),
            number: self::toString($data['number'] ?? null),
            deactivated: self::toBool($data['deactivated'] ?? false),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'shortName' => $this->shortName,
            'email' => $this->email,
            'number' => $this->number,
            'deactivated' => $this->deactivated,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getShortName(): ?string { return $this->shortName; }
    public function getEmail(): ?string { return $this->email; }
    public function getNumber(): ?string { return $this->number; }
    public function isDeactivated(): bool { return $this->deactivated; }
}