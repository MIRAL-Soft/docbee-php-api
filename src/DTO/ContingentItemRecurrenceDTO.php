<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ContingentItemRecurrence record.
 */
final class ContingentItemRecurrenceDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific itemRecurrence */
        private readonly ?int    $id,
        /** REST API Link */
        private readonly ?string $link,
        /** money */
        private ?float           $money,
        /** time in milliseconds */
        private ?int             $time,
        /** description */
        private ?string          $description,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:          self::toInt($data['id'] ?? null),
            link:        self::toString($data['link'] ?? null),
            money:       isset($data['money']) ? (float) $data['money'] : null,
            time:        self::toInt($data['time'] ?? null),
            description: self::toString($data['description'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'money'       => $this->money,
            'time'        => $this->time,
            'description' => $this->description,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int             { return $this->id; }
    public function getLink(): ?string        { return $this->link; }
    public function getMoney(): ?float        { return $this->money; }
    public function getTime(): ?int           { return $this->time; }
    public function getDescription(): ?string { return $this->description; }
}
