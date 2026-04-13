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
        private readonly ?string $link,
        private ?string $body,
        private ?string $linkUrl,
        private ?int $senderUser,
        private ?string $subject,
        private ?string $tag,
        private ?bool $tracked,
        private ?string $type,
        private ?int $user
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            body: self::toString($data['body'] ?? null),
            linkUrl: self::toString($data['linkUrl'] ?? null),
            senderUser: self::toInt($data['senderUser'] ?? null),
            subject: self::toString($data['subject'] ?? null),
            tag: self::toString($data['tag'] ?? null),
            tracked: isset($data['tracked']) ? self::toBool($data['tracked']) : null,
            type: self::toString($data['type'] ?? null),
            user: self::toInt($data['user'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'body' => $this->body,
            'linkUrl' => $this->linkUrl,
            'senderUser' => $this->senderUser,
            'subject' => $this->subject,
            'tag' => $this->tag,
            'tracked' => $this->tracked,
            'type' => $this->type,
            'user' => $this->user
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getBody(): ?string { return $this->body; }
    public function getLinkUrl(): ?string { return $this->linkUrl; }
    public function getSenderUser(): ?int { return $this->senderUser; }
    public function getSubject(): ?string { return $this->subject; }
    public function getTag(): ?string { return $this->tag; }
    public function getTracked(): ?bool { return $this->tracked; }
    public function getType(): ?string { return $this->type; }
    public function getUser(): ?int { return $this->user; }
}