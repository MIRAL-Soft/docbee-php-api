<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Reminder record.
 */
final class ReminderDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific reminder */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** reminder date */
        private ?string $date,
        /** done flag */
        private ?bool $done,
        /** send notification flag */
        private ?bool $sendNotification,
        /** create ticket message flag */
        private ?bool $createTicketMessage
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            date: self::toString($data['date'] ?? null),
            done: isset($data['done']) ? self::toBool($data['done']) : null,
            sendNotification: isset($data['sendNotification']) ? self::toBool($data['sendNotification']) : null,
            createTicketMessage: isset($data['createTicketMessage']) ? self::toBool($data['createTicketMessage']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'date' => $this->date,
            'done' => $this->done,
            'sendNotification' => $this->sendNotification,
            'createTicketMessage' => $this->createTicketMessage,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getDate(): ?string { return $this->date; }
    public function isDone(): ?bool { return $this->done; }
    public function isSendNotification(): ?bool { return $this->sendNotification; }
    public function isCreateTicketMessage(): ?bool { return $this->createTicketMessage; }
}
