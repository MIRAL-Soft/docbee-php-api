<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketCategory record.
 */
final class TicketCategoryDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?string $name,
        private ?array $protocolTemplateProfiles
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            protocolTemplateProfiles: isset($data['protocolTemplateProfiles']) && is_array($data['protocolTemplateProfiles']) ? $data['protocolTemplateProfiles'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'protocolTemplateProfiles' => $this->protocolTemplateProfiles
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getProtocolTemplateProfiles(): ?array { return $this->protocolTemplateProfiles; }
}