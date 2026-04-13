<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Ticket record.
 */
final class TicketDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?int $closedStatusChange,
        private readonly ?int $createdStatusChange,
        private readonly ?array $docBeeDocuments,
        private readonly ?bool $hasDocBeeDocuments,
        private readonly ?bool $hasProtocols,
        private readonly ?bool $hasTicketLinks,
        private readonly ?array $mergedTickets,
        private readonly ?array $protocols,
        private readonly ?int $slaProfile,
        private readonly ?array $slaReports,
        private readonly ?string $ticketNumber,
        private readonly ?string $webLink,
        private ?array $additionalData,
        private ?bool $billable,
        private ?int $confidentialTag,
        private ?array $customFields,
        private ?int $customer,
        private ?int $customerContact,
        private ?int $customerLocation,
        private ?array $customerObjects,
        private ?string $deadline,
        private ?int $department,
        private ?string $description,
        private ?string $dueDate,
        private ?string $erpReferenceNumber,
        private ?string $externalReferenceNumber,
        private ?int $externalSla,
        private ?string $externalSlaDueDate,
        private ?string $internalDescription,
        private ?array $inwardLinks,
        private ?bool $isLocked,
        private ?string $lockReason,
        private ?bool $lockVisibleForCustomer,
        private ?string $lowestDueDate,
        private ?int $mergedToTicket,
        private ?array $outwardLinks,
        private ?int $owner,
        private ?int $priority,
        private ?int $project,
        private ?string $referenceNumber,
        private ?int $sla,
        private ?string $slaDueDate,
        private ?int $slaRunning,
        private ?string $startDate,
        private ?array $tags,
        private ?int $ticketCategory,
        private ?int $ticketStatus
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            closedStatusChange: self::toInt($data['closedStatusChange'] ?? null),
            createdStatusChange: self::toInt($data['createdStatusChange'] ?? null),
            docBeeDocuments: isset($data['docBeeDocuments']) && is_array($data['docBeeDocuments']) ? $data['docBeeDocuments'] : null,
            hasDocBeeDocuments: isset($data['hasDocBeeDocuments']) ? self::toBool($data['hasDocBeeDocuments']) : null,
            hasProtocols: isset($data['hasProtocols']) ? self::toBool($data['hasProtocols']) : null,
            hasTicketLinks: isset($data['hasTicketLinks']) ? self::toBool($data['hasTicketLinks']) : null,
            mergedTickets: isset($data['mergedTickets']) && is_array($data['mergedTickets']) ? $data['mergedTickets'] : null,
            protocols: isset($data['protocols']) && is_array($data['protocols']) ? $data['protocols'] : null,
            slaProfile: self::toInt($data['slaProfile'] ?? null),
            slaReports: isset($data['slaReports']) && is_array($data['slaReports']) ? $data['slaReports'] : null,
            ticketNumber: self::toString($data['ticketNumber'] ?? null),
            webLink: self::toString($data['webLink'] ?? null),
            additionalData: isset($data['additionalData']) && is_array($data['additionalData']) ? $data['additionalData'] : null,
            billable: isset($data['billable']) ? self::toBool($data['billable']) : null,
            confidentialTag: self::toInt($data['confidentialTag'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            deadline: self::toString($data['deadline'] ?? null),
            department: self::toInt($data['department'] ?? null),
            description: self::toString($data['description'] ?? null),
            dueDate: self::toString($data['dueDate'] ?? null),
            erpReferenceNumber: self::toString($data['erpReferenceNumber'] ?? null),
            externalReferenceNumber: self::toString($data['externalReferenceNumber'] ?? null),
            externalSla: self::toInt($data['externalSla'] ?? null),
            externalSlaDueDate: self::toString($data['externalSlaDueDate'] ?? null),
            internalDescription: self::toString($data['internalDescription'] ?? null),
            inwardLinks: isset($data['inwardLinks']) && is_array($data['inwardLinks']) ? $data['inwardLinks'] : null,
            isLocked: isset($data['isLocked']) ? self::toBool($data['isLocked']) : null,
            lockReason: self::toString($data['lockReason'] ?? null),
            lockVisibleForCustomer: isset($data['lockVisibleForCustomer']) ? self::toBool($data['lockVisibleForCustomer']) : null,
            lowestDueDate: self::toString($data['lowestDueDate'] ?? null),
            mergedToTicket: self::toInt($data['mergedToTicket'] ?? null),
            outwardLinks: isset($data['outwardLinks']) && is_array($data['outwardLinks']) ? $data['outwardLinks'] : null,
            owner: self::toInt($data['owner'] ?? null),
            priority: self::toInt($data['priority'] ?? null),
            project: self::toInt($data['project'] ?? null),
            referenceNumber: self::toString($data['referenceNumber'] ?? null),
            sla: self::toInt($data['sla'] ?? null),
            slaDueDate: self::toString($data['slaDueDate'] ?? null),
            slaRunning: self::toInt($data['slaRunning'] ?? null),
            startDate: self::toString($data['startDate'] ?? null),
            tags: isset($data['tags']) && is_array($data['tags']) ? $data['tags'] : null,
            ticketCategory: self::toInt($data['ticketCategory'] ?? null),
            ticketStatus: self::toInt($data['ticketStatus'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'additionalData' => $this->additionalData,
            'billable' => $this->billable,
            'confidentialTag' => $this->confidentialTag,
            'customFields' => $this->customFields,
            'customer' => $this->customer,
            'customerContact' => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'customerObjects' => $this->customerObjects,
            'deadline' => $this->deadline,
            'department' => $this->department,
            'description' => $this->description,
            'dueDate' => $this->dueDate,
            'erpReferenceNumber' => $this->erpReferenceNumber,
            'externalReferenceNumber' => $this->externalReferenceNumber,
            'externalSla' => $this->externalSla,
            'externalSlaDueDate' => $this->externalSlaDueDate,
            'internalDescription' => $this->internalDescription,
            'inwardLinks' => $this->inwardLinks,
            'isLocked' => $this->isLocked,
            'lockReason' => $this->lockReason,
            'lockVisibleForCustomer' => $this->lockVisibleForCustomer,
            'lowestDueDate' => $this->lowestDueDate,
            'mergedToTicket' => $this->mergedToTicket,
            'outwardLinks' => $this->outwardLinks,
            'owner' => $this->owner,
            'priority' => $this->priority,
            'project' => $this->project,
            'referenceNumber' => $this->referenceNumber,
            'sla' => $this->sla,
            'slaDueDate' => $this->slaDueDate,
            'slaRunning' => $this->slaRunning,
            'startDate' => $this->startDate,
            'tags' => $this->tags,
            'ticketCategory' => $this->ticketCategory,
            'ticketStatus' => $this->ticketStatus
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getClosedStatusChange(): ?int { return $this->closedStatusChange; }
    public function getCreatedStatusChange(): ?int { return $this->createdStatusChange; }
    public function getDocBeeDocuments(): ?array { return $this->docBeeDocuments; }
    public function getHasDocBeeDocuments(): ?bool { return $this->hasDocBeeDocuments; }
    public function getHasProtocols(): ?bool { return $this->hasProtocols; }
    public function getHasTicketLinks(): ?bool { return $this->hasTicketLinks; }
    public function getMergedTickets(): ?array { return $this->mergedTickets; }
    public function getProtocols(): ?array { return $this->protocols; }
    public function getSlaProfile(): ?int { return $this->slaProfile; }
    public function getSlaReports(): ?array { return $this->slaReports; }
    public function getTicketNumber(): ?string { return $this->ticketNumber; }
    public function getWebLink(): ?string { return $this->webLink; }
    public function getAdditionalData(): ?array { return $this->additionalData; }
    public function getBillable(): ?bool { return $this->billable; }
    public function getConfidentialTag(): ?int { return $this->confidentialTag; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function getDeadline(): ?string { return $this->deadline; }
    public function getDepartment(): ?int { return $this->department; }
    public function getDescription(): ?string { return $this->description; }
    public function getDueDate(): ?string { return $this->dueDate; }
    public function getErpReferenceNumber(): ?string { return $this->erpReferenceNumber; }
    public function getExternalReferenceNumber(): ?string { return $this->externalReferenceNumber; }
    public function getExternalSla(): ?int { return $this->externalSla; }
    public function getExternalSlaDueDate(): ?string { return $this->externalSlaDueDate; }
    public function getInternalDescription(): ?string { return $this->internalDescription; }
    public function getInwardLinks(): ?array { return $this->inwardLinks; }
    public function getIsLocked(): ?bool { return $this->isLocked; }
    public function getLockReason(): ?string { return $this->lockReason; }
    public function getLockVisibleForCustomer(): ?bool { return $this->lockVisibleForCustomer; }
    public function getLowestDueDate(): ?string { return $this->lowestDueDate; }
    public function getMergedToTicket(): ?int { return $this->mergedToTicket; }
    public function getOutwardLinks(): ?array { return $this->outwardLinks; }
    public function getOwner(): ?int { return $this->owner; }
    public function getPriority(): ?int { return $this->priority; }
    public function getProject(): ?int { return $this->project; }
    public function getReferenceNumber(): ?string { return $this->referenceNumber; }
    public function getSla(): ?int { return $this->sla; }
    public function getSlaDueDate(): ?string { return $this->slaDueDate; }
    public function getSlaRunning(): ?int { return $this->slaRunning; }
    public function getStartDate(): ?string { return $this->startDate; }
    public function getTags(): ?array { return $this->tags; }
    public function getTicketCategory(): ?int { return $this->ticketCategory; }
    public function getTicketStatus(): ?int { return $this->ticketStatus; }
}