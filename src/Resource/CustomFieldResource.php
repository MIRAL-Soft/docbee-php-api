<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomFieldDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomField records.
 *
 * Includes typed constants for parent types and field types to avoid magic
 * strings in application code, plus idempotent helper methods for provisioning
 * custom fields and assigning them to entity types.
 *
 * ```php
 * $cf = $client->customFields()->ensureDefinition(
 *     name:       'weclappOrderItemId',
 *     parentType: CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT,
 *     type:       CustomFieldResource::TYPE_SINGLELINE_TEXT,
 * );
 *
 * // Assign the field to Leistungen (idempotent)
 * $client->customFields()->ensureAssignedToDocument($cf->getId());
 *
 * // Set a value on a specific document
 * $client->documents()->setCustomFieldValue($docId, $cf->getId(), 'WO-12345');
 * ```
 *
 * @extends AbstractResource<CustomFieldDTO>
 */
final class CustomFieldResource extends AbstractResource
{
    protected string $endpoint = 'customField';
    protected string $dtoClass = CustomFieldDTO::class;
    protected string $listKey  = 'customField';

    // ── Parent-type constants ─────────────────────────────────────────────────

    /** Custom field belongs to a Leistung (service document / DocBeeDocument). */
    public const string PARENT_TYPE_DOCBEE_DOCUMENT   = 'DOCBEE_DOCUMENT';

    /** Custom field belongs to a Vorgang (ticket / support process). */
    public const string PARENT_TYPE_TICKET            = 'TICKET';

    /** Custom field belongs to a customer record. */
    public const string PARENT_TYPE_CUSTOMER          = 'CUSTOMER';

    /** Custom field belongs to a customer contact. */
    public const string PARENT_TYPE_CUSTOMER_CONTACT  = 'CUSTOMER_CONTACT';

    /** Custom field belongs to a customer location. */
    public const string PARENT_TYPE_CUSTOMER_LOCATION = 'CUSTOMER_LOCATION';

    /** Custom field belongs to a material item. */
    public const string PARENT_TYPE_MATERIAL_ITEM     = 'MATERIAL_ITEM';

    // ── Field-type constants ──────────────────────────────────────────────────

    /** Single-line plain-text field. */
    public const string TYPE_SINGLELINE_TEXT = 'SINGLELINE_TEXT';

    /** Multi-line plain-text field. */
    public const string TYPE_MULTILINE_TEXT  = 'MULTILINE_TEXT';

    /** Integer number field. */
    public const string TYPE_NUMBER_LONG     = 'NUMBER_LONG';

    /** Decimal / floating-point number field. */
    public const string TYPE_NUMBER_DECIMAL  = 'NUMBER_DECIMAL';

    /** Boolean (yes / no) field. */
    public const string TYPE_BOOLEAN         = 'BOOLEAN';

    /** Date field. */
    public const string TYPE_DATE            = 'DATE';

    /** Single-value selection from a predefined list. */
    public const string TYPE_SELECTION       = 'SELECTION';

    // ── Internal helpers ──────────────────────────────────────────────────────

    /**
     * Fields that must be requested explicitly — the default list response only
     * returns `id`, `name`, and `link`.
     */
    private const array DEFINITION_FIELDS = ['id', 'name', 'parentType', 'type'];

    // ── Lookup ────────────────────────────────────────────────────────────────

    /**
     * Returns all custom-field definitions for a given parent type.
     *
     * Uses the plain `parentType=<value>` parameter — the standard
     * `parentType-eq=<value>` operator form is silently ignored by the Docbee API
     * and returns all fields regardless of type.
     *
     * ```php
     * $fields = $client->customFields()->findByParentType(
     *     CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT
     * );
     * ```
     *
     * @return list<CustomFieldDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByParentType(string $parentType): array
    {
        return $this->listAll(
            QueryBuilder::new()
                ->param('parentType', $parentType)
                ->fields(self::DEFINITION_FIELDS)
        );
    }

    /**
     * Finds the first custom-field definition matching the given name and parent type.
     *
     * Returns null when no match is found.
     *
     * The Docbee API only returns `id`, `name`, and `link` in list responses by
     * default; `parentType` and `type` must be requested explicitly via `?fields=`.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name, string $parentType): ?CustomFieldDTO
    {
        foreach ($this->findByParentType($parentType) as $field) {
            if ($field->getName() === $name) {
                return $field;
            }
        }

        return null;
    }

    // ── Provisioning ──────────────────────────────────────────────────────────

    /**
     * Ensures a custom-field definition exists with the given name and parent type.
     *
     * If a field with the same name and `parentType` already exists it is returned
     * as-is (idempotent).  Otherwise a new field is created and returned.
     *
     * @param string $name              Human-readable field label shown in the Docbee UI.
     * @param string $parentType        One of the {@see PARENT_TYPE_*} constants.
     * @param string $type              One of the {@see TYPE_*} constants.
     * @param bool   $searchable        Whether the field is indexed for search (default: true).
     * @param bool   $visibleForCustomer Whether the field is visible to customer contacts (default: false).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function ensureDefinition(
        string $name,
        string $parentType,
        string $type,
        bool $searchable = true,
        bool $visibleForCustomer = false,
    ): CustomFieldDTO {
        $existing = $this->findByName($name, $parentType);
        if ($existing !== null) {
            return $existing;
        }

        return CustomFieldDTO::fromArray($this->http->post($this->endpoint, [
            'name'               => $name,
            'parentType'         => $parentType,
            'type'               => $type,
            'searchable'         => $searchable,
            'visibleForCustomer' => $visibleForCustomer,
        ]));
    }

    /**
     * Ensures the given custom field is assigned to Leistungen (docBeeDocument).
     *
     * Reads the current global assignment list, merges in the field ID if absent,
     * and writes the merged list back.  Safe to call repeatedly (idempotent).
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function ensureAssignedToDocument(int $fieldId): void
    {
        $this->ensureAssignedToEndpoint('docBeeDocument', $fieldId);
    }

    /**
     * Ensures the given custom field is assigned to Leistungsvorlagen (docBeeDocumentTemplate).
     *
     * Document templates share custom-field definitions with regular documents
     * (`parentType = DOCBEE_DOCUMENT`), but each entity type has its own
     * independent assignment list that controls which fields appear in the UI.
     * Call this in addition to {@see ensureAssignedToDocument()} when a field
     * should be visible on both Leistungen and Leistungsvorlagen.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function ensureAssignedToDocumentTemplate(int $fieldId): void
    {
        $this->ensureAssignedToEndpoint('docBeeDocumentTemplate', $fieldId);
    }

    /**
     * Ensures the given custom field is assigned to Vorgänge (ticket).
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function ensureAssignedToTicket(int $fieldId): void
    {
        $this->ensureAssignedToEndpoint('ticket', $fieldId);
    }

    // ── Internal ──────────────────────────────────────────────────────────────

    /**
     * Generic merge-and-write for any entity that exposes a `/customFields` sub-endpoint.
     *
     * Pattern: GET current IDs → merge → PUT merged list (skipped when already present).
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    private function ensureAssignedToEndpoint(string $entityEndpoint, int $fieldId): void
    {
        $current = $this->http->get("{$entityEndpoint}/customFields");
        $ids     = array_map('intval', (array) ($current['customFields'] ?? []));

        if (in_array($fieldId, $ids, true)) {
            return; // already assigned — nothing to write
        }

        $ids[] = $fieldId;
        $this->http->put("{$entityEndpoint}/customFields", ['customFields' => array_values($ids)]);
    }
}
