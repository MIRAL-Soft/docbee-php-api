<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DailyClosingConfig record.
 */
final class DailyClosingConfigDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific dailyClosingConfig */
        private readonly ?int $id,
        /** list of CC Mail Recievers */
        private ?string $ccMailRecievers,
        /** if this is active only not internal times are counted for minTime */
        private ?bool $countOnlyExternalTimes,
        /** if this is active only finished times are counted for minTime */
        private ?bool $countOnlyFinishedTimes,
        /**
         * mail MessageTemplate
         * @var array<string, mixed>|null
         */
        private ?array $messageTemplate,
        /** minimum time per day in miliseconds */
        private ?int $minTime,
        /** name */
        private ?string $name,
        /** flag to send mail */
        private ?bool $sendMail,
        /** flag to send web notification */
        private ?bool $sendWebNotification,
        /** flag to show reminder */
        private ?bool $showReminder,
        /** offset to midnight */
        private ?int $timeOffset,
        /**
         * webNotification MessageTemplate
         * @var array<string, mixed>|null
         */
        private ?array $webNotificationTemplate
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    #[\Override]
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

    /**
     * @return array<string, mixed>
     */
    #[\Override]
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
    /**
     * @return array<string, mixed>|null
     */
    public function getMessageTemplate(): ?array { return $this->messageTemplate; }
    public function getMinTime(): ?int { return $this->minTime; }
    public function getName(): ?string { return $this->name; }
    public function getSendMail(): ?bool { return $this->sendMail; }
    public function getSendWebNotification(): ?bool { return $this->sendWebNotification; }
    public function getShowReminder(): ?bool { return $this->showReminder; }
    public function getTimeOffset(): ?int { return $this->timeOffset; }
    /**
     * @return array<string, mixed>|null
     */
    public function getWebNotificationTemplate(): ?array { return $this->webNotificationTemplate; }
}