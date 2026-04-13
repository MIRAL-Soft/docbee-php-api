<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimationTemplate record.
 */
final class CostEstimationTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific costEstimationTemplate */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** type */
        private readonly ?string $mode,
        /** TaskTemplate identifiers */
        private readonly ?array $tasks,
        /** defaultBuffer */
        private ?float $defaultBuffer,
        /** serviceType identifier */
        private ?int $defaultServiceType,
        /** defaultTicketCategory identifier (only valid for mode SUBTASK2DOCUMENT_TASK2TICKET) */
        private ?int $defaultTicketCategory,
        /** name */
        private ?string $name,
        /** pdfLayout identifier */
        private ?int $pdfLayout,
        /** personInCharge identifier */
        private ?int $personInCharge,
        /** prependNameToTaskName (only valid for mode SUBTASK2DOCUMENT_TASK2TICKET) */
        private ?bool $prependNameToTaskName,
        /** taskPersonInCharge identifier */
        private ?int $taskPersonInCharge,
        /** ticketLinkType identifier (only valid for mode SUBTASK2DOCUMENT_TASK2TICKET) */
        private ?int $ticketLinkType
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            mode: self::toString($data['mode'] ?? null),
            tasks: isset($data['tasks']) && is_array($data['tasks']) ? $data['tasks'] : null,
            defaultBuffer: self::toFloat($data['defaultBuffer'] ?? null),
            defaultServiceType: self::toInt($data['defaultServiceType'] ?? null),
            defaultTicketCategory: self::toInt($data['defaultTicketCategory'] ?? null),
            name: self::toString($data['name'] ?? null),
            pdfLayout: self::toInt($data['pdfLayout'] ?? null),
            personInCharge: self::toInt($data['personInCharge'] ?? null),
            prependNameToTaskName: isset($data['prependNameToTaskName']) ? self::toBool($data['prependNameToTaskName']) : null,
            taskPersonInCharge: self::toInt($data['taskPersonInCharge'] ?? null),
            ticketLinkType: self::toInt($data['ticketLinkType'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'defaultBuffer' => $this->defaultBuffer,
            'defaultServiceType' => $this->defaultServiceType,
            'defaultTicketCategory' => $this->defaultTicketCategory,
            'name' => $this->name,
            'pdfLayout' => $this->pdfLayout,
            'personInCharge' => $this->personInCharge,
            'prependNameToTaskName' => $this->prependNameToTaskName,
            'taskPersonInCharge' => $this->taskPersonInCharge,
            'ticketLinkType' => $this->ticketLinkType
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getMode(): ?string { return $this->mode; }
    public function getTasks(): ?array { return $this->tasks; }
    public function getDefaultBuffer(): ?float { return $this->defaultBuffer; }
    public function getDefaultServiceType(): ?int { return $this->defaultServiceType; }
    public function getDefaultTicketCategory(): ?int { return $this->defaultTicketCategory; }
    public function getName(): ?string { return $this->name; }
    public function getPdfLayout(): ?int { return $this->pdfLayout; }
    public function getPersonInCharge(): ?int { return $this->personInCharge; }
    public function getPrependNameToTaskName(): ?bool { return $this->prependNameToTaskName; }
    public function getTaskPersonInCharge(): ?int { return $this->taskPersonInCharge; }
    public function getTicketLinkType(): ?int { return $this->ticketLinkType; }
}