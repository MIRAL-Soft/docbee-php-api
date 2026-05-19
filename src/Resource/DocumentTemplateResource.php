<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomFieldValueDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee document templates.
 *
 * @extends AbstractResource<DocBeeDocumentTemplateDTO>
 *
 * **Custom fields on document templates:**
 *
 * Document templates share custom-field definitions with regular documents:
 * both use `parentType = DOCBEE_DOCUMENT`.  A single field definition can
 * therefore appear on Leistungen *and* Leistungsvorlagen simultaneously.
 *
 * Typical setup workflow:
 * ```php
 * $cf = $client->customFields()->ensureDefinition(
 *     name:       'weclappOrderItemId',
 *     parentType: CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT,
 *     type:       CustomFieldResource::TYPE_SINGLELINE_TEXT,
 * );
 * // Assign to both entity types (idempotent)
 * $client->customFields()->ensureAssignedToDocument($cf->getId());
 * $client->customFields()->ensureAssignedToDocumentTemplate($cf->getId());
 *
 * // Set a value on a specific template
 * $client->documentTemplates()->setCustomFieldValue($templateId, $cf->getId(), 'WO-12345');
 * ```
 */
final class DocumentTemplateResource extends AbstractResource
{
    protected string $endpoint = 'docBeeDocumentTemplate';
    protected string $dtoClass = DocBeeDocumentTemplateDTO::class;
    protected string $listKey  = 'docBeeDocumentTemplate';

    /**
     * Finds a template by its exact name.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): DocBeeDocumentTemplateDTO
    {
        $results = $this->list(QueryBuilder::new()->filterEq('name', $name)->limit(1));
        if (empty($results)) {
            throw new NotFoundException(
                message:    "DocumentTemplate with name '{$name}' not found.",
                statusCode: 404,
                requestUrl: $this->endpoint,
            );
        }
        return $results[0];
    }

    public function clone(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/clone", []); }
    public function createPayloadForDocBeeDocument(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/createPayloadForDocBeeDocument"); }

    // ── Custom field assignment ───────────────────────────────────────────────

    /** Returns the global list of custom-field IDs assigned to document templates. */
    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }

    /** Updates the global list of custom-field IDs assigned to document templates. */
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }

    // ── Custom field value helpers ────────────────────────────────────────────

    /**
     * Returns all custom field values for a document template as a `fieldId => value` map.
     *
     * Uses `?fields=customFields.id,customFields.value` dot-notation — required for
     * the Docbee API to return stored values rather than only field IDs.
     *
     * Returns an empty array when no custom fields have been set on the template.
     *
     * ```php
     * $values = $client->documentTemplates()->getCustomFieldValues($templateId);
     * // e.g. [102 => 123, 104 => 'WO-12345']
     * ```
     *
     * @return array<int, mixed> Map of fieldId => value.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function getCustomFieldValues(int $templateId): array
    {
        $response = $this->http->get(
            "{$this->endpoint}/{$templateId}?fields=customFields.id,customFields.value"
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
     * Returns the stored value of a single custom field on a document template, or null when unset.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function getCustomFieldValue(int $templateId, int $fieldId): mixed
    {
        return $this->getCustomFieldValues($templateId)[$fieldId] ?? null;
    }

    /**
     * Returns the IDs of custom fields that have a non-null value on this template.
     *
     * Useful for a lightweight presence check without fetching actual values.
     * For reading the values themselves use {@see getCustomFieldValues()}.
     *
     * @return list<int>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function getCustomFieldIds(int $templateId): array
    {
        $response = $this->http->get("{$this->endpoint}/{$templateId}?fields=customFields");
        return array_map('intval', (array) ($response['customFields'] ?? []));
    }

    /**
     * Returns true when the given custom field has a non-null value on the template.
     *
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function hasCustomFieldValue(int $templateId, int $fieldId): bool
    {
        return in_array($fieldId, $this->getCustomFieldIds($templateId), true);
    }

    /**
     * Sets a single custom field value on a document template.
     *
     * @param mixed $value Field value; type must match the field's configured type.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function setCustomFieldValue(int $templateId, int $fieldId, mixed $value): void
    {
        $this->http->put("{$this->endpoint}/{$templateId}", [
            'customFields' => [['id' => $fieldId, 'value' => $value]],
        ]);
    }

    /**
     * Sets multiple custom field values on a document template in a single request.
     *
     * @param array<int, mixed> $valuesByFieldId Map of fieldId => value.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function setCustomFieldValues(int $templateId, array $valuesByFieldId): void
    {
        if (empty($valuesByFieldId)) {
            return;
        }

        $customFields = [];
        foreach ($valuesByFieldId as $fieldId => $value) {
            $customFields[] = ['id' => (int) $fieldId, 'value' => $value];
        }

        $this->http->put("{$this->endpoint}/{$templateId}", ['customFields' => $customFields]);
    }

    /**
     * Returns all document templates for a customer with their custom field values pre-loaded.
     *
     * Unlike a plain list, this requests `fields=id,customFields.id,customFields.value`
     * so that each template in the paginated response already carries its custom field
     * data — avoiding N+1 individual fetches.
     *
     * ```php
     * foreach ($client->documentTemplates()->cursorWithCustomFields(205023) as $tmpl) {
     *     $fields = $tmpl->getCustomFields() ?? [];
     *     // Each element is a CustomFieldValueDTO with getId() and getValue()
     * }
     * ```
     *
     * @return \Generator<int, DocBeeDocumentTemplateDTO, void, void>
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
     * Finds document templates where the given custom field has the specified value.
     *
     * The Docbee API does not support server-side custom field filters — this method
     * performs a paginated cursor scan scoped to the customer, fetching custom field
     * values inline per page (no N+1 overhead).
     *
     * ```php
     * $templates = $client->documentTemplates()->findByCustomFieldValue(
     *     customerId: 205023,
     *     fieldId:    104,         // e.g. weclappOrderItemId custom field
     *     value:      'WO-12345',
     * );
     * ```
     *
     * @param int   $customerId Docbee customer ID to scope the search.
     * @param int   $fieldId    Custom field definition ID.
     * @param mixed $value      Value to match (strict equality).
     * @return list<DocBeeDocumentTemplateDTO>
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByCustomFieldValue(int $customerId, int $fieldId, mixed $value): array
    {
        $results = [];

        foreach ($this->cursorWithCustomFields($customerId) as $template) {
            foreach ((array) ($template->getCustomFields() ?? []) as $cf) {
                if ($cf instanceof CustomFieldValueDTO
                    && $cf->getId() === $fieldId
                    // phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators
                    && $cf->getValue() === $value
                ) {
                    $results[] = $template;
                    break;
                }
            }
        }

        return $results;
    }
}
