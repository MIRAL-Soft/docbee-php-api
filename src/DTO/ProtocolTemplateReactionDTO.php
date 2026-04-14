<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolTemplateReaction record.
 */
final class ProtocolTemplateReactionDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplateReaction */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** domainName */
        private readonly ?string $domainName,
        /** propertyName */
        private readonly ?string $propertyName,
        /** propertyType */
        private readonly ?string $propertyType,
        /** type */
        private ?string $type,
        /** value */
        private ?string $value,
        /** data */
        private ?string $data,
        /** domain */
        private ?string $domain,
        /** property */
        private ?string $property
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            domainName: self::toString($data['domainName'] ?? null),
            propertyName: self::toString($data['propertyName'] ?? null),
            propertyType: self::toString($data['propertyType'] ?? null),
            type: self::toString($data['type'] ?? null),
            value: self::toString($data['value'] ?? null),
            data: self::toString($data['data'] ?? null),
            domain: self::toString($data['domain'] ?? null),
            property: self::toString($data['property'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'value' => $this->value,
            'data' => $this->data,
            'domain' => $this->domain,
            'property' => $this->property
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getDomainName(): ?string { return $this->domainName; }
    public function getPropertyName(): ?string { return $this->propertyName; }
    public function getPropertyType(): ?string { return $this->propertyType; }
    public function getType(): ?string { return $this->type; }
    public function getValue(): ?string { return $this->value; }
    public function getData(): ?string { return $this->data; }
    public function getDomain(): ?string { return $this->domain; }
    public function getProperty(): ?string { return $this->property; }
}
