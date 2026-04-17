<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee MaterialTemplate entry (template variant of Material).
 *
 * Differs from MaterialDTO: does not have the additionalData field.
 */
final class MaterialTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific materialTemplate */
        private readonly ?int    $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** amount */
        private ?float           $amount,
        /** materialItem identifier */
        private ?int             $materialItem,
        /** name */
        private ?string          $name,
        /** serial number */
        private ?string          $serial,
        /** storage location */
        private ?string          $storageLocation,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:              self::toInt($data['id'] ?? null),
            modified:        self::toString($data['modified'] ?? null),
            link:            self::toString($data['link'] ?? null),
            amount:          isset($data['amount']) ? (float) $data['amount'] : null,
            materialItem:    self::toInt($data['materialItem'] ?? null),
            name:            self::toString($data['name'] ?? null),
            serial:          self::toString($data['serial'] ?? null),
            storageLocation: self::toString($data['storageLocation'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'amount'          => $this->amount,
            'materialItem'    => $this->materialItem,
            'name'            => $this->name,
            'serial'          => $this->serial,
            'storageLocation' => $this->storageLocation,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int              { return $this->id; }
    public function getModified(): ?string     { return $this->modified; }
    public function getLink(): ?string         { return $this->link; }
    public function getAmount(): ?float        { return $this->amount; }
    public function getMaterialItem(): ?int    { return $this->materialItem; }
    public function getName(): ?string         { return $this->name; }
    public function getSerial(): ?string       { return $this->serial; }
    public function getStorageLocation(): ?string { return $this->storageLocation; }
}
