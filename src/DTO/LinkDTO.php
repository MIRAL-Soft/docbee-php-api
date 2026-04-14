<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Link record (customer/contact URL link).
 */
final class LinkDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific link */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** url */
        private ?string $url,
        /** startTimer */
        private ?bool $startTimer,
        /** timerName */
        private ?string $timerName,
        /** type */
        private ?string $type
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            url: self::toString($data['url'] ?? null),
            startTimer: self::toBool($data['startTimer'] ?? null),
            timerName: self::toString($data['timerName'] ?? null),
            type: self::toString($data['type'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'url' => $this->url,
            'startTimer' => $this->startTimer,
            'timerName' => $this->timerName,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getUrl(): ?string { return $this->url; }
    public function getStartTimer(): ?bool { return $this->startTimer; }
    public function getTimerName(): ?string { return $this->timerName; }
    public function getType(): ?string { return $this->type; }
}
