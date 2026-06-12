<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplateProfile record.
 */
final class ProtocolTemplateProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplateProfile */
        private readonly ?int $id,
        /** name */
        private ?string $name,
        /**
         * @var array<int|string, mixed>|null list of protocolTemplates
         */
        private ?array $protocolTemplates
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            name: self::toString($data['name'] ?? null),
            protocolTemplates: isset($data['protocolTemplates']) && is_array($data['protocolTemplates']) ? $data['protocolTemplates'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'protocolTemplates' => $this->protocolTemplates
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getProtocolTemplates(): ?array { return $this->protocolTemplates; }
}
