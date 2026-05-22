<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DocBeeDocumentMessageDTO;

/**
 * Provides access to messages ("Kommentare") on a Docbee document (Leistung).
 *
 * In the Docbee UI these appear as the comment thread on a Leistung.
 * The API calls them "messages"; the UI calls them "Kommentare".
 *
 * **API limitation (confirmed by spec):** This endpoint supports only
 * GET (list/single) and POST (add).  `update()` and `delete()` inherited
 * from `AbstractResource` are NOT available on this endpoint — calling them
 * will result in HTTP 404 or 405.  Use `TicketMessageResource` if you need
 * full CRUD on messages.
 *
 * ```php
 * // Check whether a document has any comments
 * if ($client->documentMessages($documentId)->hasMessages()) {
 *     // document has activity — do not clean up
 * }
 *
 * // List all comments
 * foreach ($client->documentMessages($documentId)->cursor() as $msg) {
 *     echo $msg->getCreated() . '  ' . $msg->getSender() . ': ' . $msg->getContent();
 * }
 *
 * // Add a comment
 * $client->documentMessages($documentId)->add('Erledigt.', internal: true);
 * ```
 *
 * @extends AbstractResource<DocBeeDocumentMessageDTO>
 */
final class DocBeeDocumentMessageResource extends AbstractResource
{
    protected string $dtoClass = DocBeeDocumentMessageDTO::class;
    protected string $listKey  = 'docBeeDocumentMessage';

    public function __construct(HttpClientInterface $http, int $documentId)
    {
        $this->endpoint = "docBeeDocument/{$documentId}/message";
        parent::__construct($http);
    }

    /**
     * Adds a new message (Kommentar) to the document.
     *
     * @param string      $content  Message body (required).
     * @param string|null $subject  Optional subject line.
     * @param bool        $internal Whether the message is internal (visible to staff only).
     *                             Default: false (visible to customer).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function add(string $content, ?string $subject = null, bool $internal = false): DocBeeDocumentMessageDTO
    {
        $payload             = [];
        $payload['content']  = $content;
        $payload['internal'] = $internal;
        if ($subject !== null) {
            $payload['subject'] = $subject;
        }

        return DocBeeDocumentMessageDTO::fromArray($this->http->post($this->endpoint, $payload));
    }

    /**
     * Returns true when the document has at least one message (Kommentar).
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
     * Returns the total number of messages (Kommentare) on this document.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function countMessages(): int
    {
        return $this->count();
    }
}