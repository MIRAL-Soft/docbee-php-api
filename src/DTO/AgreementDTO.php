<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Agreement record.
 */
final class AgreementDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?array $components,
        private readonly ?bool $finished,
        private readonly ?array $invoices,
        private readonly ?array $periods,
        private readonly ?string $type,
        private ?int $agreementCategory,
        private ?bool $autoRenew,
        private ?int $customer,
        private ?int $customerLocation,
        private ?array $customerObjects,
        private ?string $description,
        private ?array $infoFiles,
        private ?string $invoiceCycle,
        private ?string $name,
        private ?int $noticePeriod,
        private ?string $number,
        private ?float $price,
        private ?string $renewCycle,
        private ?string $threshold,
        private ?int $ticketSla,
        private ?float $timeEstimate,
        private ?bool $withInvoice,
        private ?bool $withinWorkingSla
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            components: isset($data['components']) && is_array($data['components']) ? $data['components'] : null,
            finished: isset($data['finished']) ? self::toBool($data['finished']) : null,
            invoices: isset($data['invoices']) && is_array($data['invoices']) ? $data['invoices'] : null,
            periods: isset($data['periods']) && is_array($data['periods']) ? $data['periods'] : null,
            type: self::toString($data['type'] ?? null),
            agreementCategory: self::toInt($data['agreementCategory'] ?? null),
            autoRenew: isset($data['autoRenew']) ? self::toBool($data['autoRenew']) : null,
            customer: self::toInt($data['customer'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            customerObjects: isset($data['customerObjects']) && is_array($data['customerObjects']) ? $data['customerObjects'] : null,
            description: self::toString($data['description'] ?? null),
            infoFiles: isset($data['infoFiles']) && is_array($data['infoFiles']) ? $data['infoFiles'] : null,
            invoiceCycle: self::toString($data['invoiceCycle'] ?? null),
            name: self::toString($data['name'] ?? null),
            noticePeriod: self::toInt($data['noticePeriod'] ?? null),
            number: self::toString($data['number'] ?? null),
            price: self::toFloat($data['price'] ?? null),
            renewCycle: self::toString($data['renewCycle'] ?? null),
            threshold: self::toString($data['threshold'] ?? null),
            ticketSla: self::toInt($data['ticketSla'] ?? null),
            timeEstimate: self::toFloat($data['timeEstimate'] ?? null),
            withInvoice: isset($data['withInvoice']) ? self::toBool($data['withInvoice']) : null,
            withinWorkingSla: isset($data['withinWorkingSla']) ? self::toBool($data['withinWorkingSla']) : null
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'agreementCategory' => $this->agreementCategory,
            'autoRenew' => $this->autoRenew,
            'customer' => $this->customer,
            'customerLocation' => $this->customerLocation,
            'customerObjects' => $this->customerObjects,
            'description' => $this->description,
            'infoFiles' => $this->infoFiles,
            'invoiceCycle' => $this->invoiceCycle,
            'name' => $this->name,
            'noticePeriod' => $this->noticePeriod,
            'number' => $this->number,
            'price' => $this->price,
            'renewCycle' => $this->renewCycle,
            'threshold' => $this->threshold,
            'ticketSla' => $this->ticketSla,
            'timeEstimate' => $this->timeEstimate,
            'withInvoice' => $this->withInvoice,
            'withinWorkingSla' => $this->withinWorkingSla
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getComponents(): ?array { return $this->components; }
    public function getFinished(): ?bool { return $this->finished; }
    public function getInvoices(): ?array { return $this->invoices; }
    public function getPeriods(): ?array { return $this->periods; }
    public function getType(): ?string { return $this->type; }
    public function getAgreementCategory(): ?int { return $this->agreementCategory; }
    public function getAutoRenew(): ?bool { return $this->autoRenew; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getCustomerObjects(): ?array { return $this->customerObjects; }
    public function getDescription(): ?string { return $this->description; }
    public function getInfoFiles(): ?array { return $this->infoFiles; }
    public function getInvoiceCycle(): ?string { return $this->invoiceCycle; }
    public function getName(): ?string { return $this->name; }
    public function getNoticePeriod(): ?int { return $this->noticePeriod; }
    public function getNumber(): ?string { return $this->number; }
    public function getPrice(): ?float { return $this->price; }
    public function getRenewCycle(): ?string { return $this->renewCycle; }
    public function getThreshold(): ?string { return $this->threshold; }
    public function getTicketSla(): ?int { return $this->ticketSla; }
    public function getTimeEstimate(): ?float { return $this->timeEstimate; }
    public function getWithInvoice(): ?bool { return $this->withInvoice; }
    public function getWithinWorkingSla(): ?bool { return $this->withinWorkingSla; }
}