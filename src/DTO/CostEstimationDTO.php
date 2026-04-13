<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimation record.
 */
final class CostEstimationDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?bool $approved,
        private readonly ?string $approvedDate,
        private readonly ?int $defaultTicketCategory,
        private readonly ?int $file,
        private readonly ?bool $finished,
        private readonly ?string $finishedDate,
        private readonly ?string $mode,
        private readonly ?int $nextRevision,
        private readonly ?int $previousRevision,
        private readonly ?array $tasks,
        private readonly ?int $ticket,
        private readonly ?int $ticketLinkType,
        private ?int $agreement,
        private ?float $defaultBuffer,
        private ?int $defaultServiceType,
        private ?string $name,
        private ?int $pdfLayout,
        private ?int $personInCharge,
        private ?bool $prependNameToTaskName,
        private ?int $taskPersonInCharge
    ) {}

    #[Override]
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

    #[Override]
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