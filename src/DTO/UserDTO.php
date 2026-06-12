<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee User record.
 */
final class UserDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific user */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** @var list<int>|null departmentProfile identifiers */
        private ?array $assignableDepartmentProfiles,
        /** @var list<int>|null assignableUserProfile identifiers */
        private ?array $assignableUserProfiles,
        /** CalendarEnabled defines if the an external calendar connection for these user is enabled. Property only available if calendar connection is enabled in DocBee. */
        private ?bool $calendarEnabled,
        /** @var list<int>|null confidentialTag identifiers */
        private ?array $confidentialTags,
        /** dailyClosingConfig identifier */
        private ?int $dailyClosingConfig,
        /** dailyClosingStartDate */
        private ?string $dailyClosingStartDate,
        /** department identifier */
        private ?int $department,
        /** @var list<int>|null departmentProfile identifiers */
        private ?array $departmentProfiles,
        /** email */
        private ?string $email,
        /** enabled */
        private ?bool $enabled,
        /** externalErpNumber */
        private ?string $externalErpNumber,
        /** mobile */
        private ?string $mobile,
        /** name */
        private ?string $name,
        /** password */
        private ?string $password,
        /** permissionGroup identifier */
        private ?int $permissionGroup,
        /** @var list<int>|null presetProfile identifiers */
        private ?array $presetProfiles,
        /** profile image file identifier */
        private ?int $profileImage,
        /** @var list<int>|null protocolTemplateProfile identifiers */
        private ?array $protocolTemplateProfiles,
        /** @var list<int>|null serviceTypeProfile identifiers */
        private ?array $serviceTypeProfiles,
        /** shorthandName */
        private ?string $shorthandName,
        /** @var list<int>|null skill identifiers */
        private ?array $skills,
        /** is the user a system user (not selectable) */
        private ?bool $systemUser,
        /** telephone */
        private ?string $telephone,
        /** @var list<int>|null ticketBoardProfile identifiers */
        private ?array $ticketBoardProfiles,
        /** use2FA */
        private ?bool $use2FA,
        /** @var list<int>|null userProfile identifiers */
        private ?array $userProfiles,
        /** username */
        private ?string $username,
        /** is time recoding enabled for this user */
        private ?bool $withTimeRecord
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            assignableDepartmentProfiles: isset($data['assignableDepartmentProfiles']) && is_array($data['assignableDepartmentProfiles']) ? $data['assignableDepartmentProfiles'] : null,
            assignableUserProfiles: isset($data['assignableUserProfiles']) && is_array($data['assignableUserProfiles']) ? $data['assignableUserProfiles'] : null,
            calendarEnabled: isset($data['calendarEnabled']) ? self::toBool($data['calendarEnabled']) : null,
            confidentialTags: isset($data['confidentialTags']) && is_array($data['confidentialTags']) ? $data['confidentialTags'] : null,
            dailyClosingConfig: self::toInt($data['dailyClosingConfig'] ?? null),
            dailyClosingStartDate: self::toString($data['dailyClosingStartDate'] ?? null),
            department: self::toInt($data['department'] ?? null),
            departmentProfiles: isset($data['departmentProfiles']) && is_array($data['departmentProfiles']) ? $data['departmentProfiles'] : null,
            email: self::toString($data['email'] ?? null),
            enabled: isset($data['enabled']) ? self::toBool($data['enabled']) : null,
            externalErpNumber: self::toString($data['externalErpNumber'] ?? null),
            mobile: self::toString($data['mobile'] ?? null),
            name: self::toString($data['name'] ?? null),
            password: self::toString($data['password'] ?? null),
            permissionGroup: self::toInt($data['permissionGroup'] ?? null),
            presetProfiles: isset($data['presetProfiles']) && is_array($data['presetProfiles']) ? $data['presetProfiles'] : null,
            profileImage: self::toInt($data['profileImage'] ?? null),
            protocolTemplateProfiles: isset($data['protocolTemplateProfiles']) && is_array($data['protocolTemplateProfiles']) ? $data['protocolTemplateProfiles'] : null,
            serviceTypeProfiles: isset($data['serviceTypeProfiles']) && is_array($data['serviceTypeProfiles']) ? $data['serviceTypeProfiles'] : null,
            shorthandName: self::toString($data['shorthandName'] ?? null),
            skills: isset($data['skills']) && is_array($data['skills']) ? $data['skills'] : null,
            systemUser: isset($data['systemUser']) ? self::toBool($data['systemUser']) : null,
            telephone: self::toString($data['telephone'] ?? null),
            ticketBoardProfiles: isset($data['ticketBoardProfiles']) && is_array($data['ticketBoardProfiles']) ? $data['ticketBoardProfiles'] : null,
            use2FA: isset($data['use2FA']) ? self::toBool($data['use2FA']) : null,
            userProfiles: isset($data['userProfiles']) && is_array($data['userProfiles']) ? $data['userProfiles'] : null,
            username: self::toString($data['username'] ?? null),
            withTimeRecord: isset($data['withTimeRecord']) ? self::toBool($data['withTimeRecord']) : null
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter([
            'assignableDepartmentProfiles' => $this->assignableDepartmentProfiles,
            'assignableUserProfiles' => $this->assignableUserProfiles,
            'calendarEnabled' => $this->calendarEnabled,
            'confidentialTags' => $this->confidentialTags,
            'dailyClosingConfig' => $this->dailyClosingConfig,
            'dailyClosingStartDate' => $this->dailyClosingStartDate,
            'department' => $this->department,
            'departmentProfiles' => $this->departmentProfiles,
            'email' => $this->email,
            'enabled' => $this->enabled,
            'externalErpNumber' => $this->externalErpNumber,
            'mobile' => $this->mobile,
            'name' => $this->name,
            'password' => $this->password,
            'permissionGroup' => $this->permissionGroup,
            'presetProfiles' => $this->presetProfiles,
            'profileImage' => $this->profileImage,
            'protocolTemplateProfiles' => $this->protocolTemplateProfiles,
            'serviceTypeProfiles' => $this->serviceTypeProfiles,
            'shorthandName' => $this->shorthandName,
            'skills' => $this->skills,
            'systemUser' => $this->systemUser,
            'telephone' => $this->telephone,
            'ticketBoardProfiles' => $this->ticketBoardProfiles,
            'use2FA' => $this->use2FA,
            'userProfiles' => $this->userProfiles,
            'username' => $this->username,
            'withTimeRecord' => $this->withTimeRecord
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    /** @return list<int>|null */
    public function getAssignableDepartmentProfiles(): ?array { return $this->assignableDepartmentProfiles; }
    /** @return list<int>|null */
    public function getAssignableUserProfiles(): ?array { return $this->assignableUserProfiles; }
    public function getCalendarEnabled(): ?bool { return $this->calendarEnabled; }
    /** @return list<int>|null */
    public function getConfidentialTags(): ?array { return $this->confidentialTags; }
    public function getDailyClosingConfig(): ?int { return $this->dailyClosingConfig; }
    public function getDailyClosingStartDate(): ?string { return $this->dailyClosingStartDate; }
    public function getDepartment(): ?int { return $this->department; }
    /** @return list<int>|null */
    public function getDepartmentProfiles(): ?array { return $this->departmentProfiles; }
    public function getEmail(): ?string { return $this->email; }
    public function getEnabled(): ?bool { return $this->enabled; }
    public function getExternalErpNumber(): ?string { return $this->externalErpNumber; }
    public function getMobile(): ?string { return $this->mobile; }
    public function getName(): ?string { return $this->name; }
    public function getPassword(): ?string { return $this->password; }
    public function getPermissionGroup(): ?int { return $this->permissionGroup; }
    /** @return list<int>|null */
    public function getPresetProfiles(): ?array { return $this->presetProfiles; }
    public function getProfileImage(): ?int { return $this->profileImage; }
    /** @return list<int>|null */
    public function getProtocolTemplateProfiles(): ?array { return $this->protocolTemplateProfiles; }
    /** @return list<int>|null */
    public function getServiceTypeProfiles(): ?array { return $this->serviceTypeProfiles; }
    public function getShorthandName(): ?string { return $this->shorthandName; }
    /** @return list<int>|null */
    public function getSkills(): ?array { return $this->skills; }
    public function getSystemUser(): ?bool { return $this->systemUser; }
    public function getTelephone(): ?string { return $this->telephone; }
    /** @return list<int>|null */
    public function getTicketBoardProfiles(): ?array { return $this->ticketBoardProfiles; }
    public function getUse2FA(): ?bool { return $this->use2FA; }
    /** @return list<int>|null */
    public function getUserProfiles(): ?array { return $this->userProfiles; }
    public function getUsername(): ?string { return $this->username; }
    public function getWithTimeRecord(): ?bool { return $this->withTimeRecord; }
}