<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MessageBucket record.
 */
final class MessageBucketDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific message bucket */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private ?string $name,
        /** sending time */
        private ?string $sendingTime,
        /** message template reference */
        private ?int $messageTemplate,
        /** web notification message template reference */
        private ?int $webNotificationMessageTemplate,
        /** send mail flag */
        private ?bool $sendMail,
        /** send web notification flag */
        private ?bool $sendWebNotification
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            sendingTime: self::toString($data['sendingTime'] ?? null),
            messageTemplate: self::toInt($data['messageTemplate'] ?? null),
            webNotificationMessageTemplate: self::toInt($data['webNotificationMessageTemplate'] ?? null),
            sendMail: isset($data['sendMail']) ? self::toBool($data['sendMail']) : null,
            sendWebNotification: isset($data['sendWebNotification']) ? self::toBool($data['sendWebNotification']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'sendingTime' => $this->sendingTime,
            'messageTemplate' => $this->messageTemplate,
            'webNotificationMessageTemplate' => $this->webNotificationMessageTemplate,
            'sendMail' => $this->sendMail,
            'sendWebNotification' => $this->sendWebNotification,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getSendingTime(): ?string { return $this->sendingTime; }
    public function getMessageTemplate(): ?int { return $this->messageTemplate; }
    public function getWebNotificationMessageTemplate(): ?int { return $this->webNotificationMessageTemplate; }
    public function isSendMail(): ?bool { return $this->sendMail; }
    public function isSendWebNotification(): ?bool { return $this->sendWebNotification; }
}
