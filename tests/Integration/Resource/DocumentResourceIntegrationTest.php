<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\DocBeeDocumentDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for DocumentResource (DocBeeDocument) — read-only.
 *
 * Optional env var:
 *   DOCBEE_TEST_DOCUMENT_ID  — when set, the find($id) test is executed.
 */
final class DocumentResourceIntegrationTest extends IntegrationTestCase
{
    /**
     * list() should return an array (may be empty on a fresh tenant).
     */
    public function testListReturnsArray(): void
    {
        $result = $this->client->documents()->list();

        $this->assertIsArray($result);
    }

    /**
     * Every item returned by list() must be a DocBeeDocumentDTO instance.
     */
    public function testListItemsAreDocBeeDocumentDTOs(): void
    {
        $result = $this->client->documents()->list();

        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentDTO::class, $item);
        }
    }

    /**
     * Each DocBeeDocumentDTO from list() must have a positive integer ID.
     */
    public function testListItemsHavePositiveId(): void
    {
        $result = $this->client->documents()->list();

        foreach ($result as $item) {
            $this->assertInstanceOf(DocBeeDocumentDTO::class, $item);
            $this->assertIsInt($item->getId());
            $this->assertGreaterThan(0, $item->getId());
        }
    }

    /**
     * find($id) must return a DocBeeDocumentDTO with the requested ID.
     * Skipped when DOCBEE_TEST_DOCUMENT_ID is not configured.
     */
    public function testFindByIdReturnsCorrectDTO(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_DOCUMENT_ID');

        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOCUMENT_ID in tests/.env.test to enable this test.');
        }

        $document = $this->client->documents()->find($id);

        $this->assertInstanceOf(DocBeeDocumentDTO::class, $document);
        $this->assertSame($id, $document->getId());
    }
}
