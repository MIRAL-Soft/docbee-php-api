<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomColor record.
 */
final class CustomColorDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific customColor */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** HTML color code in hexadecimal representation */
        private ?string $color,
        /** document status as string in capitals */
        private ?string $documentStatus
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            color: self::toString($data['color'] ?? null),
            documentStatus: self::toString($data['documentStatus'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'color' => $this->color,
            'documentStatus' => $this->documentStatus
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getColor(): ?string { return $this->color; }
    public function getDocumentStatus(): ?string { return $this->documentStatus; }
}