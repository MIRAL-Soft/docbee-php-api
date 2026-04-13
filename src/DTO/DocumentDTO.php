<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Document record.
 */
final class DocumentDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?bool $approved,
        private readonly ?string $approvedComment,
        private readonly ?string $approvedDate,
        private readonly ?bool $canChangeDocument,
        private readonly ?bool $canceled,
        private readonly ?string $canceledDate,
        private readonly ?string $documentNumber,
        private readonly ?bool $drafted,
        private readonly ?int $file,
        private readonly ?string $finishPin,
        private readonly ?bool $finished,
        private readonly ?string $finishedDate,
        private readonly ?string $invoiceNumber,
        private readonly ?bool $preFinished,
        private readonly ?array $protocols,
        private readonly ?string $releasedDate,
        private readonly ?string $serverModified,
        private readonly ?array $tasks,
        private readonly ?float $totalInternalPrice,
        private readonly ?float $totalInvoicePrice,
        private readonly ?float $totalInvoicePriceWithAgreement,
        private readonly ?float $totalPrice,
        private readonly ?float $totalTasksInternalPrice,
        private readonly ?float $totalTasksInvoicePrice,
        private readonly ?float $totalTasksInvoicePriceWithAgreement,
        private readonly ?int $totalTasksInvoiceTime,
        private readonly ?int $totalTasksInvoiceTimeWithAgreement,
        private readonly ?float $totalTasksPrice,
        private readonly ?int $totalTasksWorkingTime,
        private readonly ?float $totalTravelLogsDistance,
        private readonly ?float $totalTravelLogsInternalPrice,
        private readonly ?float $totalTravelLogsInvoicePrice,
        private readonly ?int $totalTravelLogsInvoiceTime,
        private readonly ?float $totalTravelLogsPrice,
        private readonly ?int $totalTravelLogsTime,
        private readonly ?array $travelLogs,
        private readonly ?string $webLink,
        private ?bool $billable,
        private ?bool $completedSuccessfully,
        private ?int $confidentialTag,
        private ?array $customFields,
        private ?int $customer,
        private ?int $customerContact,
        private ?int $customerLocation,
        private ?string $erpReferenceNumber,
        private ?string $externalReferenceNumber,
        private ?array $infoFiles,
        private ?bool $needFinishPin,
        private ?bool $needSignature,
        private ?int $personInCharge,
        private ?string $postedDate,
        private ?int $priority,
        private ?int $project,
        private ?bool $recordTravelTimeAfterPreFinished,
        private ?string $referenceNumber,
        private ?bool $sendMessage,
        private ?int $ticket,
        private ?array $travelLog,
        private ?bool $type
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            created: self::toString($data['created'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            approved: isset($data['approved']) ? self::toBool($data['approved']) : null,
            approvedComment: self::toString($data['approvedComment'] ?? null),
            approvedDate: self::toString($data['approvedDate'] ?? null),
            canChangeDocument: isset($data['canChangeDocument']) ? self::toBool($data['canChangeDocument']) : null,
            canceled: isset($data['canceled']) ? self::toBool($data['canceled']) : null,
            canceledDate: self::toString($data['canceledDate'] ?? null),
            documentNumber: self::toString($data['documentNumber'] ?? null),
            drafted: isset($data['drafted']) ? self::toBool($data['drafted']) : null,
            file: self::toInt($data['file'] ?? null),
            finishPin: self::toString($data['finishPin'] ?? null),
            finished: isset($data['finished']) ? self::toBool($data['finished']) : null,
            finishedDate: self::toString($data['finishedDate'] ?? null),
            invoiceNumber: self::toString($data['invoiceNumber'] ?? null),
            preFinished: isset($data['preFinished']) ? self::toBool($data['preFinished']) : null,
            protocols: isset($data['protocols']) && is_array($data['protocols']) ? $data['protocols'] : null,
            releasedDate: self::toString($data['releasedDate'] ?? null),
            serverModified: self::toString($data['serverModified'] ?? null),
            tasks: isset($data['tasks']) && is_array($data['tasks']) ? $data['tasks'] : null,
            totalInternalPrice: self::toFloat($data['totalInternalPrice'] ?? null),
            totalInvoicePrice: self::toFloat($data['totalInvoicePrice'] ?? null),
            totalInvoicePriceWithAgreement: self::toFloat($data['totalInvoicePriceWithAgreement'] ?? null),
            totalPrice: self::toFloat($data['totalPrice'] ?? null),
            totalTasksInternalPrice: self::toFloat($data['totalTasksInternalPrice'] ?? null),
            totalTasksInvoicePrice: self::toFloat($data['totalTasksInvoicePrice'] ?? null),
            totalTasksInvoicePriceWithAgreement: self::toFloat($data['totalTasksInvoicePriceWithAgreement'] ?? null),
            totalTasksInvoiceTime: self::toInt($data['totalTasksInvoiceTime'] ?? null),
            totalTasksInvoiceTimeWithAgreement: self::toInt($data['totalTasksInvoiceTimeWithAgreement'] ?? null),
            totalTasksPrice: self::toFloat($data['totalTasksPrice'] ?? null),
            totalTasksWorkingTime: self::toInt($data['totalTasksWorkingTime'] ?? null),
            totalTravelLogsDistance: self::toFloat($data['totalTravelLogsDistance'] ?? null),
            totalTravelLogsInternalPrice: self::toFloat($data['totalTravelLogsInternalPrice'] ?? null),
            totalTravelLogsInvoicePrice: self::toFloat($data['totalTravelLogsInvoicePrice'] ?? null),
            totalTravelLogsInvoiceTime: self::toInt($data['totalTravelLogsInvoiceTime'] ?? null),
            totalTravelLogsPrice: self::toFloat($data['totalTravelLogsPrice'] ?? null),
            totalTravelLogsTime: self::toInt($data['totalTravelLogsTime'] ?? null),
            travelLogs: isset($data['travelLogs']) && is_array($data['travelLogs']) ? $data['travelLogs'] : null,
            webLink: self::toString($data['webLink'] ?? null),
            billable: isset($data['billable']) ? self::toBool($data['billable']) : null,
            completedSuccessfully: isset($data['completedSuccessfully']) ? self::toBool($data['completedSuccessfully']) : null,
            confidentialTag: self::toInt($data['confidentialTag'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            erpReferenceNumber: self::toString($data['erpReferenceNumber'] ?? null),
            externalReferenceNumber: self::toString($data['externalReferenceNumber'] ?? null),
            infoFiles: isset($data['infoFiles']) && is_array($data['infoFiles']) ? $data['infoFiles'] : null,
            needFinishPin: isset($data['needFinishPin']) ? self::toBool($data['needFinishPin']) : null,
            needSignature: isset($data['needSignature']) ? self::toBool($data['needSignature']) : null,
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            postedDate: self::toString($data['postedDate'] ?? null),
            priority: self::toInt($data['priority'] ?? null),
            project: self::toInt($data['project'] ?? null),
            recordTravelTimeAfterPreFinished: isset($data['recordTravelTimeAfterPreFinished']) ? self::toBool($data['recordTravelTimeAfterPreFinished']) : null,
            referenceNumber: self::toString($data['referenceNumber'] ?? null),
            sendMessage: isset($data['sendMessage']) ? self::toBool($data['sendMessage']) : null,
            ticket: self::toInt($data['ticket'] ?? null),
            travelLog: isset($data['travelLog']) && is_array($data['travelLog']) ? $data['travelLog'] : null,
            type: isset($data['type']) ? self::toBool($data['type']) : null
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
            'infoFiles' => $this->infoFiles,
            'needFinishPin' => $this->needFinishPin,
            'needSignature' => $this->needSignature,
            'personInCharge' => $this->personInCharge,
            'postedDate' => $this->postedDate,
            'priority' => $this->priority,
            'project' => $this->project,
            'recordTravelTimeAfterPreFinished' => $this->recordTravelTimeAfterPreFinished,
            'referenceNumber' => $this->referenceNumber,
            'sendMessage' => $this->sendMessage,
            'ticket' => $this->ticket,
            'travelLog' => $this->travelLog,
            'type' => $this->type
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getCreated(): ?string { return $this->created; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getApproved(): ?bool { return $this->approved; }
    public function getApprovedComment(): ?string { return $this->approvedComment; }
    public function getApprovedDate(): ?string { return $this->approvedDate; }
    public function getCanChangeDocument(): ?bool { return $this->canChangeDocument; }
    public function getCanceled(): ?bool { return $this->canceled; }
    public function getCanceledDate(): ?string { return $this->canceledDate; }
    public function getDocumentNumber(): ?string { return $this->documentNumber; }
    public function getDrafted(): ?bool { return $this->drafted; }
    public function getFile(): ?int { return $this->file; }
    public function getFinishPin(): ?string { return $this->finishPin; }
    public function getFinished(): ?bool { return $this->finished; }
    public function getFinishedDate(): ?string { return $this->finishedDate; }
    public function getInvoiceNumber(): ?string { return $this->invoiceNumber; }
    public function getPreFinished(): ?bool { return $this->preFinished; }
    public function getProtocols(): ?array { return $this->protocols; }
    public function getReleasedDate(): ?string { return $this->releasedDate; }
    public function getServerModified(): ?string { return $this->serverModified; }
    public function getTasks(): ?array { return $this->tasks; }
    public function getTotalInternalPrice(): ?float { return $this->totalInternalPrice; }
    public function getTotalInvoicePrice(): ?float { return $this->totalInvoicePrice; }
    public function getTotalInvoicePriceWithAgreement(): ?float { return $this->totalInvoicePriceWithAgreement; }
    public function getTotalPrice(): ?float { return $this->totalPrice; }
    public function getTotalTasksInternalPrice(): ?float { return $this->totalTasksInternalPrice; }
    public function getTotalTasksInvoicePrice(): ?float { return $this->totalTasksInvoicePrice; }
    public function getTotalTasksInvoicePriceWithAgreement(): ?float { return $this->totalTasksInvoicePriceWithAgreement; }
    public function getTotalTasksInvoiceTime(): ?int { return $this->totalTasksInvoiceTime; }
    public function getTotalTasksInvoiceTimeWithAgreement(): ?int { return $this->totalTasksInvoiceTimeWithAgreement; }
    public function getTotalTasksPrice(): ?float { return $this->totalTasksPrice; }
    public function getTotalTasksWorkingTime(): ?int { return $this->totalTasksWorkingTime; }
    public function getTotalTravelLogsDistance(): ?float { return $this->totalTravelLogsDistance; }
    public function getTotalTravelLogsInternalPrice(): ?float { return $this->totalTravelLogsInternalPrice; }
    public function getTotalTravelLogsInvoicePrice(): ?float { return $this->totalTravelLogsInvoicePrice; }
    public function getTotalTravelLogsInvoiceTime(): ?int { return $this->totalTravelLogsInvoiceTime; }
    public function getTotalTravelLogsPrice(): ?float { return $this->totalTravelLogsPrice; }
    public function getTotalTravelLogsTime(): ?int { return $this->totalTravelLogsTime; }
    public function getTravelLogs(): ?array { return $this->travelLogs; }
    public function getWebLink(): ?string { return $this->webLink; }
    public function getBillable(): ?bool { return $this->billable; }
    public function getCompletedSuccessfully(): ?bool { return $this->completedSuccessfully; }
    public function getConfidentialTag(): ?int { return $this->confidentialTag; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getErpReferenceNumber(): ?string { return $this->erpReferenceNumber; }
    public function getExternalReferenceNumber(): ?string { return $this->externalReferenceNumber; }
    public function getInfoFiles(): ?array { return $this->infoFiles; }
    public function getNeedFinishPin(): ?bool { return $this->needFinishPin; }
    public function getNeedSignature(): ?bool { return $this->needSignature; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getPostedDate(): ?string { return $this->postedDate; }
    public function getPriority(): ?int { return $this->priority; }
    public function getProject(): ?int { return $this->project; }
    public function getRecordTravelTimeAfterPreFinished(): ?bool { return $this->recordTravelTimeAfterPreFinished; }
    public function getReferenceNumber(): ?string { return $this->referenceNumber; }
    public function getSendMessage(): ?bool { return $this->sendMessage; }
    public function getTicket(): ?int { return $this->ticket; }
    public function getTravelLog(): ?array { return $this->travelLog; }
    public function getType(): ?bool { return $this->type; }
}