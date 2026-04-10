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
        private readonly ?string $mode,
        private readonly bool $finished,
        private readonly ?string $finishedDate,
        private readonly bool $approved,
        private readonly ?string $approvedDate,
        private readonly ?int $ticket,
        private readonly ?int $ticketLinkType,
        private readonly ?int $defaultTicketCategory,
        private readonly ?int $nextRevision,
        private readonly ?int $previousRevision,
        private readonly ?int $file,
        private readonly array $tasks,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            mode: self::toString($data['mode'] ?? null),
            finished: self::toBool($data['finished'] ?? false),
            finishedDate: self::toString($data['finishedDate'] ?? null),
            approved: self::toBool($data['approved'] ?? false),
            approvedDate: self::toString($data['approvedDate'] ?? null),
            ticket: self::toInt($data['ticket'] ?? null),
            ticketLinkType: self::toInt($data['ticketLinkType'] ?? null),
            defaultTicketCategory: self::toInt($data['defaultTicketCategory'] ?? null),
            nextRevision: self::toInt($data['nextRevision'] ?? null),
            previousRevision: self::toInt($data['previousRevision'] ?? null),
            file: self::toInt($data['file'] ?? null),
            tasks: $data['tasks'] ?? [],
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'mode' => $this->mode,
            'finished' => $this->finished,
            'finishedDate' => $this->finishedDate,
            'approved' => $this->approved,
            'approvedDate' => $this->approvedDate,
            'ticket' => $this->ticket,
            'ticketLinkType' => $this->ticketLinkType,
            'defaultTicketCategory' => $this->defaultTicketCategory,
            'nextRevision' => $this->nextRevision,
            'previousRevision' => $this->previousRevision,
            'file' => $this->file,
            'tasks' => $this->tasks,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getMode(): ?string { return $this->mode; }
    public function isFinished(): bool { return $this->finished; }
    public function getFinishedDate(): ?string { return $this->finishedDate; }
    public function isApproved(): bool { return $this->approved; }
    public function getApprovedDate(): ?string { return $this->approvedDate; }
    public function getTicket(): ?int { return $this->ticket; }
    public function getTicketLinkType(): ?int { return $this->ticketLinkType; }
    public function getDefaultTicketCategory(): ?int { return $this->defaultTicketCategory; }
    public function getNextRevision(): ?int { return $this->nextRevision; }
    public function getPreviousRevision(): ?int { return $this->previousRevision; }
    public function getFile(): ?int { return $this->file; }
    public function getTasks(): array { return $this->tasks; }
}