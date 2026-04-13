<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeDocumentTask (work task within a document).
 */
final class DocBeeDocumentTaskDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific Task */
        private readonly ?int    $id,
        /** created date */
        private readonly ?string $created,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** totalWorkingTime */
        private readonly ?int    $totalWorkingTime,
        /** totalPlanningTime */
        private readonly ?int    $totalPlanningTime,
        /** total invoice time */
        private readonly ?int    $totalInvoiceTime,
        /** total invoice time with agreement */
        private readonly ?int    $totalInvoiceTimeWithAgreement,
        /** total revised time */
        private readonly ?int    $totalRevisedTime,
        /** total contingent time */
        private readonly ?int    $totalContingentTime,
        /** price */
        private readonly ?float  $price,
        /** internal price */
        private readonly ?float  $internalPrice,
        /** revised price */
        private readonly ?float  $revisedPrice,
        /** contingent price */
        private readonly ?float  $contingentPrice,
        /** invoice price */
        private readonly ?float  $invoicePrice,
        /** invoice price with agreement */
        private readonly ?float  $invoicePriceWithAgreement,
        /** name */
        private ?string          $name,
        /** description */
        private ?string          $description,
        /** internalDescription */
        private ?string          $internalDescription,
        /** serviceType identifier */
        private ?int             $serviceType,
        /** contingent identifier */
        private ?int             $contingent,
        /** dueDate */
        private ?string          $dueDate,
        /** estimate */
        private ?int             $estimate,
        /** arrivalEstimate */
        private ?int             $arrivalEstimate,
        /** returnEstimate */
        private ?int             $returnEstimate,
        /** remainingEstimate */
        private ?int             $remainingEstimate,
        /** planningEstimate */
        private ?int             $planningEstimate,
        /** user identifiers */
        private ?array           $workers,
        /** customerObject identifiers */
        private ?array           $customerObjects,
        /** finished */
        private ?bool            $finished,
        /** isObligingness */
        private ?bool            $isObligingness,
        /** obligingnessMsg */
        private ?string          $obligingnessMsg,
        /** reviseValue */
        private ?float           $reviseValue,
        /** type */
        private ?string          $reviseType,
        /** reviseMsg */
        private ?string          $reviseMsg,
        /** file identifier of images */
        private ?array           $files,
        /** alternativeLocationAddress */
        private ?string          $alternativeLocationAddress,
        /** alternativeLocationLatitude */
        private ?float           $alternativeLocationLatitude,
        /** alternativeLocationLongitude */
        private ?float           $alternativeLocationLongitude,
        /** @var WorkLogDTO[]|null list of workLogs */
        private ?array           $workLogs,
        /** @var PlanningTimeDTO[]|null list of planningTimes */
        private ?array           $planningTimes,
        /** @var MaterialDTO[]|null list of materials */
        private ?array           $materials,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                            self::toInt($data['id'] ?? null),
            created:                       self::toString($data['created'] ?? null),
            modified:                      self::toString($data['modified'] ?? null),
            link:                          self::toString($data['link'] ?? null),
            totalWorkingTime:              self::toInt($data['totalWorkingTime'] ?? null),
            totalPlanningTime:             self::toInt($data['totalPlanningTime'] ?? null),
            totalInvoiceTime:              self::toInt($data['totalInvoiceTime'] ?? null),
            totalInvoiceTimeWithAgreement: self::toInt($data['totalInvoiceTimeWithAgreement'] ?? null),
            totalRevisedTime:              self::toInt($data['totalRevisedTime'] ?? null),
            totalContingentTime:           self::toInt($data['totalContingentTime'] ?? null),
            price:                         isset($data['price']) ? (float) $data['price'] : null,
            internalPrice:                 isset($data['internalPrice']) ? (float) $data['internalPrice'] : null,
            revisedPrice:                  isset($data['revisedPrice']) ? (float) $data['revisedPrice'] : null,
            contingentPrice:               isset($data['contingentPrice']) ? (float) $data['contingentPrice'] : null,
            invoicePrice:                  isset($data['invoicePrice']) ? (float) $data['invoicePrice'] : null,
            invoicePriceWithAgreement:     isset($data['invoicePriceWithAgreement']) ? (float) $data['invoicePriceWithAgreement'] : null,
            name:                          self::toString($data['name'] ?? null),
            description:                   self::toString($data['description'] ?? null),
            internalDescription:           self::toString($data['internalDescription'] ?? null),
            serviceType:                   self::toInt($data['serviceType'] ?? null),
            contingent:                    self::toInt($data['contingent'] ?? null),
            dueDate:                       self::toString($data['dueDate'] ?? null),
            estimate:                      self::toInt($data['estimate'] ?? null),
            arrivalEstimate:               self::toInt($data['arrivalEstimate'] ?? null),
            returnEstimate:                self::toInt($data['returnEstimate'] ?? null),
            remainingEstimate:             self::toInt($data['remainingEstimate'] ?? null),
            planningEstimate:              self::toInt($data['planningEstimate'] ?? null),
            workers:                       isset($data['workers']) && is_array($data['workers']) ? $data['workers'] : null,
            customerObjects:               isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            finished:                      isset($data['finished']) ? self::toBool($data['finished']) : null,
            isObligingness:                isset($data['isObligingness']) ? self::toBool($data['isObligingness']) : null,
            obligingnessMsg:               self::toString($data['obligingnessMsg'] ?? null),
            reviseValue:                   isset($data['reviseValue']) ? (float) $data['reviseValue'] : null,
            reviseType:                    self::toString($data['reviseType'] ?? null),
            reviseMsg:                     self::toString($data['reviseMsg'] ?? null),
            files:                         isset($data['files']) && is_array($data['files']) ? $data['files'] : null,
            alternativeLocationAddress:    self::toString($data['alternativeLocationAddress'] ?? null),
            alternativeLocationLatitude:   isset($data['alternativeLocationLatitude']) ? (float) $data['alternativeLocationLatitude'] : null,
            alternativeLocationLongitude:  isset($data['alternativeLocationLongitude']) ? (float) $data['alternativeLocationLongitude'] : null,
            workLogs:                      isset($data['workLogs']) && is_array($data['workLogs'])
                                               ? array_map(fn($w) => WorkLogDTO::fromArray($w), $data['workLogs'])
                                               : null,
            planningTimes:                 isset($data['planningTimes']) && is_array($data['planningTimes'])
                                               ? array_map(fn($p) => PlanningTimeDTO::fromArray($p), $data['planningTimes'])
                                               : null,
            materials:                     isset($data['materials']) && is_array($data['materials'])
                                               ? array_map(fn($m) => MaterialDTO::fromArray($m), $data['materials'])
                                               : null,
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name'                       => $this->name,
            'description'                => $this->description,
            'internalDescription'        => $this->internalDescription,
            'serviceType'                => $this->serviceType,
            'contingent'                 => $this->contingent,
            'dueDate'                    => $this->dueDate,
            'estimate'                   => $this->estimate,
            'arrivalEstimate'            => $this->arrivalEstimate,
            'returnEstimate'             => $this->returnEstimate,
            'remainingEstimate'          => $this->remainingEstimate,
            'planningEstimate'           => $this->planningEstimate,
            'workers'                    => $this->workers,
            'customerObjects'            => $this->customerObjects,
            'finished'                   => $this->finished,
            'isObligingness'             => $this->isObligingness,
            'obligingnessMsg'            => $this->obligingnessMsg,
            'reviseValue'                => $this->reviseValue,
            'reviseType'                 => $this->reviseType,
            'reviseMsg'                  => $this->reviseMsg,
            'files'                      => $this->files,
            'alternativeLocationAddress' => $this->alternativeLocationAddress,
            'alternativeLocationLatitude'  => $this->alternativeLocationLatitude,
            'alternativeLocationLongitude' => $this->alternativeLocationLongitude,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int                          { return $this->id; }
    public function getCreated(): ?string                  { return $this->created; }
    public function getModified(): ?string                 { return $this->modified; }
    public function getLink(): ?string                     { return $this->link; }
    public function getTotalWorkingTime(): ?int             { return $this->totalWorkingTime; }
    public function getTotalPlanningTime(): ?int            { return $this->totalPlanningTime; }
    public function getTotalInvoiceTime(): ?int             { return $this->totalInvoiceTime; }
    public function getTotalInvoiceTimeWithAgreement(): ?int { return $this->totalInvoiceTimeWithAgreement; }
    public function getTotalRevisedTime(): ?int             { return $this->totalRevisedTime; }
    public function getTotalContingentTime(): ?int          { return $this->totalContingentTime; }
    public function getPrice(): ?float                     { return $this->price; }
    public function getInternalPrice(): ?float             { return $this->internalPrice; }
    public function getRevisedPrice(): ?float              { return $this->revisedPrice; }
    public function getContingentPrice(): ?float           { return $this->contingentPrice; }
    public function getInvoicePrice(): ?float              { return $this->invoicePrice; }
    public function getInvoicePriceWithAgreement(): ?float { return $this->invoicePriceWithAgreement; }
    public function getName(): ?string                     { return $this->name; }
    public function getDescription(): ?string              { return $this->description; }
    public function getInternalDescription(): ?string      { return $this->internalDescription; }
    public function getServiceType(): ?int                 { return $this->serviceType; }
    public function getContingent(): ?int                  { return $this->contingent; }
    public function getDueDate(): ?string                  { return $this->dueDate; }
    public function getEstimate(): ?int                    { return $this->estimate; }
    public function getArrivalEstimate(): ?int             { return $this->arrivalEstimate; }
    public function getReturnEstimate(): ?int              { return $this->returnEstimate; }
    public function getRemainingEstimate(): ?int           { return $this->remainingEstimate; }
    public function getPlanningEstimate(): ?int            { return $this->planningEstimate; }
    public function getWorkers(): ?array                   { return $this->workers; }
    public function getCustomerObjects(): ?array           { return $this->customerObjects; }
    public function isFinished(): ?bool                    { return $this->finished; }
    public function isObligingness(): ?bool                { return $this->isObligingness; }
    public function getObligingnessMsg(): ?string          { return $this->obligingnessMsg; }
    public function getReviseValue(): ?float               { return $this->reviseValue; }
    public function getReviseType(): ?string               { return $this->reviseType; }
    public function getReviseMsg(): ?string                { return $this->reviseMsg; }
    public function getFiles(): ?array                     { return $this->files; }
    public function getAlternativeLocationAddress(): ?string    { return $this->alternativeLocationAddress; }
    public function getAlternativeLocationLatitude(): ?float    { return $this->alternativeLocationLatitude; }
    public function getAlternativeLocationLongitude(): ?float   { return $this->alternativeLocationLongitude; }
    /** @return WorkLogDTO[]|null */
    public function getWorkLogs(): ?array      { return $this->workLogs; }
    /** @return PlanningTimeDTO[]|null */
    public function getPlanningTimes(): ?array { return $this->planningTimes; }
    /** @return MaterialDTO[]|null */
    public function getMaterials(): ?array     { return $this->materials; }
}
