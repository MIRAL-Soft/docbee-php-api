<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AgreementTemplate record.
 */
final class AgreementTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly array $components,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            components: $data['components'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'components' => $this->components,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getComponents(): array { return $this->components; }
}