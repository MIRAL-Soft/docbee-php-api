<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomerContact record.
 */
final class CustomerContactDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $modified,
        private readonly ?string $link,
        private readonly ?int $customer,
        private readonly ?int $customerLocation,
        private readonly ?bool $temporary,
        private ?array $customFields,
        private ?string $email,
        private ?string $gender,
        private ?string $info,
        private ?string $labeling,
        private ?string $mobile,
        private ?string $name,
        private ?bool $sendEmail,
        private ?bool $sendEmailIfSelected,
        private ?bool $sendFax,
        private ?bool $sendFaxIfSelected,
        private ?bool $syncToApp,
        private ?string $telefax,
        private ?string $telephone
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            modified: self::toString($data['modified'] ?? null),
            link: self::toString($data['link'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            temporary: isset($data['temporary']) ? self::toBool($data['temporary']) : null,
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            email: self::toString($data['email'] ?? null),
            gender: self::toString($data['gender'] ?? null),
            info: self::toString($data['info'] ?? null),
            labeling: self::toString($data['labeling'] ?? null),
            mobile: self::toString($data['mobile'] ?? null),
            name: self::toString($data['name'] ?? null),
            sendEmail: isset($data['sendEmail']) ? self::toBool($data['sendEmail']) : null,
            sendEmailIfSelected: isset($data['sendEmailIfSelected']) ? self::toBool($data['sendEmailIfSelected']) : null,
            sendFax: isset($data['sendFax']) ? self::toBool($data['sendFax']) : null,
            sendFaxIfSelected: isset($data['sendFaxIfSelected']) ? self::toBool($data['sendFaxIfSelected']) : null,
            syncToApp: isset($data['syncToApp']) ? self::toBool($data['syncToApp']) : null,
            telefax: self::toString($data['telefax'] ?? null),
            telephone: self::toString($data['telephone'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customFields' => $this->customFields,
            'email' => $this->email,
            'gender' => $this->gender,
            'info' => $this->info,
            'labeling' => $this->labeling,
            'mobile' => $this->mobile,
            'name' => $this->name,
            'sendEmail' => $this->sendEmail,
            'sendEmailIfSelected' => $this->sendEmailIfSelected,
            'sendFax' => $this->sendFax,
            'sendFaxIfSelected' => $this->sendFaxIfSelected,
            'syncToApp' => $this->syncToApp,
            'telefax' => $this->telefax,
            'telephone' => $this->telephone
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getModified(): ?string { return $this->modified; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getTemporary(): ?bool { return $this->temporary; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getEmail(): ?string { return $this->email; }
    public function getGender(): ?string { return $this->gender; }
    public function getInfo(): ?string { return $this->info; }
    public function getLabeling(): ?string { return $this->labeling; }
    public function getMobile(): ?string { return $this->mobile; }
    public function getName(): ?string { return $this->name; }
    public function getSendEmail(): ?bool { return $this->sendEmail; }
    public function getSendEmailIfSelected(): ?bool { return $this->sendEmailIfSelected; }
    public function getSendFax(): ?bool { return $this->sendFax; }
    public function getSendFaxIfSelected(): ?bool { return $this->sendFaxIfSelected; }
    public function isSyncToApp(): ?bool { return $this->syncToApp; }
    public function getTelefax(): ?string { return $this->telefax; }
    public function getTelephone(): ?string { return $this->telephone; }
}