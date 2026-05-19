<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomFieldValueDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee documents (protocols / reports).
 *
 * @extends AbstractResource<DocBeeDocumentDTO>
 *
 * **Docbee filter quirks (confirmed by live tests against a 3 963-document tenant):**
 *
 * - Filtering by ticket: `ticket-eq=<id>` is silently ignored (returns all documents).
 *   Use {@see findByTicket()} which uses the documented `ticketIds` query parameter.
 *
 * - Filtering by `erpReferenceNumber`: no server-side filter exists (neither `-eq` nor
 *   plain form is recognised).  The Docbee document search also does not search
 *   `erpReferenceNumber`.  Use {@see findByErpReferenceNumber()} which performs a
 *   paginated scan scoped to a customer with `fields=id,erpReferenceNumber`.
 *
 * - `customer-eq=<id>` filters by company/organisation-level customer; the `customer`
 *   field returned in each document may refer to a child entity (location/contact)
 *   with a different numeric ID.  This is correct Docbee behaviour.
 *
 * - Searching by `externalReferenceNumber`: the `/findByExternalId/{val}` endpoint
 *   does NOT map to `externalReferenceNumber`, `erpReferenceNumber`, or
 *   `referenceNumber` — it searches an internal integration field that is not settable
 *   via the standard REST API.  Use custom fields + {@see findByCustomFieldValue()}
 *   as a unique-key lookup instead.
 */
final class DocumentResource extends AbstractResource
{
    protected string $endpoint = 'docBeeDocument';
    protected string $dtoClass = DocBeeDocumentDTO::class;
    protected string $listKey  = 'docBeeDocument';

    /**
     * Returns all documents for a given customer.
     *
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomer(int $customerId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('customer', $customerId));
    }

    /**
     * Returns all documents based on a given template.
     *
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByTemplate(int $templateId): array
    {
        return $this->listAll(QueryBuilder::new()->filterEq('template', $templateId));
    }

    /**
     * Returns all documents linked to a specific ticket.
     *
     * Uses the `ticketIds` query parameter documented in the Docbee OpenAPI spec.
     * The seemingly equivalent `ticket-eq=<id>` form is silently ignored by the
     * Docbee API and returns all documents unfiltered — use this method instead.
     *
     * ```php
     * $docs = $client->documents()->findByTicket(261861);
     * // Returns only documents where getTicket() === 261861
     * ```
     *
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByTicket(int $ticketId): array
    {
        return $this->listAll(QueryBuilder::new()->param('ticketIds', $ticketId));
    }

    /**
     * Returns documents whose `erpReferenceNumber` matches the given value exactly.
     *
     * The Docbee API provides no server-side filter for `erpReferenceNumber` on the
     * `/docBeeDocument` endpoint, and the document full-text search also does not
     * include this field.  This method therefore performs a paginated client-side scan
     * scoped to a single customer, which keeps the result set manageable.
     *
     * **Performance:** proportional to the number of documents for that customer
     * (e.g. ~40 API calls for 3 963 documents at 100 per page, each ≈ 500 ms).
     * For large customers, consider caching or using a custom field as an index
     * together with {@see findByCustomFieldValue()}.
     *
     * ```php
     * $docs = $client->documents()->findByErpReferenceNumber(205023, 'WO-12345');
     * ```
     *
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByErpReferenceNumber(int $customerId, string $erpReferenceNumber): array
    {
        $results = [];

        foreach ($this->cursor(
            QueryBuilder::new()
                ->filterEq('customer', $customerId)
                ->fields(['id', 'erpReferenceNumber']),
        ) as $doc) {
            if ($doc->getErpReferenceNumber() === $erpReferenceNumber) {
                $results[] = $doc;
            }
        }

        return $results;
    }

    /**
     * Returns all documents for a customer with their custom field values pre-loaded.
     *
     * Unlike {@see findByCustomer()}, this method requests
     * `fields=id,customFields.id,customFields.value` so that each document in the
     * paginated list response already carries its custom field data — avoiding the
     * N+1 problem of calling {@see getCustomFieldValues()} per document.
     *
     * Documents that have no custom fields assigned return an empty `customFields`
     * array (the key will be absent from the raw API response, which is normalised
     * to `null` by the DTO).
     *
     * ```php
     * foreach ($client->documents()->cursorWithCustomFields(205023) as $doc) {
     *     $fields = $doc->getCustomFields() ?? [];
     *     // Each element is a CustomFieldValueDTO with getId() and getValue()
     * }
     * ```
     *
     * @return \Generator<int, DocBeeDocumentDTO, void, void>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function cursorWithCustomFields(int $customerId): \Generator
    {
        return $this->cursor(
            QueryBuilder::new()
                ->filterEq('customer', $customerId)
                ->fields(['id', 'customFields.id', 'customFields.value']),
        );
    }

    /**
     * Finds documents where the given custom field has the specified value.
     *
     * The Docbee API does not support server-side custom field filters — this method
     * performs a paginated scan of all documents for the given customer, fetching
     * custom field values in each page response (no N+1 calls).
     *
     * **Typical use case (weclapp↔Docbee sync):**
     * ```php
     * $docs = $client->documents()->findByCustomFieldValue(
     *     customerId:  205023,
     *     fieldId:     104,          // e.g. weclappOrderItemId custom field
     *     value:       'WO-12345',
     * );
     * // Returns all documents for customer 205023 where custom field 104 === 'WO-12345'
     * ```
     *
     * **Performance:** one API call per page (100 docs) — far better than the N+1
     * pattern of one call per document.  For a customer with 3 963 documents: ~40
     * page calls instead of 3 963 individual calls.
     *
     * Note: only documents where the custom field was explicitly assigned (via
     * `/docBeeDocument/customFields`) will return a value; other documents will have
     * `customFields = null` and will be skipped.
     *
     * @param int   $customerId Docbee customer ID to scope the search.
     * @param int   $fieldId    Custom field definition ID.
     * @param mixed $value      Value to match (strict equality).
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomFieldValue(int $customerId, int $fieldId, mixed $value): array
    {
        $results = [];

        foreach ($this->cursorWithCustomFields($customerId) as $doc) {
            foreach ((array) ($doc->getCustomFields() ?? []) as $cf) {
                if ($cf instanceof CustomFieldValueDTO
                    && $cf->getId() === $fieldId
                    // phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators
                    && $cf->getValue() === $value
                ) {
                    $results[] = $doc;
                    break;
                }
            }
        }

        return $results;
    }

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }

    /**
     * Returns all custom field values for a document as a `fieldId => value` map.
     *
     * Uses the dot-notation `?fields=customFields.id,customFields.value` which is
     * the only request form that makes the Docbee API return actual stored values.
     * (The plain `?fields=customFields` form returns only a list of field IDs.)
     *
     * Returns an empty array when no custom fields have been set.
     *
     * ```php
     * $values = $client->documents()->getCustomFieldValues($docId);
     * // e.g. [102 => 123, 104 => 'WO-12345']
     * echo $values[104]; // 'WO-12345'
     * ```
     *
     * @return array<int, mixed> Map of fieldId => value.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function getCustomFieldValues(int $docId): array
    {
        $response = $this->http->get(
            "{$this->endpoint}/{$docId}?fields=customFields.id,customFields.value"
        );

        $map = [];
        foreach ((array) ($response['customFields'] ?? []) as $entry) {
            if (is_array($entry) && isset($entry['id'])) {
                $map[(int) $entry['id']] = $entry['value'] ?? null;
            }
        }

        return $map;
    }

    /**
     * Returns the stored value of a single custom field on a document, or null when unset.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function getCustomFieldValue(int $docId, int $fieldId): mixed
    {
        return $this->getCustomFieldValues($docId)[$fieldId] ?? null;
    }

    /**
     * Returns the IDs of custom fields that have a non-null value on this document.
     *
     * Useful for a lightweight presence check without fetching actual values.
     * For reading the values themselves use {@see getCustomFieldValues()}.
     *
     * @return list<int>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function getCustomFieldIds(int $docId): array
    {
        $response = $this->http->get("{$this->endpoint}/{$docId}?fields=customFields");
        return array_map('intval', (array) ($response['customFields'] ?? []));
    }

    /**
     * Returns true when the given custom field has a non-null value on the document.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function hasCustomFieldValue(int $docId, int $fieldId): bool
    {
        return in_array($fieldId, $this->getCustomFieldIds($docId), true);
    }

    /**
     * Sets a single custom field value on a document.
     *
     * @param mixed $value Field value; type must match the field's configured type.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function setCustomFieldValue(int $docId, int $fieldId, mixed $value): void
    {
        $this->http->put("{$this->endpoint}/{$docId}", [
            'customFields' => [['id' => $fieldId, 'value' => $value]],
        ]);
    }

    /**
     * Sets multiple custom field values on a document in a single request.
     *
     * @param array<int, mixed> $valuesByFieldId Map of fieldId => value.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function setCustomFieldValues(int $docId, array $valuesByFieldId): void
    {
        if (empty($valuesByFieldId)) {
            return;
        }

        $customFields = [];
        foreach ($valuesByFieldId as $fieldId => $value) {
            $customFields[] = ['id' => (int) $fieldId, 'value' => $value];
        }

        $this->http->put("{$this->endpoint}/{$docId}", ['customFields' => $customFields]);
    }
    public function fromTemplate(int $templateId, array $data = []): DocBeeDocumentDTO { return DocBeeDocumentDTO::fromArray($this->http->post("{$this->endpoint}/fromTemplate", array_merge(['template' => $templateId], $data))); }
    public function findByNumber(string $number): DocBeeDocumentDTO { return DocBeeDocumentDTO::fromArray($this->http->get("{$this->endpoint}/findByNumber/{$number}")); }
    public function findByExternalId(string $externalId): DocBeeDocumentDTO { return DocBeeDocumentDTO::fromArray($this->http->get("{$this->endpoint}/findByExternalId/{$externalId}")); }
    public function clone(int $id): DocBeeDocumentDTO { return DocBeeDocumentDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/clone", [])); }
    public function approve(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/approve", $data); }
    public function finish(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/finish", $data); }
    public function cancel(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/cancel", $data); }
    public function cancelAndClone(int $id): DocBeeDocumentDTO { return DocBeeDocumentDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/cancelAndClone", [])); }
    public function invoice(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/invoice", $data); }
    public function preFinish(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/preFinish", $data); }
    public function releaseDraft(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/releaseDraft", []); }
    public function preview(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/preview"); }
    public function getMessageData(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/messageData"); }
    public function poke(int $id, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/poke", $data); }
    public function reply(int $id, int $messageId, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/reply/{$messageId}", $data); }
    public function forward(int $id, int $messageId, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/forward/{$messageId}", $data); }
    public function export(int $exportProfileId, array $params = []): array { return $this->http->get("{$this->endpoint}/export/{$exportProfileId}"); }
    public function exportByIds(int $exportProfileId, array $ids): array { return $this->http->post("{$this->endpoint}/exportByIds/{$exportProfileId}", ['ids' => $ids]); }
}
