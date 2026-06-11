<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TimeRecord record.
 */
final class TimeRecordDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific time record */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** is TimeRecord enabled */
        private readonly ?bool $enabled,
        /** comment for the timeRecord */
        private ?string $comment,
        /** current active timeRecord can only be set if externalAppName is set */
        private ?bool $current,
        /** external app name */
        private ?string $externalAppName,
        /** external id */
        private ?string $externalId,
        /** started date */
        private ?string $started,
        /** time in milliseconds */
        private ?int $time,
        /** User identifier. If not provided the identifier of the logged in user. */
        private ?int $user
    ) {}

    #[\Override]
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

    #[\Override]
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