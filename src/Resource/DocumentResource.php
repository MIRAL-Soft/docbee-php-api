<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee documents (protocols / reports).
 *
 * @extends AbstractResource<DocBeeDocumentDTO>
 *
 * @note Filtering documents by ticket ID via filterEq('ticket', $ticketId) is not
 *       supported by the Docbee API — the filter is silently ignored and returns
 *       an empty result. As a workaround, use findByCustomer($customerId) and
 *       scan the result client-side by matching getTicket() === $ticketId.
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

    public function getCustomFields(): array { return $this->http->get("{$this->endpoint}/customFields"); }
    public function updateCustomFields(array $data): array { return $this->http->put("{$this->endpoint}/customFields", $data); }

    /**
     * Returns the IDs of custom fields that have a non-null value on this document.
     *
     * The Docbee API does not expose the actual field values — only the IDs of
     * fields whose value has been set.  Use {@see hasCustomFieldValue()} to check
     * whether a specific field is populated.
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
