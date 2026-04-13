<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee UserActivity record.
 */
final class UserActivityDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific userActivity */
        private readonly ?int $id,
        /** REST API Link */
        private readonly ?string $link,
        /** processed */
        private readonly ?bool $processed,
        /** Email of a customerContact. Only used if customer, customerLocation and customerContact is not provided. */
        private ?string $contactEmail,
        /** Telephone or mobile number of a customerContact. Only used if customer, customerLocation and customerContact is not provided. */
        private ?string $contactPhoneNumber,
        /** customer identifier */
        private ?int $customer,
        /** customerContact identifier */
        private ?int $customerContact,
        /** customerLocation identifier */
        private ?int $customerLocation,
        /** daily */
        private ?bool $daily,
        /** description */
        private ?string $description,
        /** duration in milliseconds */
        private ?int $duration,
        /** startDate */
        private ?string $startDate,
        /** title */
        private ?string $title,
        /** type */
        private ?string $type,
        /** url */
        private ?string $url,
        /** User identifier. If not provided the identifier of the logged in user. */
        private ?int $user,
        /** Email of a user. Only used if user is not provided. */
        private ?string $userEmail,
        /** Telephone or mobile number of a user. Only used if user is not provided. */
        private ?string $userPhoneNumber
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id: self::toInt($data['id'] ?? null),
            link: self::toString($data['link'] ?? null),
            processed: isset($data['processed']) ? self::toBool($data['processed']) : null,
            contactEmail: self::toString($data['contactEmail'] ?? null),
            contactPhoneNumber: self::toString($data['contactPhoneNumber'] ?? null),
            customer: self::toInt($data['customer'] ?? null),
            customerContact: self::toInt($data['customerContact'] ?? null),
            customerLocation: self::toInt($data['customerLocation'] ?? null),
            daily: isset($data['daily']) ? self::toBool($data['daily']) : null,
            description: self::toString($data['description'] ?? null),
            duration: self::toInt($data['duration'] ?? null),
            startDate: self::toString($data['startDate'] ?? null),
            title: self::toString($data['title'] ?? null),
            type: self::toString($data['type'] ?? null),
            url: self::toString($data['url'] ?? null),
            user: self::toInt($data['user'] ?? null),
            userEmail: self::toString($data['userEmail'] ?? null),
            userPhoneNumber: self::toString($data['userPhoneNumber'] ?? null)
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'contactEmail' => $this->contactEmail,
            'contactPhoneNumber' => $this->contactPhoneNumber,
            'customer' => $this->customer,
            'customerContact' => $this->customerContact,
            'customerLocation' => $this->customerLocation,
            'daily' => $this->daily,
            'description' => $this->description,
            'duration' => $this->duration,
            'startDate' => $this->startDate,
            'title' => $this->title,
            'type' => $this->type,
            'url' => $this->url,
            'user' => $this->user,
            'userEmail' => $this->userEmail,
            'userPhoneNumber' => $this->userPhoneNumber
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int { return $this->id; }
    public function getLink(): ?string { return $this->link; }
    public function getProcessed(): ?bool { return $this->processed; }
    public function getContactEmail(): ?string { return $this->contactEmail; }
    public function getContactPhoneNumber(): ?string { return $this->contactPhoneNumber; }
    public function getCustomer(): ?int { return $this->customer; }
    public function getCustomerContact(): ?int { return $this->customerContact; }
    public function getCustomerLocation(): ?int { return $this->customerLocation; }
    public function getDaily(): ?bool { return $this->daily; }
    public function getDescription(): ?string { return $this->description; }
    public function getDuration(): ?int { return $this->duration; }
    public function getStartDate(): ?string { return $this->startDate; }
    public function getTitle(): ?string { return $this->title; }
    public function getType(): ?string { return $this->type; }
    public function getUrl(): ?string { return $this->url; }
    public function getUser(): ?int { return $this->user; }
    public function getUserEmail(): ?string { return $this->userEmail; }
    public function getUserPhoneNumber(): ?string { return $this->userPhoneNumber; }
}