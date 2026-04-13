<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomerLocation record.
 */
final class CustomerLocationDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific customerLocation */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** customer identifier */
        private readonly ?int $customer,
        /** If these location is temporary */
        private readonly ?bool $temporary,
        /** city */
        private ?string $city,
        /** @var CustomFieldValueDTO[]|null list of customFieldValues */
        private ?array $customFields,
        /** name */
        private ?string $name,
        /** street */
        private ?string $street,
        /** If this contact is synced to app. The customer setting withSyncToAppFlag needs to be set to use this property. Otherwise all contacts are synced. */
        private ?bool $syncToApp,
        /** zipcode */
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
            customFields: isset($data['customFields']) && is_array($data['customFields'])
                ? array_map(fn($x) => CustomFieldValueDTO::fromArray($x), $data['customFields'])
                : null,
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