<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Dashboard record.
 */
final class DashboardDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific dashboard */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** forcedSubscribed */
        private ?bool $forcedSubscribed,
        /** name */
        private ?string $name,
        /** shared */
        private ?bool $shared,
        /** user id */
        private ?int $user,
        /** @var DashboardWidgetDTO[]|null list of dashboard widgets */
        private ?array $widgets
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            forcedSubscribed: isset($data['forcedSubscribed']) ? self::toBool($data['forcedSubscribed']) : null,
            name: self::toString($data['name'] ?? null),
            shared: isset($data['shared']) ? self::toBool($data['shared']) : null,
            user: self::toInt($data['user'] ?? null),
            widgets: self::toDtoList($data['widgets'] ?? null, DashboardWidgetDTO::class)
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
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
    /**
     * @return list<DashboardWidgetDTO>|null
     */
    public function getWidgets(): ?array { return $this->widgets; }
}