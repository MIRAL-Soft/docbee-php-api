<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee CustomerUser record.
 */
final class CustomerUserDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $link,
        private ?int $customerContact,
        private ?array $customerProfiles,
        private ?string $email,
        private ?bool $enabled,
        private ?string $mobile,
        private ?string $name,
        private ?string $password,
        private ?int $permissionGroup,
        private ?string $shorthandName,
        private ?string $telephone,
        private ?array $ticketCategories,
        private ?bool $use2FA,
        private ?string $username
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerProfiles: isset($data['customerProfiles']) && is_array($data['customerProfiles']) ? $data['customerProfiles'] : null,
            email: self::toString($data['email'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            mobile: self::toString($data['mobile'] ?? null),
            name: self::toString($data['name'] ?? null),
            password: self::toString($data['password'] ?? null),
            permissionGroup: self::toInt($data['permissionGroup'] ?? null),
            shorthandName: self::toString($data['shorthandName'] ?? null),
            telephone: self::toString($data['telephone'] ?? null),
            ticketCategories: isset($data['ticketCategories']) && is_array($data['ticketCategories']) ? $data['ticketCategories'] : null,
            use2FA: isset($data['use2FA']) ? self::toBool($data['use2FA']) : null,
            username: self::toString($data['username'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customerContact' => $this->customerContact,
            'customerProfiles' => $this->customerProfiles,
            'email' => $this->email,
            'enabled' => $this->enabled,
            'mobile' => $this->mobile,
            'name' => $this->name,
            'password' => $this->password,
            'permissionGroup' => $this->permissionGroup,
            'shorthandName' => $this->shorthandName,
            'telephone' => $this->telephone,
            'ticketCategories' => $this->ticketCategories,
            'use2FA' => $this->use2FA,
            'username' => $this->username
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerProfiles(): ?array { return $this->customerProfiles; }
    public function getEmail(): ?string { return $this->email; }
    public function getEnabled(): ?bool { return $this->enabled; }
    public function getMobile(): ?string { return $this->mobile; }
    public function getName(): ?string { return $this->name; }
    public function getPassword(): ?string { return $this->password; }
    public function getPermissionGroup(): ?int { return $this->permissionGroup; }
    public function getShorthandName(): ?string { return $this->shorthandName; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function getTicketCategories(): ?array { return $this->ticketCategories; }
    public function getUse2FA(): ?bool { return $this->use2FA; }
    public function getUsername(): ?string { return $this->username; }
}