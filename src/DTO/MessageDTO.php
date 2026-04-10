<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Message record.
 */
final class MessageDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $user,
        private readonly ?string $sender,
        private readonly ?int $contact,
        private readonly ?string $created,
        private readonly ?string $receivedDate,
        private readonly ?string $senderType,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            user: self::toInt($data['user'] ?? null),
            sender: self::toString($data['sender'] ?? null),
            contact: self::toInt($data['contact'] ?? null),
            created: self::toString($data['created'] ?? null),
            receivedDate: self::toString($data['receivedDate'] ?? null),
            senderType: self::toString($data['senderType'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'user' => $this->user,
            'sender' => $this->sender,
            'contact' => $this->contact,
            'created' => $this->created,
            'receivedDate' => $this->receivedDate,
            'senderType' => $this->senderType,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?int { return $this->user; }
    public function getSender(): ?string { return $this->sender; }
    public function getContact(): ?int { return $this->contact; }
    public function getCreated(): ?string { return $this->created; }
    public function getReceivedDate(): ?string { return $this->receivedDate; }
    public function getSenderType(): ?string { return $this->senderType; }
}