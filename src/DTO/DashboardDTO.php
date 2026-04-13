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
        private readonly ?string $link,
        private ?bool $forcedSubscribed,
        private ?string $name,
        private ?bool $shared,
        private ?int $user,
        private ?array $widgets
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            forcedSubscribed: isset($data['forcedSubscribed']) ? self::toBool($data['forcedSubscribed']) : null,
            name: self::toString($data['name'] ?? null),
            shared: isset($data['shared']) ? self::toBool($data['shared']) : null,
            user: self::toInt($data['user'] ?? null),
            widgets: isset($data['widgets']) && is_array($data['widgets']) ? $data['widgets'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'forcedSubscribed' => $this->forcedSubscribed,
            'name' => $this->name,
            'shared' => $this->shared,
            'user' => $this->user,
            'widgets' => $this->widgets
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getForcedSubscribed(): ?bool { return $this->forcedSubscribed; }
    public function getName(): ?string { return $this->name; }
    public function getShared(): ?bool { return $this->shared; }
    public function getUser(): ?int { return $this->user; }
    public function getWidgets(): ?array { return $this->widgets; }
}