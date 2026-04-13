<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AgreementComponentTemplate record.
 */
final class AgreementComponentTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific component template */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** inclusive */
        private ?bool $inclusive,
        /** serviceType identifier */
        private ?int $serviceType,
        /** sla */
        private ?int $sla,
        /** withinWorkingSla */
        private ?bool $withinWorkingSla
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            inclusive: isset($data['inclusive']) ? self::toBool($data['inclusive']) : null,
            serviceType: self::toInt($data['serviceType'] ?? null),
            sla: self::toInt($data['sla'] ?? null),
            withinWorkingSla: isset($data['withinWorkingSla']) ? self::toBool($data['withinWorkingSla']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'inclusive' => $this->inclusive,
            'serviceType' => $this->serviceType,
            'sla' => $this->sla,
            'withinWorkingSla' => $this->withinWorkingSla
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getInclusive(): ?bool { return $this->inclusive; }
    public function getServiceType(): ?int { return $this->serviceType; }
    public function getSla(): ?int { return $this->sla; }
    public function getWithinWorkingSla(): ?bool { return $this->withinWorkingSla; }
}