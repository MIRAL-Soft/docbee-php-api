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
 *
 * **Billing field clarification (live-verified):**
 *
 * - `erpReferenceNumber` on DocBeeDocumentDTO is **not** the billing number.
 *   It is an ERP integration reference (e.g. work-order number) that can only be set
 *   at document creation time — `update($id, ['erpReferenceNumber' => …])` is silently
 *   ignored (HTTP 200, value stays null).  In CSV exports it appears as the column
 *   "Vorgangs-Referenznummer".
 *
 * - The **billing number** ("Abrechnungsnummer") lives on the {@see InvoiceDTO} as
 *   `invoiceNumber` and is set via `invoices()->update($invoiceId, ['invoiceNumber' => …])`.
 *   Setting it also transitions the invoice status from `OPEN` → `INVOICED`.
 *   Use {@see \miralsoft\docbee\api\Resource\InvoiceResource::findByDocument()} to
 *   look up the Invoice record for a given document ID.
 *
 * - `invoiceNumber` on DocBeeDocumentDTO is a **read-only mirror** of the Invoice record's
 *   `invoiceNumber`.  It is populated automatically once the invoice is marked INVOICED and
 *   is handy for quick reads (no separate Invoice look-up needed).  Writing it via
 *   `documents()->update($id, ['invoiceNumber' => …])` has no effect.
 */
final class DocumentResource extends AbstractResource
{
    protected string $endpoint = 'docBeeDocument';
    protected string $dtoClass = DocBeeDocumentDTO::class;
    protected string $listKey  = 'docBeeDocument';

    /**
     * Fields requested automatically by {@see find()} when no explicit fields are passed.
     *
     * The Docbee API omits billing and status fields from the default single-record response.
     * `approved`, `finished`, `billable`, `invoiceNumber`, `erpReferenceNumber` and `ticket`
     * are all absent unless requested explicitly — making them silently null even when set.
     * This default set ensures `find($id)` always returns a fully usable document.
     *
     * @var array<string>
     */
    protected array $findFields = [
        'id', 'documentNumber', 'created', 'modified', 'link',
        'approved', 'approvedDate', 'approvedComment',
        'finished', 'finishedDate',
        'preFinished', 'drafted', 'canceled', 'canceledDate',
        'billable', 'invoiceNumber', 'erpReferenceNumber',
        'ticket', 'customer', 'customerLocation', 'customerContact',
        'releasedDate', 'personInCharge', 'priority', 'type',
        'sendMessage', 'needSignature', 'needFinishPin',
        'completedSuccessfully', 'totalInvoicePrice', 'totalTasksInvoicePrice',
        'tags',
    ];

    /**
     * `/docBeeDocument` supports up to at least 500 records per page (live-verified
     * 2026-05-23 on pcs tenant: 500 items returned correctly; timing 4,374ms for 4,000
     * docs vs. 8,443ms at 50/page).  Override cursor's conservative default of 100.
     *
     * @var int
     */
    protected int $defaultPageSize = 500;

    /**
     * Same as `$findFields` — ensures list/cursor/findModifiedSince/findCreatedSince
     * return DTOs that are as fully populated as a `find($id)` call.
     *
     * **Why:** The Docbee list endpoint omits billing/status fields by default.
     * Without this, `findModifiedSince()` would return documents where `getModified()`,
     * `getTicket()`, `getApproved()` etc. all return null — making delta-sync logic
     * silently broken (live-verified bug, 2026-05-27).
     *
     * Callers that need a slim projection (e.g. for performance) can still pass an
     * explicit `QueryBuilder::fields([...])` to override this default.
     *
     * @var array<string>
     */
    protected array $defaultListFields = [
        'id', 'documentNumber', 'created', 'modified', 'link',
        'approved', 'approvedDate', 'approvedComment',
        'finished', 'finishedDate',
        'preFinished', 'drafted', 'canceled', 'canceledDate',
        'billable', 'invoiceNumber', 'erpReferenceNumber',
        'ticket', 'customer', 'customerLocation', 'customerContact',
        'releasedDate', 'personInCharge', 'priority', 'type',
        'sendMessage', 'needSignature', 'needFinishPin',
        'completedSuccessfully', 'totalInvoicePrice', 'totalTasksInvoicePrice',
        'tags',
    ];

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
     * Returns all approved and billable documents for a given customer (Leistungen zur Abrechnung).
     *
     * Because the Docbee API does not support server-side filtering by `approved` or `billable`,
     * this method performs a paginated cursor scan with explicit field selection and filters
     * client-side.  The `approved`, `finished`, and `billable` fields are **absent** from the
     * default list response — this method requests them explicitly so the filter works correctly.
     *
     * ```php
     * $docs = $client->documents()->findApprovedBillable($customerId);
     * $ids  = array_map(fn($d) => $d->getId(), $docs);
     * $csv  = $client->documents()->exportByIds($profileId, $ids);
     * ```
     *
     * @return list<DocBeeDocumentDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findApprovedBillable(int $customerId): array
    {
        $query = QueryBuilder::new()
            ->filterEq('customer', $customerId)
            ->fields(['id', 'documentNumber', 'approved', 'finished', 'billable',
                      'invoiceNumber', 'erpReferenceNumber', 'ticket', 'customer']);

        $results = [];
        foreach ($this->cursor($query) as $doc) {
            if ($doc->getApproved() === true && $doc->getBillable() === true) {
                $results[] = $doc;
            }
        }
        return $results;
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

    /** @return array<string, mixed> */
    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
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

    /**
     * Adds a tag to a document, preserving any tags it already has.
     *
     * Read-modify-write over the generic {@see update()}: reads the document's current
     * `tags`, appends `$tagId` if absent, then writes the merged list back.  The Docbee
     * `tags` field is replace-on-write, so the merge step is what keeps existing tags.
     *
     * **Idempotent:** if the tag is already present, no request is sent.
     *
     * ```php
     * $client->documents()->addTag($docId, 42);
     * ```
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function addTag(int $docId, int $tagId): void
    {
        $current = $this->find($docId, ['id', 'tags'])->getTags() ?? [];
        if (in_array($tagId, $current, true)) {
            return; // already tagged — nothing to do
        }
        $current[] = $tagId;
        $this->update($docId, ['tags' => array_values($current)]);
    }

    /**
     * Removes a tag from a document, preserving its remaining tags.
     *
     * Read-modify-write over the generic {@see update()}: reads the document's current
     * `tags`, drops `$tagId`, then writes the remaining list back.
     *
     * **Idempotent:** if the tag is not present, no request is sent.
     *
     * ```php
     * $client->documents()->removeTag($docId, 42);
     * ```
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function removeTag(int $docId, int $tagId): void
    {
        $current = $this->find($docId, ['id', 'tags'])->getTags() ?? [];
        if (!in_array($tagId, $current, true)) {
            return; // not tagged — nothing to do
        }
        $merged = array_values(array_filter($current, static fn(int $id) => $id !== $tagId));
        $this->update($docId, ['tags' => $merged]);
    }

    /**
     * Creates a new document from a template.
     *
     * **Required payload key is `templateId`** — the seemingly obvious `template` key
     * is silently rejected with HTTP 400 "You must specify a templateId or a templateName".
     * Alternatively pass `['templateName' => 'My Template']` in `$data` and omit the
     * `$templateId` argument (pass `0`).
     *
     * **Accepted `$data` fields (confirmed by live test):**
     * - `customer` (int) — applied to the created document ✓
     *
     * **Silently ignored `$data` fields (confirmed by live test):**
     * - `ticket` — HTTP 400 "Unknown error" when passed to this endpoint
     * - `erpReferenceNumber` — accepted without error but value stays null
     * - `billable` — accepted without error but value stays null
     *
     * **Creating a document with a ticket link:**
     * `fromTemplate()` cannot link a ticket.  Use {@see createFromTemplate()} instead —
     * it fetches the full template payload (including tasks) and supports `ticket`,
     * `erpReferenceNumber`, and `billable` via its `$overrides` parameter.
     *
     * @param int   $templateId Docbee template ID (used as `templateId` in the payload).
     * @param array<string, mixed> $data       Additional fields merged into the request body.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function fromTemplate(int $templateId, array $data = []): DocBeeDocumentDTO
    {
        return DocBeeDocumentDTO::fromArray(
            $this->http->post("{$this->endpoint}/fromTemplate", array_merge(['templateId' => $templateId], $data))
        );
    }
    /**
     * Creates a new document from a template, with full support for ticket linkage.
     *
     * This is the recommended alternative to {@see fromTemplate()} when the document
     * must be linked to a ticket, or when `erpReferenceNumber` / `billable` need to be
     * set at creation time — fields that `fromTemplate()` silently ignores.
     *
     * Internally calls `GET /docBeeDocumentTemplate/{id}/createPayloadForDocBeeDocument`
     * to obtain the complete template payload (including embedded task structure), merges
     * `$overrides` on top, then creates the document via `POST /docBeeDocument`.
     *
     * **Supported `$overrides` keys (confirmed by live test):**
     * - `customer` (int) — customer ID
     * - `ticket` (int) — links the document to an existing ticket ✓
     * - `erpReferenceNumber` (string) — ERP reference
     * - `billable` (bool) — whether the document is billable
     *
     * ```php
     * $doc = $client->documents()->createFromTemplate(
     *     templateId: 4109,
     *     overrides:  [
     *         'customer'           => 205023,
     *         'ticket'             => 261861,
     *         'erpReferenceNumber' => 'WO-12345',
     *     ],
     * );
     * ```
     *
     * @param int   $templateId Docbee document template ID.
     * @param array<string, mixed> $overrides  Fields merged into the template payload before creation.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function createFromTemplate(int $templateId, array $overrides = []): DocBeeDocumentDTO
    {
        $payload = $this->http->get("docBeeDocumentTemplate/{$templateId}/createPayloadForDocBeeDocument");

        return DocBeeDocumentDTO::fromArray(
            $this->http->post($this->endpoint, array_merge($payload, $overrides))
        );
    }

    /** @throws \InvalidArgumentException when $number is empty. */
    public function findByNumber(string $number): DocBeeDocumentDTO
    {
        if (trim($number) === '') {
            throw new \InvalidArgumentException('findByNumber(): number must not be empty.');
        }
        return DocBeeDocumentDTO::fromArray($this->http->get("{$this->endpoint}/findByNumber/" . rawurlencode($number)));
    }

    /** @throws \InvalidArgumentException when $externalId is empty. */
    public function findByExternalId(string $externalId): DocBeeDocumentDTO
    {
        if (trim($externalId) === '') {
            throw new \InvalidArgumentException('findByExternalId(): externalId must not be empty.');
        }
        return DocBeeDocumentDTO::fromArray($this->http->get("{$this->endpoint}/findByExternalId/" . rawurlencode($externalId)));
    }
    public function clone(int $id): DocBeeDocumentDTO { return DocBeeDocumentDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/clone", [])); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function approve(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/approve", $data); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function finish(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/finish", $data); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function cancel(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/cancel", $data); }
    public function cancelAndClone(int $id): DocBeeDocumentDTO { return DocBeeDocumentDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/cancelAndClone", [])); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function invoice(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/invoice", $data); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function preFinish(int $id, array $data = []): array { return $this->http->put("{$this->endpoint}/{$id}/preFinish", $data); }
    /** @return array<string, mixed> */
    public function releaseDraft(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/releaseDraft", []); }
    /**
     * Renders a single document (Leistung) as its "Leistungsnachweis" PDF and returns the raw bytes.
     *
     * Maps to `GET /docBeeDocument/{id}/preview` — the same output as "Leistung → Drucken/PDF"
     * in the Docbee UI.  The result is the **single-document detail page only**, WITHOUT the
     * "Leistungsnachweis-Übersicht" cover sheet that `invoices()->exportPdfByIds()` prepends.
     *
     * **No PDF layout parameter:** the endpoint renders with the document's own configured
     * layout; passing a `pdfLayoutId` has no effect (live-verified — identical bytes for every
     * layout ID).  No special document-type layout needs to be set up in Docbee.
     *
     * ```php
     * $pdf = $client->documents()->preview($docId);   // or exportPdfById($docId)
     * file_put_contents('leistungsnachweis.pdf', $pdf);
     * ```
     *
     * For a **combined** report over multiple documents (overview cover + one detail page per
     * Leistung), keep using `invoices()->exportPdfByIds($pdfLayoutId, $invoiceIds)` instead.
     *
     * @return string Raw PDF bytes (response starts with `%PDF`).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function preview(int $id): string { return $this->http->getRaw("{$this->endpoint}/{$id}/preview"); }

    /**
     * Alias of {@see preview()} — renders a single document as its "Leistungsnachweis" PDF.
     *
     * Provided for naming symmetry with the export family.  Returns raw PDF bytes for exactly
     * one document, with no overview cover sheet.  For multi-document combined reports use
     * `invoices()->exportPdfByIds()`.
     *
     * @return string Raw PDF bytes (response starts with `%PDF`).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function exportPdfById(int $docId): string { return $this->preview($docId); }

    /** @return array<string, mixed> */
    public function getMessageData(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/messageData"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function poke(int $id, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/poke", $data); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function reply(int $id, int $messageId, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/reply/{$messageId}", $data); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function forward(int $id, int $messageId, array $data): array { return $this->http->post("{$this->endpoint}/{$id}/forward/{$messageId}", $data); }
    /**
     * Exports all documents matching a given export profile and returns raw file bytes.
     *
     * The export format (PDF, CSV, …) depends on the profile configuration in Docbee.
     * Use {@see exportProfiles()} to list available profiles and their IDs.
     *
     * ```php
     * $pdf = $client->documents()->export($profileId);
     * file_put_contents('leistungen.pdf', $pdf);
     * ```
     *
     * @return string Raw file bytes (typically PDF or CSV).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function export(int $exportProfileId): string
    {
        return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}");
    }

    /**
     * Exports a specific set of documents by ID and returns raw file bytes.
     *
     * Supports single-document and bulk (Sammelreport) exports in one call.
     * The export format depends on the profile configuration in Docbee.
     *
     * ```php
     * // Sammelreport für alle freigegebenen Leistungen eines Vorgangs:
     * $ids = array_map(fn($d) => $d->getId(), $approvedDocs);
     * $pdf = $client->documents()->exportByIds($profileId, $ids);
     * file_put_contents('abrechnung.pdf', $pdf);
     * ```
     *
     * @param  int[]  $ids Document IDs to include in the export.
     * @return string Raw file bytes (typically PDF or CSV).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function exportByIds(int $exportProfileId, array $ids): string
    {
        return $this->postExportByIds("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids);
    }
}
