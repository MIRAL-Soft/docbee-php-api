<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents the personalised URL returned after creating a webhook link.
 *
 * The {@see getLink()} URL can be shared with the end-user or embedded in emails.
 */
final class WebhookLinkDTO extends AbstractDTO
{
    public function __construct(
        /** link */
        private readonly ?string $link,
    ) {}

    #[\Override]
    public static function fromArray(array $data): static
    {
        return new self(
            link: self::toString($data['link'] ?? null),
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return array_filter(['link' => $this->link], fn($v) => $v !== null);
    }

    /** This DTO has no server-assigned ID. Always returns null. */
    public function getId(): ?int { return null; }

    /** Returns the personalised webhook URL. */
    public function getLink(): ?string { return $this->link; }
}
