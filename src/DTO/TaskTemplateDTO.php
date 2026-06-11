<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee TaskTemplate record.
 */
final class TaskTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific TaskTemplate */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** materialTemplate identifiers */
        private readonly ?array $materialTemplates,
        /** planningTimeTemplate identifiers */
        private readonly ?array $planningTimeTemplates,
        /** workLogTemplate identifiers */
        private readonly ?array $workLogTemplates,
        /** alternativeLocationAddress */
        private ?string $alternativeLocationAddress,
        /** alternativeLocationLatitude */
        private ?float $alternativeLocationLatitude,
        /** alternativeLocationLongitude */
        private ?float $alternativeLocationLongitude,
        /** arrivalEstimate */
        private ?int $arrivalEstimate,
        /** contingent identifier */
        private ?int $contingent,
        /** customer identifier */
        private ?int $customer,
        /** customerContact identifier */
        private ?int $customerContact,
        /** customerLocation identifier */
        private ?int $customerLocation,
        /** customerObject identifiers */
        private ?array $customerObjects,
        /** description */
        private ?string $description,
        /** dueDate offset in days of the to be created task */
        private ?int $dueDateOffset,
        /** dueDate time of day in milliseconds */
        private ?int $dueDateTime,
        /** estimate */
        private ?int $estimate,
        /** internalDescription */
        private ?string $internalDescription,
        /** name */
        private ?string $name,
        /** planningEstimate */
        private ?int $planningEstimate,
        /** remainingEstimate */
        private ?int $remainingEstimate,
        /** returnEstimate */
        private ?int $returnEstimate,
        /** serviceType identifier */
        private ?int $serviceType,
        /** templateName */
        private ?string $templateName,
        /** user identifiers */
        private ?array $workers
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            materialTemplates: isset($data['materialTemplates']) && is_array($data['materialTemplates']) ? $data['materialTemplates'] : null,
            planningTimeTemplates: isset($data['planningTimeTemplates']) && is_array($data['planningTimeTemplates']) ? $data['planningTimeTemplates'] : null,
            workLogTemplates: isset($data['workLogTemplates']) && is_array($data['workLogTemplates']) ? $data['workLogTemplates'] : null,
            alternativeLocationAddress: self::toString($data['alternativeLocationAddress'] ?? null),
            alternativeLocationLatitude: self::toFloat($data['alternativeLocationLatitude'] ?? null),
            alternativeLocationLongitude: self::toFloat($data['alternativeLocationLongitude'] ?? null),
            arrivalEstimate: self::toInt($data['arrivalEstimate'] ?? null),
            contingent: self::toInt($data['contingent'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            description: self::toString($data['description'] ?? null),
            dueDateOffset: self::toInt($data['dueDateOffset'] ?? null),
            dueDateTime: self::toInt($data['dueDateTime'] ?? null),
            estimate: self::toInt($data['estimate'] ?? null),
            internalDescription: self::toString($data['internalDescription'] ?? null),
            name: self::toString($data['name'] ?? null),
            planningEstimate: self::toInt($data['planningEstimate'] ?? null),
            remainingEstimate: self::toInt($data['remainingEstimate'] ?? null),
            returnEstimate: self::toInt($data['returnEstimate'] ?? null),
            serviceType: self::toInt($data['serviceType'] ?? null),
            templateName: self::toString($data['templateName'] ?? null),
            workers: isset($data['workers']) && is_array($data['workers']) ? $data['workers'] : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'alternativeLocationAddress' => $this->alternativeLocationAddress,
            'alternativeLocationLatitude' => $this->alternativeLocationLatitude,
            'alternativeLocationLongitude' => $this->alternativeLocationLongitude,
            'arrivalEstimate' => $this->arrivalEstimate,
            'contingent' => $this->contingent,
            'customer' => $this->customer,
            'customerContact' => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'customerObjects' => $this->customerObjects,
            'description' => $this->description,
            'dueDateOffset' => $this->dueDateOffset,
            'dueDateTime' => $this->dueDateTime,
            'estimate' => $this->estimate,
            'internalDescription' => $this->internalDescription,
            'name' => $this->name,
            'planningEstimate' => $this->planningEstimate,
            'remainingEstimate' => $this->remainingEstimate,
            'returnEstimate' => $this->returnEstimate,
            'serviceType' => $this->serviceType,
            'templateName' => $this->templateName,
            'workers' => $this->workers
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getMaterialTemplates(): ?array { return $this->materialTemplates; }
    public function getPlanningTimeTemplates(): ?array { return $this->planningTimeTemplates; }
    public function getWorkLogTemplates(): ?array { return $this->workLogTemplates; }
    public function getAlternativeLocationAddress(): ?string { return $this->alternativeLocationAddress; }
    public function getAlternativeLocationLatitude(): ?float { return $this->alternativeLocationLatitude; }
    public function getAlternativeLocationLongitude(): ?float { return $this->alternativeLocationLongitude; }
    public function getArrivalEstimate(): ?int { return $this->arrivalEstimate; }
    public function getContingent(): ?int { return $this->contingent; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function getDescription(): ?string { return $this->description; }
    public function getDueDateOffset(): ?int { return $this->dueDateOffset; }
    public function getDueDateTime(): ?int { return $this->dueDateTime; }
    public function getEstimate(): ?int { return $this->estimate; }
    public function getInternalDescription(): ?string { return $this->internalDescription; }
    public function getName(): ?string { return $this->name; }
    public function getPlanningEstimate(): ?int { return $this->planningEstimate; }
    public function getRemainingEstimate(): ?int { return $this->remainingEstimate; }
    public function getReturnEstimate(): ?int { return $this->returnEstimate; }
    public function getServiceType(): ?int { return $this->serviceType; }
    public function getTemplateName(): ?string { return $this->templateName; }
    public function getWorkers(): ?array { return $this->workers; }
}