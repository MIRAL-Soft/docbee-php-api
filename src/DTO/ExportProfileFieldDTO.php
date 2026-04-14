<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ExportProfileField record.
 */
final class ExportProfileFieldDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier */
        private readonly ?int    $id,
        /** REST API Link */
        private readonly ?string $link,
        /** data formatter name */
        private ?string $dataFormatterName,
        /** domain name */
        private ?string $domainName,
        /** property name */
        private ?string $propertyName,
        /** property sortable */
        private ?string $propertySortable,
        /** domain */
        private ?string $domain,
        /** property */
        private ?string $property,
        /** value */
        private ?string $value,
        /** header name */
        private ?string $headerName,
        /** data formatter */
        private ?string $dataFormatter,
        /** sort */
        private ?string $sort,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                self::toInt($data['id'] ?? null),
            link:              self::toString($data['link'] ?? null),
            dataFormatterName: self::toString($data['dataFormatterName'] ?? null),
            domainName:        self::toString($data['domainName'] ?? null),
            propertyName:      self::toString($data['propertyName'] ?? null),
            propertySortable:  self::toString($data['propertySortable'] ?? null),
            domain:            self::toString($data['domain'] ?? null),
            property:          self::toString($data['property'] ?? null),
            value:             self::toString($data['value'] ?? null),
            headerName:        self::toString($data['headerName'] ?? null),
            dataFormatter:     self::toString($data['dataFormatter'] ?? null),
            sort:              self::toString($data['sort'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'domain'            => $this->domain,
            'property'          => $this->property,
            'value'             => $this->value,
            'headerName'        => $this->headerName,
            'dataFormatter'     => $this->dataFormatter,
            'sort'              => $this->sort,
            'dataFormatterName' => $this->dataFormatterName,
            'domainName'        => $this->domainName,
            'propertyName'      => $this->propertyName,
            'propertySortable'  => $this->propertySortable,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int                  { return $this->id; }
    public function getLink(): ?string             { return $this->link; }
    public function getDataFormatterName(): ?string { return $this->dataFormatterName; }
    public function getDomainName(): ?string        { return $this->domainName; }
    public function getPropertyName(): ?string      { return $this->propertyName; }
    public function getPropertySortable(): ?string  { return $this->propertySortable; }
    public function getDomain(): ?string            { return $this->domain; }
    public function getProperty(): ?string          { return $this->property; }
    public function getValue(): ?string             { return $this->value; }
    public function getHeaderName(): ?string        { return $this->headerName; }
    public function getDataFormatter(): ?string     { return $this->dataFormatter; }
    public function getSort(): ?string              { return $this->sort; }
}
