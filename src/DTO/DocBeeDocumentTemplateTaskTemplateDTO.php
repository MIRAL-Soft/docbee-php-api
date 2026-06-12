<?php declare(strict_types=1);
namespace miralsoft\docbee\api\DTO;
/** Represents a Docbee DocBeeDocumentTemplate TaskTemplate record. Differs from TaskTemplateDTO: has selectable, no customer/contact/location. */
final class DocBeeDocumentTemplateTaskTemplateDTO extends AbstractDTO {
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        /** @var array<int|string, mixed>|null */
        private readonly ?array $materialTemplates,
        /** @var array<int|string, mixed>|null */
        private readonly ?array $planningTimeTemplates,
        /** @var array<int|string, mixed>|null */
        private readonly ?array $workLogTemplates,
        private readonly ?string $link,
        private ?string $templateName,
        private ?string $name,
        private ?string $description,
        private ?string $internalDescription,
        private ?int $serviceType,
        private ?int $contingent,
        private ?int $dueDateTime,
        private ?int $dueDateOffset,
        private ?int $estimate,
        private ?int $arrivalEstimate,
        private ?int $returnEstimate,
        private ?int $remainingEstimate,
        private ?int $planningEstimate,
        private ?string $alternativeLocationAddress,
        private ?float $alternativeLocationLatitude,
        private ?float $alternativeLocationLongitude,
        /** @var array<int|string, mixed>|null */
        private ?array $workers,
        /** @var array<int|string, mixed>|null */
        private ?array $customerObjects,
        private ?bool $selectable,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    #[\Override]
    public static function fromArray(array $data): static {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            materialTemplates: isset($data['materialTemplates']) && is_array($data['materialTemplates']) ? $data['materialTemplates'] : null,
            planningTimeTemplates: isset($data['planningTimeTemplates']) && is_array($data['planningTimeTemplates']) ? $data['planningTimeTemplates'] : null,
            workLogTemplates: isset($data['workLogTemplates']) && is_array($data['workLogTemplates']) ? $data['workLogTemplates'] : null,
            link: self::toString($data['link'] ?? null),
            templateName: self::toString($data['templateName'] ?? null),
            name: self::toString($data['name'] ?? null),
            description: self::toString($data['description'] ?? null),
            internalDescription: self::toString($data['internalDescription'] ?? null),
            serviceType: self::toInt($data['serviceType'] ?? null),
            contingent: self::toInt($data['contingent'] ?? null),
            dueDateTime: self::toInt($data['dueDateTime'] ?? null),
            dueDateOffset: self::toInt($data['dueDateOffset'] ?? null),
            estimate: self::toInt($data['estimate'] ?? null),
            arrivalEstimate: self::toInt($data['arrivalEstimate'] ?? null),
            returnEstimate: self::toInt($data['returnEstimate'] ?? null),
            remainingEstimate: self::toInt($data['remainingEstimate'] ?? null),
            planningEstimate: self::toInt($data['planningEstimate'] ?? null),
            alternativeLocationAddress: self::toString($data['alternativeLocationAddress'] ?? null),
            alternativeLocationLatitude: self::toFloat($data['alternativeLocationLatitude'] ?? null),
            alternativeLocationLongitude: self::toFloat($data['alternativeLocationLongitude'] ?? null),
            workers: isset($data['workers']) && is_array($data['workers']) ? $data['workers'] : null,
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            selectable: isset($data['selectable']) ? self::toBool($data['selectable']) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array {
        return array_filter([
            'templateName' => $this->templateName,
            'name' => $this->name,
            'description' => $this->description,
            'internalDescription' => $this->internalDescription,
            'serviceType' => $this->serviceType,
            'contingent' => $this->contingent,
            'dueDateTime' => $this->dueDateTime,
            'dueDateOffset' => $this->dueDateOffset,
            'estimate' => $this->estimate,
            'arrivalEstimate' => $this->arrivalEstimate,
            'returnEstimate' => $this->returnEstimate,
            'remainingEstimate' => $this->remainingEstimate,
            'planningEstimate' => $this->planningEstimate,
            'alternativeLocationAddress' => $this->alternativeLocationAddress,
            'alternativeLocationLatitude' => $this->alternativeLocationLatitude,
            'alternativeLocationLongitude' => $this->alternativeLocationLongitude,
            'workers' => $this->workers,
            'customerObjects' => $this->customerObjects,
            'selectable' => $this->selectable,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    /** @return array<int|string, mixed>|null */
    public function getMaterialTemplates(): ?array { return $this->materialTemplates; }
    /** @return array<int|string, mixed>|null */
    public function getPlanningTimeTemplates(): ?array { return $this->planningTimeTemplates; }
    /** @return array<int|string, mixed>|null */
    public function getWorkLogTemplates(): ?array { return $this->workLogTemplates; }
    public function getLink(): ?string { return $this->link; }
    public function getTemplateName(): ?string { return $this->templateName; }
    public function getName(): ?string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getInternalDescription(): ?string { return $this->internalDescription; }
    public function getServiceType(): ?int { return $this->serviceType; }
    public function getContingent(): ?int { return $this->contingent; }
    public function getDueDateTime(): ?int { return $this->dueDateTime; }
    public function getDueDateOffset(): ?int { return $this->dueDateOffset; }
    public function getEstimate(): ?int { return $this->estimate; }
    public function getArrivalEstimate(): ?int { return $this->arrivalEstimate; }
    public function getReturnEstimate(): ?int { return $this->returnEstimate; }
    public function getRemainingEstimate(): ?int { return $this->remainingEstimate; }
    public function getPlanningEstimate(): ?int { return $this->planningEstimate; }
    public function getAlternativeLocationAddress(): ?string { return $this->alternativeLocationAddress; }
    public function getAlternativeLocationLatitude(): ?float { return $this->alternativeLocationLatitude; }
    public function getAlternativeLocationLongitude(): ?float { return $this->alternativeLocationLongitude; }
    /** @return array<int|string, mixed>|null */
    public function getWorkers(): ?array { return $this->workers; }
    /** @return array<int|string, mixed>|null */
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function isSelectable(): ?bool { return $this->selectable; }
}
