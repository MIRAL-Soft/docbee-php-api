<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee documents (protocols / reports).
 *
 * @extends AbstractResource<DocBeeDocumentDTO>
 */
final class DocumentResource extends AbstractResource
{
    protected string $endpoint = 'docbeedocument';
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
