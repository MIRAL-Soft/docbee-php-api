<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketRecurrence record.
 */
final class TicketRecurrenceDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ticket recurrence */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        private mixed $recurrence,
        private mixed $template
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            recurrence: $data['recurrence'] ?? null,
            template: $data['template'] ?? null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'recurrence' => $this->recurrence,
            'template' => $this->template
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getRecurrence(): mixed { return $this->recurrence; }
    public function getTemplate(): mixed { return $this->template; }
}