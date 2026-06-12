<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeDocumentTemplate record.
 */
final class DocBeeDocumentTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific docBeeDocumentTemplate */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /**
         * taskTemplate identifiers
         * @var array<int|string, mixed>|null
         */
        private readonly ?array $taskTemplates,
        /**
         * travelLogTemplate identifiers
         * @var array<int|string, mixed>|null
         */
        private readonly ?array $travelLogTemplates,
        /**
         * worker identifiers
         * @var array<int|string, mixed>|null
         */
        private readonly ?array $workers,
        /** billable */
        private ?bool $billable,
        /** completedSuccessfully */
        private ?bool $completedSuccessfully,
        /** confidentialTag identifier */
        private ?int $confidentialTag,
        /**
         * list of customFieldValues
         * @var array<int, CustomFieldValueDTO>|null
         */
        private ?array $customFields,
        /** customer identifier */
        private ?int $customer,
        /** customerContact identifier */
        private ?int $customerContact,
        /** customerLocation identifier */
        private ?int $customerLocation,
        /** erpReferenceNumber */
        private ?string $erpReferenceNumber,
        /** externalReferenceNumber */
        private ?string $externalReferenceNumber,
        /** isDraft */
        private ?bool $isDraft,
        /** name */
        private ?string $name,
        /** needFinishPin */
        private ?bool $needFinishPin,
        /** needSignature */
        private ?bool $needSignature,
        /** user or queue identifier */
        private ?int $personInCharge,
        /** priority identifier */
        private ?int $priority,
        /**
         * protocolDocumentTemplate identifiers
         * @var array<int|string, mixed>|null
         */
        private ?array $protocolDocumentTemplates,
        /** record travel time after prefinish */
        private ?bool $recordTravelTimeAfterPreFinished,
        /** referenceNumber */
        private ?string $referenceNumber,
        /** release offset in days of the to be created docBeeDocument draft */
        private ?int $releaseOffset,
        /** sendMessage */
        private ?bool $sendMessage,
        /**
         * tag identifiers
         * @var array<int|string, mixed>|null
         */
        private ?array $tags
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    #[\Override]
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
            customFields: isset($data['customFields']) && is_array($data['customFields'])
                // Same dual-shape handling as DocBeeDocumentDTO:
                //   ?fields=customFields            → [102, 104]              (IDs only)
                //   ?fields=customFields.id,…value  → [{id:102,value:…}, …]   (full objects)
                ? array_values(array_filter(
                    array_map(
                        fn($x) => is_array($x) ? CustomFieldValueDTO::fromArray($x) : null,
                        $data['customFields']
                    ),
                    fn($v) => $v !== null
                ))
                : null,
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

    /**
     * @return array<string, mixed>
     */
    #[\Override]
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
    /** @return array<int|string, mixed>|null */
    public function getTaskTemplates(): ?array { return $this->taskTemplates; }
    /** @return array<int|string, mixed>|null */
    public function getTravelLogTemplates(): ?array { return $this->travelLogTemplates; }
    /** @return array<int|string, mixed>|null */
    public function getWorkers(): ?array { return $this->workers; }
    public function getBillable(): ?bool { return $this->billable; }
    public function getCompletedSuccessfully(): ?bool { return $this->completedSuccessfully; }
    public function getConfidentialTag(): ?int { return $this->confidentialTag; }
    /** @return array<int, CustomFieldValueDTO>|null */
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
    /** @return array<int|string, mixed>|null */
    public function getProtocolDocumentTemplates(): ?array { return $this->protocolDocumentTemplates; }
    public function getRecordTravelTimeAfterPreFinished(): ?bool { return $this->recordTravelTimeAfterPreFinished; }
    public function getReferenceNumber(): ?string { return $this->referenceNumber; }
    public function getReleaseOffset(): ?int { return $this->releaseOffset; }
    public function getSendMessage(): ?bool { return $this->sendMessage; }
    /** @return array<int|string, mixed>|null */
    public function getTags(): ?array { return $this->tags; }
}