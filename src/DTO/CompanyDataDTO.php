<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CompanyData record.
 */
final class CompanyDataDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?bool $isDefault,
        private ?string $city,
        private ?int $documentLogo,
        private ?string $faxNumber,
        private ?string $name,
        private ?int $pdfLayout,
        private ?string $street,
        private ?string $telephoneNumber,
        private ?int $websiteLogo,
        private ?string $zipcode
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            isDefault: isset($data['isDefault']) ? self::toBool($data['isDefault']) : null,
            city: self::toString($data['city'] ?? null),
            documentLogo: self::toInt($data['documentLogo'] ?? null),
            faxNumber: self::toString($data['faxNumber'] ?? null),
            name: self::toString($data['name'] ?? null),
            pdfLayout: self::toInt($data['pdfLayout'] ?? null),
            street: self::toString($data['street'] ?? null),
            telephoneNumber: self::toString($data['telephoneNumber'] ?? null),
            websiteLogo: self::toInt($data['websiteLogo'] ?? null),
            zipcode: self::toString($data['zipcode'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'city' => $this->city,
            'documentLogo' => $this->documentLogo,
            'faxNumber' => $this->faxNumber,
            'name' => $this->name,
            'pdfLayout' => $this->pdfLayout,
            'street' => $this->street,
            'telephoneNumber' => $this->telephoneNumber,
            'websiteLogo' => $this->websiteLogo,
            'zipcode' => $this->zipcode
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getIsDefault(): ?bool { return $this->isDefault; }
    public function getCity(): ?string { return $this->city; }
    public function getDocumentLogo(): ?int { return $this->documentLogo; }
    public function getFaxNumber(): ?string { return $this->faxNumber; }
    public function getName(): ?string { return $this->name; }
    public function getPdfLayout(): ?int { return $this->pdfLayout; }
    public function getStreet(): ?string { return $this->street; }
    public function getTelephoneNumber(): ?string { return $this->telephoneNumber; }
    public function getWebsiteLogo(): ?int { return $this->websiteLogo; }
    public function getZipcode(): ?string { return $this->zipcode; }
}