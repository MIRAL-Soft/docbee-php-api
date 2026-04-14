<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents Docbee MandantSettings.
 */
final class MandantSettingsDTO extends AbstractDTO
{
    public function __construct(
        /** REST API Link */
        private readonly ?string $link,
        /** minimum password length */
        private ?int $passwordMinLength,
        /** minimum uppercase characters in password */
        private ?int $passwordMinUppers,
        /** minimum lowercase characters in password */
        private ?int $passwordMinLowers,
        /** minimum digit characters in password */
        private ?int $passwordMinDigits,
        /** minimum special characters in password */
        private ?int $passwordMinSpecials,
        /** time string format */
        private ?string $timeStringFormat,
        /** hours in a person day */
        private ?int $hoursInPersonDay,
        /** person days in a person week */
        private ?int $personDaysInPersonWeek,
        /** admin user reference */
        private ?int $adminUser
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            link: self::toString($data['link'] ?? null),
            passwordMinLength: self::toInt($data['passwordMinLength'] ?? null),
            passwordMinUppers: self::toInt($data['passwordMinUppers'] ?? null),
            passwordMinLowers: self::toInt($data['passwordMinLowers'] ?? null),
            passwordMinDigits: self::toInt($data['passwordMinDigits'] ?? null),
            passwordMinSpecials: self::toInt($data['passwordMinSpecials'] ?? null),
            timeStringFormat: self::toString($data['timeStringFormat'] ?? null),
            hoursInPersonDay: self::toInt($data['hoursInPersonDay'] ?? null),
            personDaysInPersonWeek: self::toInt($data['personDaysInPersonWeek'] ?? null),
            adminUser: self::toInt($data['adminUser'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'passwordMinLength' => $this->passwordMinLength,
            'passwordMinUppers' => $this->passwordMinUppers,
            'passwordMinLowers' => $this->passwordMinLowers,
            'passwordMinDigits' => $this->passwordMinDigits,
            'passwordMinSpecials' => $this->passwordMinSpecials,
            'timeStringFormat' => $this->timeStringFormat,
            'hoursInPersonDay' => $this->hoursInPersonDay,
            'personDaysInPersonWeek' => $this->personDaysInPersonWeek,
            'adminUser' => $this->adminUser,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return null; }
    public function getLink(): ?string { return $this->link; }
    public function getPasswordMinLength(): ?int { return $this->passwordMinLength; }
    public function getPasswordMinUppers(): ?int { return $this->passwordMinUppers; }
    public function getPasswordMinLowers(): ?int { return $this->passwordMinLowers; }
    public function getPasswordMinDigits(): ?int { return $this->passwordMinDigits; }
    public function getPasswordMinSpecials(): ?int { return $this->passwordMinSpecials; }
    public function getTimeStringFormat(): ?string { return $this->timeStringFormat; }
    public function getHoursInPersonDay(): ?int { return $this->hoursInPersonDay; }
    public function getPersonDaysInPersonWeek(): ?int { return $this->personDaysInPersonWeek; }
    public function getAdminUser(): ?int { return $this->adminUser; }
}
