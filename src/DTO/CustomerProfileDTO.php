<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomerProfile record.
 */
final class CustomerProfileDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific customerProfile */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /**
         * customer identifiers
         * @var array<int|string, mixed>|null
         */
        private ?array $customers,
        /** name */
        private ?string $name
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
            customers: isset($data['customers']) && is_array($data['customers']) ? $data['customers'] : null,
            name: self::toString($data['name'] ?? null)
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'customers' => $this->customers,
            'name' => $this->name
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getCustomers(): ?array { return $this->customers; }
    public function getName(): ?string { return $this->name; }
}