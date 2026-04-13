<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TicketTemplate record.
 */
final class TicketTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private ?int $customer,
        private ?int $customerContact,
        private ?int $customerLocation,
        private ?array $customerObjects,
        private ?bool $customerSelectable,
        private ?int $deadline,
        private ?string $description,
        private ?int $dueDate,
        private ?string $internalDescription,
        private ?string $name,
        private ?int $owner,
        private ?int $priority,
        private ?array $tags,
        private ?int $ticketCategory,
        private ?int $ticketStatus
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            customerSelectable: isset($data['customerSelectable']) ? self::toBool($data['customerSelectable']) : null,
            deadline: self::toInt($data['deadline'] ?? null),
            description: self::toString($data['description'] ?? null),
            dueDate: self::toInt($data['dueDate'] ?? null),
            internalDescription: self::toString($data['internalDescription'] ?? null),
            name: self::toString($data['name'] ?? null),
            owner: self::toInt($data['owner'] ?? null),
            priority: self::toInt($data['priority'] ?? null),
            tags: isset($data['tags']) && is_array($data['tags']) ? $data['tags'] : null,
            ticketCategory: self::toInt($data['ticketCategory'] ?? null),
            ticketStatus: self::toInt($data['ticketStatus'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customer' => $this->customer,
            'customerContact' => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'customerObjects' => $this->customerObjects,
            'customerSelectable' => $this->customerSelectable,
            'deadline' => $this->deadline,
            'description' => $this->description,
            'dueDate' => $this->dueDate,
            'internalDescription' => $this->internalDescription,
            'name' => $this->name,
            'owner' => $this->owner,
            'priority' => $this->priority,
            'tags' => $this->tags,
            'ticketCategory' => $this->ticketCategory,
            'ticketStatus' => $this->ticketStatus
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function getCustomerSelectable(): ?bool { return $this->customerSelectable; }
    public function getDeadline(): ?int { return $this->deadline; }
    public function getDescription(): ?string { return $this->description; }
    public function getDueDate(): ?int { return $this->dueDate; }
    public function getInternalDescription(): ?string { return $this->internalDescription; }
    public function getName(): ?string { return $this->name; }
    public function getOwner(): ?int { return $this->owner; }
    public function getPriority(): ?int { return $this->priority; }
    public function getTags(): ?array { return $this->tags; }
    public function getTicketCategory(): ?int { return $this->ticketCategory; }
    public function getTicketStatus(): ?int { return $this->ticketStatus; }
}