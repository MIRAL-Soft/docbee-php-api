<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketLink record.
 */
final class TicketLinkDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ticket link */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** ticket identifier */
        private ?int $ticket,
        /** ticket link type identifier */
        private ?int $type
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            ticket: self::toInt($data['ticket'] ?? null),
            type: self::toInt($data['type'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'ticket' => $this->ticket,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getTicket(): ?int { return $this->ticket; }
    public function getType(): ?int { return $this->type; }
}