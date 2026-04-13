<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimationSubTaskTemplate record.
 */
final class CostEstimationSubTaskTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?float $estimateBuffer,
        private ?string $description,
        private ?int $dueDateOffset,
        private ?int $dueDateTime,
        private ?float $estimate,
        private ?string $internalDescription,
        private ?string $name,
        private ?int $serviceType
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            estimateBuffer: self::toFloat($data['estimateBuffer'] ?? null),
            description: self::toString($data['description'] ?? null),
            dueDateOffset: self::toInt($data['dueDateOffset'] ?? null),
            dueDateTime: self::toInt($data['dueDateTime'] ?? null),
            estimate: self::toFloat($data['estimate'] ?? null),
            internalDescription: self::toString($data['internalDescription'] ?? null),
            name: self::toString($data['name'] ?? null),
            serviceType: self::toInt($data['serviceType'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'description' => $this->description,
            'dueDateOffset' => $this->dueDateOffset,
            'dueDateTime' => $this->dueDateTime,
            'estimate' => $this->estimate,
            'internalDescription' => $this->internalDescription,
            'name' => $this->name,
            'serviceType' => $this->serviceType
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getEstimateBuffer(): ?float { return $this->estimateBuffer; }
    public function getDescription(): ?string { return $this->description; }
    public function getDueDateOffset(): ?int { return $this->dueDateOffset; }
    public function getDueDateTime(): ?int { return $this->dueDateTime; }
    public function getEstimate(): ?float { return $this->estimate; }
    public function getInternalDescription(): ?string { return $this->internalDescription; }
    public function getName(): ?string { return $this->name; }
    public function getServiceType(): ?int { return $this->serviceType; }
}