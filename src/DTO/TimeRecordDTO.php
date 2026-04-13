<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TimeRecord record.
 */
final class TimeRecordDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $link,
        private readonly ?bool $enabled,
        private ?string $comment,
        private ?bool $current,
        private ?string $externalAppName,
        private ?string $externalId,
        private ?string $started,
        private ?int $time,
        private ?int $user
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            comment: self::toString($data['comment'] ?? null),
            current: isset($data['current']) ? self::toBool($data['current']) : null,
            externalAppName: self::toString($data['externalAppName'] ?? null),
            externalId: self::toString($data['externalId'] ?? null),
            started: self::toString($data['started'] ?? null),
            time: self::toInt($data['time'] ?? null),
            user: self::toInt($data['user'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'comment' => $this->comment,
            'current' => $this->current,
            'externalAppName' => $this->externalAppName,
            'externalId' => $this->externalId,
            'started' => $this->started,
            'time' => $this->time,
            'user' => $this->user
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getEnabled(): ?bool { return $this->enabled; }
    public function getComment(): ?string { return $this->comment; }
    public function getCurrent(): ?bool { return $this->current; }
    public function getExternalAppName(): ?string { return $this->externalAppName; }
    public function getExternalId(): ?string { return $this->externalId; }
    public function getStarted(): ?string { return $this->started; }
    public function getTime(): ?int { return $this->time; }
    public function getUser(): ?int { return $this->user; }
}