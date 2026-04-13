<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketLinkType record.
 */
final class TicketLinkTypeDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ticket link type */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** inward label */
        private ?string $inward,
        /** name */
        private ?string $name,
        /** outward label */
        private ?string $outward
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            inward: self::toString($data['inward'] ?? null),
            name: self::toString($data['name'] ?? null),
            outward: self::toString($data['outward'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'inward' => $this->inward,
            'name' => $this->name,
            'outward' => $this->outward
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getInward(): ?string { return $this->inward; }
    public function getName(): ?string { return $this->name; }
    public function getOutward(): ?string { return $this->outward; }
}