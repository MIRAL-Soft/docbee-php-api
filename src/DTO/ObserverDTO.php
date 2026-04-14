<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Observer record.
 */
final class ObserverDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific observer */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** details */
        private readonly ?string $details,
        /** observer type */
        private readonly ?int $observerType,
        /** observer category */
        private readonly ?int $observerCategory,
        /** name */
        private ?string $name,
        /** email address */
        private ?string $email,
        /** telefax number */
        private ?string $telefax,
        /** message delivery method */
        private ?string $messageDelivery,
        /** list of custom fields */
        private ?array $customFields
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            details: self::toString($data['details'] ?? null),
            observerType: self::toInt($data['observerType'] ?? null),
            observerCategory: self::toInt($data['observerCategory'] ?? null),
            name: self::toString($data['name'] ?? null),
            email: self::toString($data['email'] ?? null),
            telefax: self::toString($data['telefax'] ?? null),
            messageDelivery: self::toString($data['messageDelivery'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'telefax' => $this->telefax,
            'messageDelivery' => $this->messageDelivery,
            'customFields' => $this->customFields,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getDetails(): ?string { return $this->details; }
    public function getObserverType(): ?int { return $this->observerType; }
    public function getObserverCategory(): ?int { return $this->observerCategory; }
    public function getName(): ?string { return $this->name; }
    public function getEmail(): ?string { return $this->email; }
    public function getTelefax(): ?string { return $this->telefax; }
    public function getMessageDelivery(): ?string { return $this->messageDelivery; }
    public function getCustomFields(): ?array { return $this->customFields; }
}
