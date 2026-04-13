<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DailyClosingConfig record.
 */
final class DailyClosingConfigDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private ?string $ccMailRecievers,
        private ?bool $countOnlyExternalTimes,
        private ?bool $countOnlyFinishedTimes,
        private ?array $messageTemplate,
        private ?int $minTime,
        private ?string $name,
        private ?bool $sendMail,
        private ?bool $sendWebNotification,
        private ?bool $showReminder,
        private ?int $timeOffset,
        private ?array $webNotificationTemplate
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            ccMailRecievers: self::toString($data['ccMailRecievers'] ?? null),
            countOnlyExternalTimes: isset($data['countOnlyExternalTimes']) ? self::toBool($data['countOnlyExternalTimes']) : null,
            countOnlyFinishedTimes: isset($data['countOnlyFinishedTimes']) ? self::toBool($data['countOnlyFinishedTimes']) : null,
            messageTemplate: isset($data['messageTemplate']) && is_array($data['messageTemplate']) ? $data['messageTemplate'] : null,
            minTime: self::toInt($data['minTime'] ?? null),
            name: self::toString($data['name'] ?? null),
            sendMail: isset($data['sendMail']) ? self::toBool($data['sendMail']) : null,
            sendWebNotification: isset($data['sendWebNotification']) ? self::toBool($data['sendWebNotification']) : null,
            showReminder: isset($data['showReminder']) ? self::toBool($data['showReminder']) : null,
            timeOffset: self::toInt($data['timeOffset'] ?? null),
            webNotificationTemplate: isset($data['webNotificationTemplate']) && is_array($data['webNotificationTemplate']) ? $data['webNotificationTemplate'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'ccMailRecievers' => $this->ccMailRecievers,
            'countOnlyExternalTimes' => $this->countOnlyExternalTimes,
            'countOnlyFinishedTimes' => $this->countOnlyFinishedTimes,
            'messageTemplate' => $this->messageTemplate,
            'minTime' => $this->minTime,
            'name' => $this->name,
            'sendMail' => $this->sendMail,
            'sendWebNotification' => $this->sendWebNotification,
            'showReminder' => $this->showReminder,
            'timeOffset' => $this->timeOffset,
            'webNotificationTemplate' => $this->webNotificationTemplate
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCcMailRecievers(): ?string { return $this->ccMailRecievers; }
    public function getCountOnlyExternalTimes(): ?bool { return $this->countOnlyExternalTimes; }
    public function getCountOnlyFinishedTimes(): ?bool { return $this->countOnlyFinishedTimes; }
    public function getMessageTemplate(): ?array { return $this->messageTemplate; }
    public function getMinTime(): ?int { return $this->minTime; }
    public function getName(): ?string { return $this->name; }
    public function getSendMail(): ?bool { return $this->sendMail; }
    public function getSendWebNotification(): ?bool { return $this->sendWebNotification; }
    public function getShowReminder(): ?bool { return $this->showReminder; }
    public function getTimeOffset(): ?int { return $this->timeOffset; }
    public function getWebNotificationTemplate(): ?array { return $this->webNotificationTemplate; }
}