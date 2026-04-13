<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocumentTemplate record.
 */
final class DocumentTemplateDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?array $taskTemplates,
        private readonly ?array $travelLogTemplates,
        private readonly ?array $workers,
        private ?bool $billable,
        private ?bool $completedSuccessfully,
        private ?int $confidentialTag,
        private ?array $customFields,
        private ?int $customer,
        private ?int $customerContact,
        private ?int $customerLocation,
        private ?string $erpReferenceNumber,
        private ?string $externalReferenceNumber,
        private ?bool $isDraft,
        private ?string $name,
        private ?bool $needFinishPin,
        private ?bool $needSignature,
        private ?int $personInCharge,
        private ?int $priority,
        private ?array $protocolDocumentTemplates,
        private ?bool $recordTravelTimeAfterPreFinished,
        private ?string $referenceNumber,
        private ?int $releaseOffset,
        private ?bool $sendMessage,
        private ?array $tags
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            taskTemplates: isset($data['taskTemplates']) && is_array($data['taskTemplates']) ? $data['taskTemplates'] : null,
            travelLogTemplates: isset($data['travelLogTemplates']) && is_array($data['travelLogTemplates']) ? $data['travelLogTemplates'] : null,
            workers: isset($data['workers']) && is_array($data['workers']) ? $data['workers'] : null,
            billable: isset($data['billable']) ? self::toBool($data['billable']) : null,
            completedSuccessfully: isset($data['completedSuccessfully']) ? self::toBool($data['completedSuccessfully']) : null,
            confidentialTag: self::toInt($data['confidentialTag'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            erpReferenceNumber: self::toString($data['erpReferenceNumber'] ?? null),
            externalReferenceNumber: self::toString($data['externalReferenceNumber'] ?? null),
            isDraft: isset($data['isDraft']) ? self::toBool($data['isDraft']) : null,
            name: self::toString($data['name'] ?? null),
            needFinishPin: isset($data['needFinishPin']) ? self::toBool($data['needFinishPin']) : null,
            needSignature: isset($data['needSignature']) ? self::toBool($data['needSignature']) : null,
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            priority: self::toInt($data['priority'] ?? null),
            protocolDocumentTemplates: isset($data['protocolDocumentTemplates']) && is_array($data['protocolDocumentTemplates']) ? $data['protocolDocumentTemplates'] : null,
            recordTravelTimeAfterPreFinished: isset($data['recordTravelTimeAfterPreFinished']) ? self::toBool($data['recordTravelTimeAfterPreFinished']) : null,
            referenceNumber: self::toString($data['referenceNumber'] ?? null),
            releaseOffset: self::toInt($data['releaseOffset'] ?? null),
            sendMessage: isset($data['sendMessage']) ? self::toBool($data['sendMessage']) : null,
            tags: isset($data['tags']) && is_array($data['tags']) ? $data['tags'] : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'billable' => $this->billable,
            'completedSuccessfully' => $this->completedSuccessfully,
            'confidentialTag' => $this->confidentialTag,
            'customFields' => $this->customFields,
            'customer' => $this->customer,
            'customerContact' => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'erpReferenceNumber' => $this->erpReferenceNumber,
            'externalReferenceNumber' => $this->externalReferenceNumber,
            'isDraft' => $this->isDraft,
            'name' => $this->name,
            'needFinishPin' => $this->needFinishPin,
            'needSignature' => $this->needSignature,
            'personInCharge' => $this->personInCharge,
            'priority' => $this->priority,
            'protocolDocumentTemplates' => $this->protocolDocumentTemplates,
            'recordTravelTimeAfterPreFinished' => $this->recordTravelTimeAfterPreFinished,
            'referenceNumber' => $this->referenceNumber,
            'releaseOffset' => $this->releaseOffset,
            'sendMessage' => $this->sendMessage,
            'tags' => $this->tags
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getTaskTemplates(): ?array { return $this->taskTemplates; }
    public function getTravelLogTemplates(): ?array { return $this->travelLogTemplates; }
    public function getWorkers(): ?array { return $this->workers; }
    public function getBillable(): ?bool { return $this->billable; }
    public function getCompletedSuccessfully(): ?bool { return $this->completedSuccessfully; }
    public function getConfidentialTag(): ?int { return $this->confidentialTag; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getErpReferenceNumber(): ?string { return $this->erpReferenceNumber; }
    public function getExternalReferenceNumber(): ?string { return $this->externalReferenceNumber; }
    public function getIsDraft(): ?bool { return $this->isDraft; }
    public function getName(): ?string { return $this->name; }
    public function getNeedFinishPin(): ?bool { return $this->needFinishPin; }
    public function getNeedSignature(): ?bool { return $this->needSignature; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getPriority(): ?int { return $this->priority; }
    public function getProtocolDocumentTemplates(): ?array { return $this->protocolDocumentTemplates; }
    public function getRecordTravelTimeAfterPreFinished(): ?bool { return $this->recordTravelTimeAfterPreFinished; }
    public function getReferenceNumber(): ?string { return $this->referenceNumber; }
    public function getReleaseOffset(): ?int { return $this->releaseOffset; }
    public function getSendMessage(): ?bool { return $this->sendMessage; }
    public function getTags(): ?array { return $this->tags; }
}