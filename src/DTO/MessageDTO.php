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
        private readonly ?string $created,
        private readonly ?string $link,
        private readonly ?int $contact,
        private readonly ?string $receivedDate,
        private readonly ?string $sender,
        private readonly ?string $senderType,
        private readonly ?int $user,
        private ?array $attachments,
        private ?string $content,
        private ?bool $hidden,
        private ?bool $internal,
        private ?string $subject
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            link: self::toString($data['link'] ?? null),
            contact: self::toInt($data['contact'] ?? null),
            receivedDate: self::toString($data['receivedDate'] ?? null),
            sender: self::toString($data['sender'] ?? null),
            senderType: self::toString($data['senderType'] ?? null),
            user: self::toInt($data['user'] ?? null),
            attachments: isset($data['attachments']) && is_array($data['attachments']) ? $data['attachments'] : null,
            content: self::toString($data['content'] ?? null),
            hidden: isset($data['hidden']) ? self::toBool($data['hidden']) : null,
            internal: isset($data['internal']) ? self::toBool($data['internal']) : null,
            subject: self::toString($data['subject'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'attachments' => $this->attachments,
            'content' => $this->content,
            'hidden' => $this->hidden,
            'internal' => $this->internal,
            'subject' => $this->subject
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getLink(): ?string { return $this->link; }
    public function getContact(): ?int { return $this->contact; }
    public function getReceivedDate(): ?string { return $this->receivedDate; }
    public function getSender(): ?string { return $this->sender; }
    public function getSenderType(): ?string { return $this->senderType; }
    public function getUser(): ?int { return $this->user; }
    public function getAttachments(): ?array { return $this->attachments; }
    public function getContent(): ?string { return $this->content; }
    public function getHidden(): ?bool { return $this->hidden; }
    public function getInternal(): ?bool { return $this->internal; }
    public function getSubject(): ?string { return $this->subject; }
}