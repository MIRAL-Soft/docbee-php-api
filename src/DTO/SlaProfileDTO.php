<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SlaProfile record.
 */
final class SlaProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific slaProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** defaultSla in milliseconds */
        private ?int $defaultSla,
        /** sla scope */
        private ?string $scope,
        /** setDueDate */
        private ?bool $setDueDate,
        /** @var list<mixed>|null specializations */
        private ?array $specializations,
        /** @var list<int>|null ticket status identifiers */
        private ?array $targetTicketStatuses,
        /** @var list<mixed>|null workingHours */
        private ?array $workingHours
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            defaultSla: self::toInt($data['defaultSla'] ?? null),
            scope: self::toString($data['scope'] ?? null),
            setDueDate: isset($data['setDueDate']) ? self::toBool($data['setDueDate']) : null,
            specializations: isset($data['specializations']) && is_array($data['specializations']) ? $data['specializations'] : null,
            targetTicketStatuses: isset($data['targetTicketStatuses']) && is_array($data['targetTicketStatuses']) ? $data['targetTicketStatuses'] : null,
            workingHours: isset($data['workingHours']) && is_array($data['workingHours']) ? $data['workingHours'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'defaultSla' => $this->defaultSla,
            'scope' => $this->scope,
            'setDueDate' => $this->setDueDate,
            'specializations' => $this->specializations,
            'targetTicketStatuses' => $this->targetTicketStatuses,
            'workingHours' => $this->workingHours
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getDefaultSla(): ?int { return $this->defaultSla; }
    public function getScope(): ?string { return $this->scope; }
    public function getSetDueDate(): ?bool { return $this->setDueDate; }
    /** @return list<mixed>|null */
    public function getSpecializations(): ?array { return $this->specializations; }
    /** @return list<int>|null */
    public function getTargetTicketStatuses(): ?array { return $this->targetTicketStatuses; }
    /** @return list<mixed>|null */
    public function getWorkingHours(): ?array { return $this->workingHours; }
}