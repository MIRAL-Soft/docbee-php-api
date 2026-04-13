<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketMessage record.
 */
final class TicketMessageDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific ticketTemplate */
        private readonly ?int $id,
        /** created */
        private readonly ?string $created,
        /** REST API Link */
        private readonly ?string $link,
        /** contact identifier */
        private readonly ?int $contact,
        /** docBeeDocument identifier */
        private readonly ?int $docBeeDocument,
        /** receivedDate */
        private readonly ?string $receivedDate,
        /** sender */
        private readonly ?string $sender,
        /** sender type */
        private readonly ?string $senderType,
        /** user identifier */
        private readonly ?int $user,
        /** file identifiers */
        private ?array $attachments,
        /** content */
        private ?string $content,
        /** hidden */
        private ?bool $hidden,
        /** internal */
        private ?bool $internal,
        /** subject */
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
            docBeeDocument: self::toInt($data['docBeeDocument'] ?? null),
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
    public function getDocBeeDocument(): ?int { return $this->docBeeDocument; }
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