<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DashboardWidget record.
 */
final class DashboardWidgetDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly bool $forcedSubscribed,
        private readonly bool $validForUser,
        private readonly ?int $user,
        private readonly mixed $viewConfiguration,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            forcedSubscribed: self::toBool($data['forcedSubscribed'] ?? false),
            validForUser: self::toBool($data['validForUser'] ?? false),
            user: self::toInt($data['user'] ?? null),
            viewConfiguration: $data['viewConfiguration'] ?? null,
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'forcedSubscribed' => $this->forcedSubscribed,
            'validForUser' => $this->validForUser,
            'user' => $this->user,
            'viewConfiguration' => $this->viewConfiguration,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function isForcedSubscribed(): bool { return $this->forcedSubscribed; }
    public function isValidForUser(): bool { return $this->validForUser; }
    public function getUser(): ?int { return $this->user; }
    public function getViewConfiguration(): mixed { return $this->viewConfiguration; }
}