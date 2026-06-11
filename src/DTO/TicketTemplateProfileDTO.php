<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketTemplateProfile record.
 */
final class TicketTemplateProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ticketTemplateProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** @var array|null list of ticketTemplates */
        private ?array $ticketTemplates
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            ticketTemplates: isset($data['ticketTemplates']) && is_array($data['ticketTemplates']) ? $data['ticketTemplates'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'ticketTemplates' => $this->ticketTemplates
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getTicketTemplates(): ?array { return $this->ticketTemplates; }
}
