<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Represents a Docbee webhook subscription.
 *
 * See {@see \miralsoft\docbee\api\Resource\WebhookResource} for available event types.
 */
final class WebhookDTO extends AbstractDTO
{
    public function __construct(
        /** Unique identifier representing a specific webhook */
        private readonly ?int    $id,
        /** name */
        private readonly ?string $name,
        /** type */
        private readonly ?string $type,
        /** docBeeDocumentTemplate id */
        private readonly ?int    $docBeeDocumentTemplate,
        /** ticketTemplate id */
        private readonly ?int    $ticketTemplate,
        /** protocolTemplate id */
        private readonly ?int    $protocolTemplate,
        /** withForm */
        private readonly ?bool   $withForm,
        /** withEmail */
        private readonly ?bool   $withEmail,
        /** withAttachment */
        private readonly ?bool   $withAttachment,
        /** threshold */
        private readonly ?int    $threshold,
        /** successText */
        private readonly ?string $successText,
        /** redirectUrl */
        private readonly ?string $redirectUrl,
        /** redirectWebhook id */
        private readonly ?int    $redirectWebhook,
        /** ruleEngineAction id */
        private readonly ?int    $ruleEngineActionId,
        /** REST API Link */
        private readonly ?string $link,
    ) {}

    #[Override]
    public static function fromArray(array $data): static
    {
        return new self(
            id:                      self::toInt($data['id'] ?? null),
            name:                    self::toString($data['name'] ?? null),
            type:                    self::toString($data['type'] ?? null),
            docBeeDocumentTemplate:  self::toInt($data['docBeeDocumentTemplate'] ?? null),
            ticketTemplate:          self::toInt($data['ticketTemplate'] ?? null),
            protocolTemplate:        self::toInt($data['protocolTemplate'] ?? null),
            withForm:                isset($data['withForm']) ? self::toBool($data['withForm']) : null,
            withEmail:               isset($data['withEmail']) ? self::toBool($data['withEmail']) : null,
            withAttachment:          isset($data['withAttachment']) ? self::toBool($data['withAttachment']) : null,
            threshold:               self::toInt($data['threshold'] ?? null),
            successText:             self::toString($data['successText'] ?? null),
            redirectUrl:             self::toString($data['redirectUrl'] ?? null),
            redirectWebhook:         self::toInt($data['redirectWebhook'] ?? null),
            ruleEngineActionId:      self::toInt($data['ruleEngineActionId'] ?? null),
            link:                    self::toString($data['link'] ?? null),
        );
    }

    #[Override]
    public function toArray(): array
    {
        return array_filter([
            'name'                   => $this->name,
            'type'                   => $this->type,
            'docBeeDocumentTemplate' => $this->docBeeDocumentTemplate,
            'ticketTemplate'         => $this->ticketTemplate,
            'protocolTemplate'       => $this->protocolTemplate,
            'withForm'               => $this->withForm,
            'withEmail'              => $this->withEmail,
            'withAttachment'         => $this->withAttachment,
            'threshold'              => $this->threshold,
            'successText'            => $this->successText,
            'redirectUrl'            => $this->redirectUrl,
            'redirectWebhook'        => $this->redirectWebhook,
            'ruleEngineActionId'     => $this->ruleEngineActionId,
        ], fn($v) => $v !== null);
    }

    public function getId(): ?int                      { return $this->id; }
    public function getName(): ?string                 { return $this->name; }
    public function getType(): ?string                 { return $this->type; }
    public function getDocBeeDocumentTemplate(): ?int  { return $this->docBeeDocumentTemplate; }
    public function getTicketTemplate(): ?int          { return $this->ticketTemplate; }
    public function getProtocolTemplate(): ?int        { return $this->protocolTemplate; }
    public function isWithForm(): ?bool                { return $this->withForm; }
    public function isWithEmail(): ?bool               { return $this->withEmail; }
    public function isWithAttachment(): ?bool          { return $this->withAttachment; }
    public function getThreshold(): ?int               { return $this->threshold; }
    public function getSuccessText(): ?string          { return $this->successText; }
    public function getRedirectUrl(): ?string          { return $this->redirectUrl; }
    public function getRedirectWebhook(): ?int         { return $this->redirectWebhook; }
    public function getRuleEngineActionId(): ?int      { return $this->ruleEngineActionId; }

    /**
     * Returns the read-only generated webhook URL.
     * Only populated when retrieved from the API; null for newly created objects.
     */
    public function getLink(): ?string { return $this->link; }
}
