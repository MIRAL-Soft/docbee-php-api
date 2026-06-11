<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CostEstimationTask record.
 */
final class CostEstimationTaskDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific costEstimationTask */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** Unique identifier representing a specific docBeeDocument */
        private readonly ?int $docBeeDocument,
        /** sub task identifiers */
        private readonly ?array $subTasks,
        /** Unique identifier representing a specific ticket */
        private readonly ?int $ticket,
        /** buffer (100.0 means 100 %). If not provided the defaultBuffer of costEstimation is used */
        private ?float $buffer,
        /** description */
        private ?string $description,
        /** internal description */
        private ?string $internalDescription,
        /** name */
        private ?string $name,
        /** sofarCost */
        private ?bool $sofarCost,
        /** ticketCategory identifier (only valid for mode SUBTASK2DOCUMENT_TASK2TICKET). If not provided the defaultTicketCategory of costEstimation is used */
        private ?int $ticketCategory
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            docBeeDocument: self::toInt($data['docBeeDocument'] ?? null),
            subTasks: isset($data['subTasks']) && is_array($data['subTasks']) ? $data['subTasks'] : null,
            ticket: self::toInt($data['ticket'] ?? null),
            buffer: self::toFloat($data['buffer'] ?? null),
            description: self::toString($data['description'] ?? null),
            internalDescription: self::toString($data['internalDescription'] ?? null),
            name: self::toString($data['name'] ?? null),
            sofarCost: isset($data['sofarCost']) ? self::toBool($data['sofarCost']) : null,
            ticketCategory: self::toInt($data['ticketCategory'] ?? null)
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'buffer' => $this->buffer,
            'description' => $this->description,
            'internalDescription' => $this->internalDescription,
            'name' => $this->name,
            'sofarCost' => $this->sofarCost,
            'ticketCategory' => $this->ticketCategory
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getDocBeeDocument(): ?int { return $this->docBeeDocument; }
    public function getSubTasks(): ?array { return $this->subTasks; }
    public function getTicket(): ?int { return $this->ticket; }
    public function getBuffer(): ?float { return $this->buffer; }
    public function getDescription(): ?string { return $this->description; }
    public function getInternalDescription(): ?string { return $this->internalDescription; }
    public function getName(): ?string { return $this->name; }
    public function getSofarCost(): ?bool { return $this->sofarCost; }
    public function getTicketCategory(): ?int { return $this->ticketCategory; }
}