<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolDocumentTemplate record.
 */
final class ProtocolDocumentTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $name,
        private readonly ?int $protocolTemplate,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            name: self::toString($data['name'] ?? null),
            protocolTemplate: self::toInt($data['protocolTemplate'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'protocolTemplate' => $this->protocolTemplate,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getProtocolTemplate(): ?int { return $this->protocolTemplate; }
}