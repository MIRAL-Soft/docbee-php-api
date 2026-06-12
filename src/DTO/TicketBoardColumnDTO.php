<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketBoardColumn record.
 */
final class TicketBoardColumnDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ticketBoardColumn */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** additionalInfo */
        private ?string $additionalInfo,
        /** customField identifier */
        private ?int $customField,
        /** name */
        private ?string $name,
        /** @var list<int>|null ticketStatus identifiers */
        private ?array $ticketStatuses
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            additionalInfo: self::toString($data['additionalInfo'] ?? null),
            customField: self::toInt($data['customField'] ?? null),
            name: self::toString($data['name'] ?? null),
            ticketStatuses: isset($data['ticketStatuses']) && is_array($data['ticketStatuses']) ? $data['ticketStatuses'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'additionalInfo' => $this->additionalInfo,
            'customField' => $this->customField,
            'name' => $this->name,
            'ticketStatuses' => $this->ticketStatuses
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getAdditionalInfo(): ?string { return $this->additionalInfo; }
    public function getCustomField(): ?int { return $this->customField; }
    public function getName(): ?string { return $this->name; }
    /** @return list<int>|null */
    public function getTicketStatuses(): ?array { return $this->ticketStatuses; }
}