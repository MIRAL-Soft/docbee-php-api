<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Priority record.
 */
final class PriorityDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific priority */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** HTML color code in hexadecimal representation */
        private ?string $color,
        /** customerSelectable */
        private ?bool $customerSelectable,
        /** isDefault */
        private ?bool $isDefault,
        /** name */
        private ?string $name,
        /** Range from 0 to 100 */
        private ?int $priority,
        /** Sla in milliseconds */
        private ?int $sla
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            color: self::toString($data['color'] ?? null),
            customerSelectable: isset($data['customerSelectable']) ? self::toBool($data['customerSelectable']) : null,
            isDefault: isset($data['isDefault']) ? self::toBool($data['isDefault']) : null,
            name: self::toString($data['name'] ?? null),
            priority: self::toInt($data['priority'] ?? null),
            sla: self::toInt($data['sla'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'color' => $this->color,
            'customerSelectable' => $this->customerSelectable,
            'isDefault' => $this->isDefault,
            'name' => $this->name,
            'priority' => $this->priority,
            'sla' => $this->sla
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getColor(): ?string { return $this->color; }
    public function getCustomerSelectable(): ?bool { return $this->customerSelectable; }
    public function getIsDefault(): ?bool { return $this->isDefault; }
    public function getName(): ?string { return $this->name; }
    public function getPriority(): ?int { return $this->priority; }
    public function getSla(): ?int { return $this->sla; }
}