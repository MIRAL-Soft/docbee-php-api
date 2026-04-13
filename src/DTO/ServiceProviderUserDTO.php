<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee ServiceProviderUser record.
 */
final class ServiceProviderUserDTO extends AbstractDTO
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?string $link,
        private readonly ?int $serviceProvider,
        private ?array $customFields,
        private ?string $email,
        private ?bool $enabled,
        private ?string $mobile,
        private ?string $name,
        private ?string $password,
        private ?int $permissionGroup,
        private ?string $shorthandName,
        private ?string $telephone,
        private ?bool $use2FA,
        private ?string $username
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            serviceProvider: self::toInt($data['serviceProvider'] ?? null),
            customFields: isset($data['customFields']) && is_array($data['customFields']) ? $data['customFields'] : null,
            email: self::toString($data['email'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            mobile: self::toString($data['mobile'] ?? null),
            name: self::toString($data['name'] ?? null),
            password: self::toString($data['password'] ?? null),
            permissionGroup: self::toInt($data['permissionGroup'] ?? null),
            shorthandName: self::toString($data['shorthandName'] ?? null),
            telephone: self::toString($data['telephone'] ?? null),
            use2FA: isset($data['use2FA']) ? self::toBool($data['use2FA']) : null,
            username: self::toString($data['username'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'customFields' => $this->customFields,
            'email' => $this->email,
            'enabled' => $this->enabled,
            'mobile' => $this->mobile,
            'name' => $this->name,
            'password' => $this->password,
            'permissionGroup' => $this->permissionGroup,
            'shorthandName' => $this->shorthandName,
            'telephone' => $this->telephone,
            'use2FA' => $this->use2FA,
            'username' => $this->username
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getServiceProvider(): ?int { return $this->serviceProvider; }
    public function getCustomFields(): ?array { return $this->customFields; }
    public function getEmail(): ?string { return $this->email; }
    public function getEnabled(): ?bool { return $this->enabled; }
    public function getMobile(): ?string { return $this->mobile; }
    public function getName(): ?string { return $this->name; }
    public function getPassword(): ?string { return $this->password; }
    public function getPermissionGroup(): ?int { return $this->permissionGroup; }
    public function getShorthandName(): ?string { return $this->shorthandName; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function getUse2FA(): ?bool { return $this->use2FA; }
    public function getUsername(): ?string { return $this->username; }
}