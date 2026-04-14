<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee User or Queue record.
 */
final class UserAndQueueDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific user or queue */
        private readonly ?int $id,
        /** name */
        private ?string $name,
        /** email address */
        private ?string $email,
        /** username */
        private ?string $username,
        /** shorthand name */
        private ?string $shorthandName,
        /** enabled state */
        private ?bool $enabled,
        /** mobile number */
        private ?string $mobile,
        /** telephone number */
        private ?string $telephone,
        /** permission group reference */
        private ?int $permissionGroup,
        /** external ERP number */
        private ?string $externalErpNumber,
        /** calendar enabled state */
        private ?bool $calendarEnabled,
        /** department reference */
        private ?int $department,
        /** profile image reference */
        private ?int $profileImage,
        /** service provider reference */
        private ?int $serviceProvider,
        /** user type */
        private ?string $userType
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            name: self::toString($data['name'] ?? null),
            email: self::toString($data['email'] ?? null),
            username: self::toString($data['username'] ?? null),
            shorthandName: self::toString($data['shorthandName'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            mobile: self::toString($data['mobile'] ?? null),
            telephone: self::toString($data['telephone'] ?? null),
            permissionGroup: self::toInt($data['permissionGroup'] ?? null),
            externalErpNumber: self::toString($data['externalErpNumber'] ?? null),
            calendarEnabled: isset($data['calendarEnabled']) ? self::toBool($data['calendarEnabled']) : null,
            department: self::toInt($data['department'] ?? null),
            profileImage: self::toInt($data['profileImage'] ?? null),
            serviceProvider: self::toInt($data['serviceProvider'] ?? null),
            userType: self::toString($data['userType'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'shorthandName' => $this->shorthandName,
            'enabled' => $this->enabled,
            'mobile' => $this->mobile,
            'telephone' => $this->telephone,
            'permissionGroup' => $this->permissionGroup,
            'externalErpNumber' => $this->externalErpNumber,
            'calendarEnabled' => $this->calendarEnabled,
            'department' => $this->department,
            'profileImage' => $this->profileImage,
            'serviceProvider' => $this->serviceProvider,
            'userType' => $this->userType,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function getEmail(): ?string { return $this->email; }
    public function getUsername(): ?string { return $this->username; }
    public function getShorthandName(): ?string { return $this->shorthandName; }
    public function isEnabled(): ?bool { return $this->enabled; }
    public function getMobile(): ?string { return $this->mobile; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function getPermissionGroup(): ?int { return $this->permissionGroup; }
    public function getExternalErpNumber(): ?string { return $this->externalErpNumber; }
    public function isCalendarEnabled(): ?bool { return $this->calendarEnabled; }
    public function getDepartment(): ?int { return $this->department; }
    public function getProfileImage(): ?int { return $this->profileImage; }
    public function getServiceProvider(): ?int { return $this->serviceProvider; }
    public function getUserType(): ?string { return $this->userType; }
}
