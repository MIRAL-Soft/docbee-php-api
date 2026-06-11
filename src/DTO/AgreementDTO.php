<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee Agreement record.
 */
final class AgreementDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific agreement */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** component identifiers */
        private readonly ?array $components,
        /** finished */
        private readonly ?bool $finished,
        /** invoice identifiers */
        private readonly ?array $invoices,
        /** period identifiers */
        private readonly ?array $periods,
        /** type */
        private readonly ?string $type,
        /** agreementCategory identifier */
        private ?int $agreementCategory,
        /** autoRenew */
        private ?bool $autoRenew,
        /** customer identifier */
        private ?int $customer,
        /** customerLocation identifier */
        private ?int $customerLocation,
        /** customerObject identifiers */
        private ?array $customerObjects,
        /** description */
        private ?string $description,
        /** file identifiers */
        private ?array $infoFiles,
        /** invoiceCycle */
        private ?string $invoiceCycle,
        /** name */
        private ?string $name,
        /** noticePeriod in months */
        private ?int $noticePeriod,
        /** number */
        private ?string $number,
        /** price */
        private ?float $price,
        /** renewCycle */
        private ?string $renewCycle,
        /** threshold */
        private ?string $threshold,
        /** ticketSla in milliseconds */
        private ?int $ticketSla,
        /** timeEstimate */
        private ?float $timeEstimate,
        /** withInvoice */
        private ?bool $withInvoice,
        /** withinWorkingSla */
        private ?bool $withinWorkingSla
    ) {}

    #[\Override]
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

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            // `type` is required by NewAgreement — without it create($dto->toArray())
            // could never produce a valid payload.
            'type' => $this->type,
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