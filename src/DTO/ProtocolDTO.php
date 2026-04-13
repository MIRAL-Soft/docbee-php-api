<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Protocol record.
 */
final class ProtocolDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific protocol */
        private readonly ?int $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** finished */
        private readonly ?bool $canceled,
        /** canceledDate */
        private readonly ?string $canceledDate,
        /** Protocol data can't be inherited after this date */
        private readonly ?string $endDate,
        /** finished */
        private readonly ?bool $finished,
        /** finishedDate */
        private readonly ?string $finishedDate,
        /** protocolNumber */
        private readonly ?string $protocolNumber,
        /** protocolTemplate identifier */
        private readonly ?int $protocolTemplate,
        /** serverModified date */
        private readonly ?string $serverModified,
        /** web link */
        private readonly ?string $webLink,
        /** confidentialTag identifier */
        private ?int $confidentialTag,
        /** customer identifier */
        private ?int $customer,
        /** customerContact identifier */
        private ?int $customerContact,
        /** customerLocation identifier */
        private ?int $customerLocation,
        /** customerObject identifiers */
        private ?array $customerObjects,
        /** docBeeDocument identifier */
        private ?int $docBeeDocument,
        /** dueDate */
        private ?string $dueDate,
        /** file identifier */
        private ?int $file,
        /** list of protocolGroupData */
        private ?array $groupData,
        /** @var ProtocolGroupDataDTO[]|null list of protocol groups */
        private ?array $groups,
        /** user or queue identifier */
        private ?int $personInCharge,
        /** @var ProtocolEntryDTO[]|null list of protocol entries */
        private ?array $protocolEntries,
        /** sendMessage */
        private ?bool $sendMessage,
        /** ticket identifier */
        private ?int $ticket
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            canceled: isset($data['canceled']) ? self::toBool($data['canceled']) : null,
            canceledDate: self::toString($data['canceledDate'] ?? null),
            endDate: self::toString($data['endDate'] ?? null),
            finished: isset($data['finished']) ? self::toBool($data['finished']) : null,
            finishedDate: self::toString($data['finishedDate'] ?? null),
            protocolNumber: self::toString($data['protocolNumber'] ?? null),
            protocolTemplate: self::toInt($data['protocolTemplate'] ?? null),
            serverModified: self::toString($data['serverModified'] ?? null),
            webLink: self::toString($data['webLink'] ?? null),
            confidentialTag: self::toInt($data['confidentialTag'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            docBeeDocument: self::toInt($data['docBeeDocument'] ?? null),
            dueDate: self::toString($data['dueDate'] ?? null),
            file: self::toInt($data['file'] ?? null),
            groupData: isset($data['groupData']) && is_array($data['groupData']) ? $data['groupData'] : null,
            groups: isset($data['groups']) && is_array($data['groups'])
                ? array_map(fn($x) => ProtocolGroupDataDTO::fromArray($x), $data['groups'])
                : null,
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            protocolEntries: isset($data['protocolEntries']) && is_array($data['protocolEntries'])
                ? array_map(fn($x) => ProtocolEntryDTO::fromArray($x), $data['protocolEntries'])
                : null,
            sendMessage: isset($data['sendMessage']) ? self::toBool($data['sendMessage']) : null,
            ticket: self::toInt($data['ticket'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'confidentialTag' => $this->confidentialTag,
            'customer' => $this->customer,
            'customerContact' => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'customerObjects' => $this->customerObjects,
            'docBeeDocument' => $this->docBeeDocument,
            'dueDate' => $this->dueDate,
            'file' => $this->file,
            'groupData' => $this->groupData,
            'groups' => $this->groups,
            'personInCharge' => $this->personInCharge,
            'protocolEntries' => $this->protocolEntries,
            'sendMessage' => $this->sendMessage,
            'ticket' => $this->ticket
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCanceled(): ?bool { return $this->canceled; }
    public function getCanceledDate(): ?string { return $this->canceledDate; }
    public function getEndDate(): ?string { return $this->endDate; }
    public function getFinished(): ?bool { return $this->finished; }
    public function getFinishedDate(): ?string { return $this->finishedDate; }
    public function getProtocolNumber(): ?string { return $this->protocolNumber; }
    public function getProtocolTemplate(): ?int { return $this->protocolTemplate; }
    public function getServerModified(): ?string { return $this->serverModified; }
    public function getWebLink(): ?string { return $this->webLink; }
    public function getConfidentialTag(): ?int { return $this->confidentialTag; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function getDocBeeDocument(): ?int { return $this->docBeeDocument; }
    public function getDueDate(): ?string { return $this->dueDate; }
    public function getFile(): ?int { return $this->file; }
    public function getGroupData(): ?array { return $this->groupData; }
    public function getGroups(): ?array { return $this->groups; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getProtocolEntries(): ?array { return $this->protocolEntries; }
    public function getSendMessage(): ?bool { return $this->sendMessage; }
    public function getTicket(): ?int { return $this->ticket; }
}