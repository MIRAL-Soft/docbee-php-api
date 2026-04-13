<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AwayReason record.
 */
final class AwayReasonDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $link,
        private ?bool $forwardNotifications,
        private ?string $name,
        private ?bool $requiredSubstitution,
        private ?bool $userInCc,
        private ?bool $withSubstitution
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            forwardNotifications: isset($data['forwardNotifications']) ? self::toBool($data['forwardNotifications']) : null,
            name: self::toString($data['name'] ?? null),
            requiredSubstitution: isset($data['requiredSubstitution']) ? self::toBool($data['requiredSubstitution']) : null,
            userInCc: isset($data['userInCc']) ? self::toBool($data['userInCc']) : null,
            withSubstitution: isset($data['withSubstitution']) ? self::toBool($data['withSubstitution']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'forwardNotifications' => $this->forwardNotifications,
            'name' => $this->name,
            'requiredSubstitution' => $this->requiredSubstitution,
            'userInCc' => $this->userInCc,
            'withSubstitution' => $this->withSubstitution
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getForwardNotifications(): ?bool { return $this->forwardNotifications; }
    public function getName(): ?string { return $this->name; }
    public function getRequiredSubstitution(): ?bool { return $this->requiredSubstitution; }
    public function getUserInCc(): ?bool { return $this->userInCc; }
    public function getWithSubstitution(): ?bool { return $this->withSubstitution; }
}