<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Dashboard record.
 */
final class DashboardDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $forcedSubscribed,
        private readonly ?int $user,
        private readonly array $widgets,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            forcedSubscribed: self::toBool($data['forcedSubscribed'] ?? false),
            user: self::toInt($data['user'] ?? null),
            widgets: $data['widgets'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'forcedSubscribed' => $this->forcedSubscribed,
            'user' => $this->user,
            'widgets' => $this->widgets,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isForcedSubscribed(): bool { return $this->forcedSubscribed; }
    public function getUser(): ?int { return $this->user; }
    public function getWidgets(): array { return $this->widgets; }
}