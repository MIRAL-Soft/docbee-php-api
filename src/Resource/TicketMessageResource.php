<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TicketMessageDTO;

/**
 * Provides access to messages ("Kommentare") on a Docbee ticket.
 *
 * In the Docbee UI these appear as the comment/activity thread on a ticket.
 * The API calls them "messages"; the UI calls them "Kommentare".
 *
 * Supports: list, find, add (POST), update (PUT), delete (DELETE).
 *
 * ```php
 * // Check whether a ticket has any comments (e.g. to protect it from cleanup)
 * if ($client->ticketMessages($ticketId)->hasMessages()) {
 *     // ticket has activity — do not clean up
 * }
 *
 * // List all comments
 * foreach ($client->ticketMessages($ticketId)->cursor() as $msg) {
 *     echo $msg->getCreated() . '  ' . $msg->getSender() . ': ' . $msg->getContent();
 * }
 *
 * // Add an internal comment
 * $client->ticketMessages($ticketId)->add('Kunde angerufen.', internal: true);
 * ```
 *
 * @extends AbstractResource<TicketMessageDTO>
 */
final class TicketMessageResource extends AbstractResource
{
    protected string $dtoClass = TicketMessageDTO::class;
    protected string $listKey  = 'ticketMessage';

    public function __construct(HttpClientInterface $http, int $ticketId)
    {
        $this->endpoint = "ticket/{$ticketId}/message";
        parent::__construct($http);
    }

    /**
     * Adds a new message (Kommentar) to the ticket.
     *
     * @param string      $content  Message body (required).
     * @param string|null $subject  Optional subject line.
     * @param bool        $internal Whether the message is internal (visible to staff only).
     *                             Default: false (visible to customer).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function add(string $content, ?string $subject = null, bool $internal = false): TicketMessageDTO
    {
        $payload = array_filter(
            ['content' => $content, 'subject' => $subject, 'internal' => $internal],
            fn($v) => $v !== null && $v !== false,
        );
        $payload['content']  = $content;  // always include even if empty string
        $payload['internal'] = $internal; // always include (boolean)

        return TicketMessageDTO::fromArray($this->http->post($this->endpoint, $payload));
    }

    /**
     * Returns true when the ticket has at least one message (Kommentar).
     *
     * Useful as a quick activity check without loading all messages.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function hasMessages(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Returns the total number of messages (Kommentare) on this ticket.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function countMessages(): int
    {
        return $this->count();
    }
}