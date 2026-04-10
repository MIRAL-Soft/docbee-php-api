<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketMessage record.
 */
final class TicketMessageDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $docBeeDocument,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            docBeeDocument: self::toInt($data['docBeeDocument'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'docBeeDocument' => $this->docBeeDocument,
        ], fn($v) => $v !== null);
    }

    public function getDocBeeDocument(): ?int { return $this->docBeeDocument; }
    public function getId(): ?int { return null; }
}