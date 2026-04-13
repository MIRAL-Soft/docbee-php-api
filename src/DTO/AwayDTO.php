<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Away record.
 */
final class AwayDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?int $awayReason,
        private ?string $endDate,
        private ?bool $isPeriod,
        private ?string $startDate,
        private ?int $substitutionUser,
        private ?int $user
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            awayReason: self::toInt($data['awayReason'] ?? null),
            endDate: self::toString($data['endDate'] ?? null),
            isPeriod: isset($data['isPeriod']) ? self::toBool($data['isPeriod']) : null,
            startDate: self::toString($data['startDate'] ?? null),
            substitutionUser: self::toInt($data['substitutionUser'] ?? null),
            user: self::toInt($data['user'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'awayReason' => $this->awayReason,
            'endDate' => $this->endDate,
            'isPeriod' => $this->isPeriod,
            'startDate' => $this->startDate,
            'substitutionUser' => $this->substitutionUser,
            'user' => $this->user
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getAwayReason(): ?int { return $this->awayReason; }
    public function getEndDate(): ?string { return $this->endDate; }
    public function getIsPeriod(): ?bool { return $this->isPeriod; }
    public function getStartDate(): ?string { return $this->startDate; }
    public function getSubstitutionUser(): ?int { return $this->substitutionUser; }
    public function getUser(): ?int { return $this->user; }
}