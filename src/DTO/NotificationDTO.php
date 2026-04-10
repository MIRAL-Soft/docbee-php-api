<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Notification record.
 */
final class NotificationDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $senderUser,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            senderUser: self::toInt($data['senderUser'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'senderUser' => $this->senderUser,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getSenderUser(): ?int { return $this->senderUser; }
}