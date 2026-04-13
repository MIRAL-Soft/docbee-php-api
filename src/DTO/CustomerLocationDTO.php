<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomerLocation record.
 */
final class CustomerLocationDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?int $customer,
        private readonly ?bool $temporary,
        private ?string $city,
        private ?array $customFields,
        private ?string $name,
        private ?string $street,
        private ?bool $syncToApp,
        private ?string $zipcode
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            temporary: isset($data['temporary']) ? self::toBool($data['temporary']) : null,
            city: self::toString($data['city'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            name: self::toString($data['name'] ?? null),
            street: self::toString($data['street'] ?? null),
            syncToApp: isset($data['syncToApp']) ? self::toBool($data['syncToApp']) : null,
            zipcode: self::toString($data['zipcode'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'city' => $this->city,
            'customFields' => $this->customFields,
            'name' => $this->name,
            'street' => $this->street,
            'syncToApp' => $this->syncToApp,
            'zipcode' => $this->zipcode
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getTemporary(): ?bool { return $this->temporary; }
    public function getCity(): ?string { return $this->city; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getName(): ?string { return $this->name; }
    public function getStreet(): ?string { return $this->street; }
    public function isSyncToApp(): ?bool { return $this->syncToApp; }
    public function getZipcode(): ?string { return $this->zipcode; }
}