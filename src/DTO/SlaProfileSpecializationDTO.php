<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee SlaProfileSpecialization record.
 */
final class SlaProfileSpecializationDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific slaSpecialization */
        private readonly ?int $id,
        /** department identifier */
        private ?int $department,
        /** name */
        private ?string $name,
        /** priority identifier */
        private ?int $priority,
        /** sla */
        private ?float $sla,
        /** ticket status identifiers */
        private ?array $targetTicketStatuses,
        /** ticketCategory identifier */
        private ?int $ticketCategory,
        /** useDefaultTargetTicketStatuses */
        private ?bool $useDefaultTargetTicketStatuses,
        /** weight */
        private ?float $weight
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            department: self::toInt($data['department'] ?? null),
            name: self::toString($data['name'] ?? null),
            priority: self::toInt($data['priority'] ?? null),
            sla: self::toFloat($data['sla'] ?? null),
            targetTicketStatuses: isset($data['targetTicketStatuses']) && is_array($data['targetTicketStatuses']) ? $data['targetTicketStatuses'] : null,
            ticketCategory: self::toInt($data['ticketCategory'] ?? null),
            useDefaultTargetTicketStatuses: isset($data['useDefaultTargetTicketStatuses']) ? self::toBool($data['useDefaultTargetTicketStatuses']) : null,
            weight: self::toFloat($data['weight'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'department' => $this->department,
            'name' => $this->name,
            'priority' => $this->priority,
            'sla' => $this->sla,
            'targetTicketStatuses' => $this->targetTicketStatuses,
            'ticketCategory' => $this->ticketCategory,
            'useDefaultTargetTicketStatuses' => $this->useDefaultTargetTicketStatuses,
            'weight' => $this->weight
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getDepartment(): ?int { return $this->department; }
    public function getName(): ?string { return $this->name; }
    public function getPriority(): ?int { return $this->priority; }
    public function getSla(): ?float { return $this->sla; }
    public function getTargetTicketStatuses(): ?array { return $this->targetTicketStatuses; }
    public function getTicketCategory(): ?int { return $this->ticketCategory; }
    public function getUseDefaultTargetTicketStatuses(): ?bool { return $this->useDefaultTargetTicketStatuses; }
    public function getWeight(): ?float { return $this->weight; }
}