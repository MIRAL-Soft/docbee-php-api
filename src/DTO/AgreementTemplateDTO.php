<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee AgreementTemplate record.
 */
final class AgreementTemplateDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific agreement template */
        private readonly ?int $id,
        /** modified date */
        private readonly ?string $modified,
        /** REST API Link */
        private readonly ?string $link,
        /** component template identifiers */
        private readonly ?array $components,
        /** agreementCategory identifier */
        private ?int $agreementCategory,
        /** autoRenew */
        private ?bool $autoRenew,
        /** costEstimationTemplate identifier */
        private ?int $costEstimationTemplate,
        /** description */
        private ?string $description,
        /** invoiceCycle */
        private ?string $invoiceCycle,
        /** name */
        private ?string $name,
        /** noticePeriod in months */
        private ?int $noticePeriod,
        /** price */
        private ?float $price,
        /** renewCycle */
        private ?string $renewCycle,
        /** slaProfile identifier */
        private ?int $slaProfile,
        /** threshold */
        private ?string $threshold,
        /** ticketSla in milliseconds */
        private ?int $ticketSla,
        /** timeEstimate */
        private ?float $timeEstimate,
        /** type */
        private ?string $type,
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
            agreementCategory: self::toInt($data['agreementCategory'] ?? null),
            autoRenew: isset($data['autoRenew']) ? self::toBool($data['autoRenew']) : null,
            costEstimationTemplate: self::toInt($data['costEstimationTemplate'] ?? null),
            description: self::toString($data['description'] ?? null),
            invoiceCycle: self::toString($data['invoiceCycle'] ?? null),
            name: self::toString($data['name'] ?? null),
            noticePeriod: self::toInt($data['noticePeriod'] ?? null),
            price: self::toFloat($data['price'] ?? null),
            renewCycle: self::toString($data['renewCycle'] ?? null),
            slaProfile: self::toInt($data['slaProfile'] ?? null),
            threshold: self::toString($data['threshold'] ?? null),
            ticketSla: self::toInt($data['ticketSla'] ?? null),
            timeEstimate: self::toFloat($data['timeEstimate'] ?? null),
            type: self::toString($data['type'] ?? null),
            withInvoice: isset($data['withInvoice']) ? self::toBool($data['withInvoice']) : null,
            withinWorkingSla: isset($data['withinWorkingSla']) ? self::toBool($data['withinWorkingSla']) : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'agreementCategory' => $this->agreementCategory,
            'autoRenew' => $this->autoRenew,
            'costEstimationTemplate' => $this->costEstimationTemplate,
            'description' => $this->description,
            'invoiceCycle' => $this->invoiceCycle,
            'name' => $this->name,
            'noticePeriod' => $this->noticePeriod,
            'price' => $this->price,
            'renewCycle' => $this->renewCycle,
            'slaProfile' => $this->slaProfile,
            'threshold' => $this->threshold,
            'ticketSla' => $this->ticketSla,
            'timeEstimate' => $this->timeEstimate,
            'type' => $this->type,
            'withInvoice' => $this->withInvoice,
            'withinWorkingSla' => $this->withinWorkingSla
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getComponents(): ?array { return $this->components; }
    public function getAgreementCategory(): ?int { return $this->agreementCategory; }
    public function getAutoRenew(): ?bool { return $this->autoRenew; }
    public function getCostEstimationTemplate(): ?int { return $this->costEstimationTemplate; }
    public function getDescription(): ?string { return $this->description; }
    public function getInvoiceCycle(): ?string { return $this->invoiceCycle; }
    public function getName(): ?string { return $this->name; }
    public function getNoticePeriod(): ?int { return $this->noticePeriod; }
    public function getPrice(): ?float { return $this->price; }
    public function getRenewCycle(): ?string { return $this->renewCycle; }
    public function getSlaProfile(): ?int { return $this->slaProfile; }
    public function getThreshold(): ?string { return $this->threshold; }
    public function getTicketSla(): ?int { return $this->ticketSla; }
    public function getTimeEstimate(): ?float { return $this->timeEstimate; }
    public function getType(): ?string { return $this->type; }
    public function getWithInvoice(): ?bool { return $this->withInvoice; }
    public function getWithinWorkingSla(): ?bool { return $this->withinWorkingSla; }
}