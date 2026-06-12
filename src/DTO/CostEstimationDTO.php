<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimation record.
 */
final class CostEstimationDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific costEstimation */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** approved */
        private readonly ?bool $approved,
        /** approve date */
        private readonly ?string $approvedDate,
        /** Unique identifier representing a specific ticketCategory */
        private readonly ?int $defaultTicketCategory,
        /** Unique identifier representing a specific file */
        private readonly ?int $file,
        /** finished */
        private readonly ?bool $finished,
        /** finish date */
        private readonly ?string $finishedDate,
        /** type */
        private readonly ?string $mode,
        /** Unique identifier representing the next revision of a revised costEstimation */
        private readonly ?int $nextRevision,
        /** Unique identifier representing the previous revision of a costEstimation */
        private readonly ?int $previousRevision,
        /**
         * task identifiers
         * @var array<int|string, mixed>|null
         */
        private readonly ?array $tasks,
        /** Unique identifier representing a specific ticket */
        private readonly ?int $ticket,
        /** Unique identifier representing a specific ticketLinkType */
        private readonly ?int $ticketLinkType,
        /** agreement identifier */
        private ?int $agreement,
        /** defaultBuffer */
        private ?float $defaultBuffer,
        /** serviceType identifier */
        private ?int $defaultServiceType,
        /** name */
        private ?string $name,
        /** pdfLayout identifier */
        private ?int $pdfLayout,
        /** personInCharge identifier */
        private ?int $personInCharge,
        /** prependNameToTaskName (only valid for mode SUBTASK2DOCUMENT_TASK2TICKET) */
        private ?bool $prependNameToTaskName,
        /** taskPersonInCharge identifier */
        private ?int $taskPersonInCharge
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
            approved: isset($data['approved']) ? self::toBool($data['approved']) : null,
            approvedDate: self::toString($data['approvedDate'] ?? null),
            defaultTicketCategory: self::toInt($data['defaultTicketCategory'] ?? null),
            file: self::toInt($data['file'] ?? null),
            finished: isset($data['finished']) ? self::toBool($data['finished']) : null,
            finishedDate: self::toString($data['finishedDate'] ?? null),
            mode: self::toString($data['mode'] ?? null),
            nextRevision: self::toInt($data['nextRevision'] ?? null),
            previousRevision: self::toInt($data['previousRevision'] ?? null),
            tasks: isset($data['tasks']) && is_array($data['tasks']) ? $data['tasks'] : null,
            ticket: self::toInt($data['ticket'] ?? null),
            ticketLinkType: self::toInt($data['ticketLinkType'] ?? null),
            agreement: self::toInt($data['agreement'] ?? null),
            defaultBuffer: self::toFloat($data['defaultBuffer'] ?? null),
            defaultServiceType: self::toInt($data['defaultServiceType'] ?? null),
            name: self::toString($data['name'] ?? null),
            pdfLayout: self::toInt($data['pdfLayout'] ?? null),
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            prependNameToTaskName: isset($data['prependNameToTaskName']) ? self::toBool($data['prependNameToTaskName']) : null,
            taskPersonInCharge: self::toInt($data['taskPersonInCharge'] ?? null)
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'agreement' => $this->agreement,
            'defaultBuffer' => $this->defaultBuffer,
            'defaultServiceType' => $this->defaultServiceType,
            'name' => $this->name,
            'pdfLayout' => $this->pdfLayout,
            'personInCharge' => $this->personInCharge,
            'prependNameToTaskName' => $this->prependNameToTaskName,
            'taskPersonInCharge' => $this->taskPersonInCharge
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getApproved(): ?bool { return $this->approved; }
    public function getApprovedDate(): ?string { return $this->approvedDate; }
    public function getDefaultTicketCategory(): ?int { return $this->defaultTicketCategory; }
    public function getFile(): ?int { return $this->file; }
    public function getFinished(): ?bool { return $this->finished; }
    public function getFinishedDate(): ?string { return $this->finishedDate; }
    public function getMode(): ?string { return $this->mode; }
    public function getNextRevision(): ?int { return $this->nextRevision; }
    public function getPreviousRevision(): ?int { return $this->previousRevision; }
    /**
     * @return array<int|string, mixed>|null
     */
    public function getTasks(): ?array { return $this->tasks; }
    public function getTicket(): ?int { return $this->ticket; }
    public function getTicketLinkType(): ?int { return $this->ticketLinkType; }
    public function getAgreement(): ?int { return $this->agreement; }
    public function getDefaultBuffer(): ?float { return $this->defaultBuffer; }
    public function getDefaultServiceType(): ?int { return $this->defaultServiceType; }
    public function getName(): ?string { return $this->name; }
    public function getPdfLayout(): ?int { return $this->pdfLayout; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getPrependNameToTaskName(): ?bool { return $this->prependNameToTaskName; }
    public function getTaskPersonInCharge(): ?int { return $this->taskPersonInCharge; }
}