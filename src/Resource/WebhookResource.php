<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use InvalidArgumentException;
use miralsoft\docbee\api\DTO\WebhookDTO;
use miralsoft\docbee\api\DTO\WebhookLinkDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee webhook subscriptions.
 *
 * Webhooks allow external systems to be notified when events occur in Docbee.
 * After creating a webhook, use {@see createLink()} to generate a personalised
 * URL for a specific owner / customer.
 *
 * Available event types (use the TYPE_* constants):
 *  - {@see TYPE_CREATE_DOCUMENT}       – a new document is created
 *  - {@see TYPE_CREATE_TICKET}         – a new ticket is created
 *  - {@see TYPE_EDIT_PROTOCOL}         – a protocol is edited
 *  - {@see TYPE_CREATE_TICKET_MESSAGE} – a message is added to a ticket
 *  - {@see TYPE_EXECUTE_RULE_ENGINE}   – the rule engine is executed
 *  - {@see TYPE_CONTAINER}             – container event
 *
 * ```php
 * $webhook = $client->webhooks()->register(
 *     name:        'New Ticket Notification',
 *     type:        WebhookResource::TYPE_CREATE_TICKET,
 *     withEmail:   true,
 * );
 *
 * $link = $client->webhooks()->createLink($webhook->getId(), ownerId: 1, customerId: 42);
 * echo $link->getLink(); // share this URL with the customer
 * ```
 *
 * @extends AbstractResource<WebhookDTO>
 */
final class WebhookResource extends AbstractResource
{
    // -------------------------------------------------------------------------
    // Webhook type constants
    // -------------------------------------------------------------------------

    /** Event fired when a new document is created. */
    public const string TYPE_CREATE_DOCUMENT = 'CREATE_DOCUMENT';

    /** Event fired when a new ticket is created. */
    public const string TYPE_CREATE_TICKET = 'CREATE_TICKET';

    /** Event fired when a protocol is edited. */
    public const string TYPE_EDIT_PROTOCOL = 'EDIT_PROTOCOL';

    /** Event fired when a message is added to a ticket. */
    public const string TYPE_CREATE_TICKET_MESSAGE = 'CREATE_TICKET_MESSAGE';

    /** Event fired when the rule engine is executed. */
    public const string TYPE_EXECUTE_RULE_ENGINE = 'EXECUTE_RULE_ENGINE';

    /** Container webhook event. */
    public const string TYPE_CONTAINER = 'CONTAINER';

    /** All allowed webhook type values.
     * @var list<string>
     */
    public const array TYPES = [
        self::TYPE_CREATE_DOCUMENT,
        self::TYPE_CREATE_TICKET,
        self::TYPE_EDIT_PROTOCOL,
        self::TYPE_CREATE_TICKET_MESSAGE,
        self::TYPE_EXECUTE_RULE_ENGINE,
        self::TYPE_CONTAINER,
    ];

    protected string $endpoint = 'webhook';
    protected string $dtoClass = WebhookDTO::class;
    protected string $listKey  = 'webhook';

    // -------------------------------------------------------------------------
    // Convenience registration method
    // -------------------------------------------------------------------------

    /**
     * Registers a new webhook subscription.
     *
     * @param string      $name                 Human-readable name for this webhook.
     * @param string      $type                 One of the TYPE_* constants.
     * @param int|null    $docBeeDocumentTemplate  Optional template ID for CREATE_DOCUMENT.
     * @param int|null    $ticketTemplate        Optional template ID for CREATE_TICKET.
     * @param int|null    $protocolTemplate      Optional protocol template ID.
     * @param bool        $withForm              Whether to include a form.
     * @param bool        $withEmail             Whether to send an email notification.
     * @param bool        $withAttachment        Whether to include attachments.
     * @param int|null    $threshold             Optional threshold value.
     * @param string      $successText           Text shown on success.
     * @param string      $redirectUrl           URL to redirect to after submission.
     * @param int|null    $redirectWebhook       Optional redirect webhook ID.
     * @param int|null    $ruleEngineActionId    Optional rule engine action ID.
     *
     * @throws InvalidArgumentException when an unknown type is provided.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function register(
        string  $name,
        string  $type,
        ?int    $docBeeDocumentTemplate = null,
        ?int    $ticketTemplate         = null,
        ?int    $protocolTemplate       = null,
        bool    $withForm               = false,
        bool    $withEmail              = false,
        bool    $withAttachment         = false,
        ?int    $threshold              = null,
        string  $successText            = '',
        string  $redirectUrl            = '',
        ?int    $redirectWebhook        = null,
        ?int    $ruleEngineActionId     = null,
    ): WebhookDTO {
        if (trim($name) === '') {
            throw new InvalidArgumentException('Webhook name must not be empty.');
        }
        if (!in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException(
                "Invalid webhook type '{$type}'. Allowed: " . implode(', ', self::TYPES)
            );
        }

        $data = array_filter([
            'name'                   => $name,
            'type'                   => $type,
            'docBeeDocumentTemplate' => $docBeeDocumentTemplate,
            'ticketTemplate'         => $ticketTemplate,
            'protocolTemplate'       => $protocolTemplate,
            'withForm'               => $withForm,
            'withEmail'              => $withEmail,
            'withAttachment'         => $withAttachment,
            'threshold'              => $threshold,
            'successText'            => $successText,
            'redirectUrl'            => $redirectUrl,
            'redirectWebhook'        => $redirectWebhook,
            'ruleEngineActionId'     => $ruleEngineActionId,
        ], fn($v) => $v !== null && $v !== '');

        return $this->create($data);
    }

    // -------------------------------------------------------------------------
    // Link management
    // -------------------------------------------------------------------------

    /**
     * Creates a personalised webhook link for a specific owner and optional customer.
     *
     * The returned {@see WebhookLinkDTO::getLink()} URL can be shared directly
     * with the end-user or customer to submit data via this webhook.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function createLink(
        int  $webhookId,
        int  $ownerId,
        ?int $customerId         = null,
        ?int $customerLocationId = null,
        ?int $customerContactId  = null,
        ?int $customerObjectId   = null,
    ): WebhookLinkDTO {
        $data = array_filter([
            'owner'            => $ownerId,
            'customer'         => $customerId,
            'customerLocation' => $customerLocationId,
            'customerContact'  => $customerContactId,
            'customerObject'   => $customerObjectId,
        ], fn($v) => $v !== null);

        $response = $this->http->post("webhook/{$webhookId}/link", $data);
        return WebhookLinkDTO::fromArray($response);
    }

    /**
     * Returns all registered webhooks.
     *
     * @return list<WebhookDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function all(): array
    {
        return $this->listAll();
    }

    /**
     * Finds webhooks by event type.
     *
     * @return list<WebhookDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByType(string $type): array
    {
        return $this->list(QueryBuilder::new()->filterEq('type', $type));
    }

    /**
     * @return array<string, mixed>
     */
    public function generateLink(int $webhookId): array { return $this->http->post("webhook/{$webhookId}/generateLink"); }
}
