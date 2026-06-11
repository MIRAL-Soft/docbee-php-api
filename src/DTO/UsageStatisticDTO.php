<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents Docbee usage statistics.
 */
final class UsageStatisticDTO extends AbstractDTO
{
    public function __construct(
        /** maximum number of users */
        private ?int $maxUsers,
        /** number of active users */
        private ?int $activeUsers,
        /** maximum number of service providers */
        private ?int $maxServiceProviders,
        /** number of active service providers */
        private ?int $activeServiceProviders,
        /** current monthly finished webhook protocols count */
        private ?int $currentMonthlyFinishedWebhookProtocols
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            maxUsers: self::toInt($data['maxUsers'] ?? null),
            activeUsers: self::toInt($data['activeUsers'] ?? null),
            maxServiceProviders: self::toInt($data['maxServiceProviders'] ?? null),
            activeServiceProviders: self::toInt($data['activeServiceProviders'] ?? null),
            currentMonthlyFinishedWebhookProtocols: self::toInt($data['currentMonthlyFinishedWebhookProtocols'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'maxUsers' => $this->maxUsers,
            'activeUsers' => $this->activeUsers,
            'maxServiceProviders' => $this->maxServiceProviders,
            'activeServiceProviders' => $this->activeServiceProviders,
            'currentMonthlyFinishedWebhookProtocols' => $this->currentMonthlyFinishedWebhookProtocols,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return null; }
    public function getMaxUsers(): ?int { return $this->maxUsers; }
    public function getActiveUsers(): ?int { return $this->activeUsers; }
    public function getMaxServiceProviders(): ?int { return $this->maxServiceProviders; }
    public function getActiveServiceProviders(): ?int { return $this->activeServiceProviders; }
    public function getCurrentMonthlyFinishedWebhookProtocols(): ?int { return $this->currentMonthlyFinishedWebhookProtocols; }
}
