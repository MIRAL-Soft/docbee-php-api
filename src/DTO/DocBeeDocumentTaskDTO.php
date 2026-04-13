<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee DocBeeDocumentTask (work task within a document).
 */
final class DocBeeDocumentTaskDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int    $id,
        private readonly ?string $created,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?int    $totalWorkingTime,
        private readonly ?int    $totalPlanningTime,
        private readonly ?int    $totalInvoiceTime,
        private readonly ?int    $totalInvoiceTimeWithAgreement,
        private readonly ?int    $totalRevisedTime,
        private readonly ?int    $totalContingentTime,
        private readonly ?float  $price,
        private readonly ?float  $internalPrice,
        private readonly ?float  $revisedPrice,
        private readonly ?float  $contingentPrice,
        private readonly ?float  $invoicePrice,
        private readonly ?float  $invoicePriceWithAgreement,
        private ?string          $name,
        private ?string          $description,
        private ?string          $internalDescription,
        private ?int             $serviceType,
        private ?int             $contingent,
        private ?string          $dueDate,
        private ?int             $estimate,
        private ?int             $arrivalEstimate,
        private ?int             $returnEstimate,
        private ?int             $remainingEstimate,
        private ?int             $planningEstimate,
        private ?array           $workers,
        private ?array           $customerObjects,
        private ?bool            $finished,
        private ?bool            $isObligingness,
        private ?string          $obligingnessMsg,
        private ?float           $reviseValue,
        private ?string          $reviseType,
        private ?string          $reviseMsg,
        private ?array           $files,
        private ?string          $alternativeLocationAddress,
        private ?float           $alternativeLocationLatitude,
        private ?float           $alternativeLocationLongitude,
        private ?array           $workLogs,
        private ?array           $planningTimes,
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
            workLogs:                      isset($data['workLogs']) && is_array($data['workLogs']) ? $data['workLogs'] : null,
            planningTimes:                 isset($data['planningTimes']) && is_array($data['planningTimes']) ? $data['planningTimes'] : null,
            materials:                     isset($data['materials']) && is_array($data['materials']) ? $data['materials'] : null,
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
    public function getWorkLogs(): ?array                  { return $this->workLogs; }
    public function getPlanningTimes(): ?array             { return $this->planningTimes; }
    public function getMaterials(): ?array                 { return $this->materials; }
}
