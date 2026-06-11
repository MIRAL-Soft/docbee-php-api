<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ProtocolDocumentTemplate record.
 */
final class ProtocolDocumentTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocolTemplate */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** name */
        private readonly ?string $name,
        /** protocolTemplate Id */
        private readonly ?int $protocolTemplate,
        /** confidentialTag identifier */
        private ?int $confidentialTag,
        /** customer identifier */
        private ?int $customer,
        /** contact identifier */
        private ?int $customerContact,
        /** location identifier */
        private ?int $customerLocation,
        /** customerObject identifiers */
        private ?array $customerObjects,
        /** time added to the creation date of the protocol to determine the final due date */
        private ?int $dueDate,
        /** personInCharge identifier */
        private ?int $personInCharge,
        /** tags identifier */
        private ?int $tags
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            name: self::toString($data['name'] ?? null),
            protocolTemplate: self::toInt($data['protocolTemplate'] ?? null),
            confidentialTag: self::toInt($data['confidentialTag'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            dueDate: self::toInt($data['dueDate'] ?? null),
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            tags: self::toInt($data['tags'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'confidentialTag' => $this->confidentialTag,
            'customer' => $this->customer,
            'customerContact' => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'customerObjects' => $this->customerObjects,
            'dueDate' => $this->dueDate,
            'personInCharge' => $this->personInCharge,
            'tags' => $this->tags
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getName(): ?string { return $this->name; }
    public function getProtocolTemplate(): ?int { return $this->protocolTemplate; }
    public function getConfidentialTag(): ?int { return $this->confidentialTag; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function getDueDate(): ?int { return $this->dueDate; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getTags(): ?int { return $this->tags; }
}