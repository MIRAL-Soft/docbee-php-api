<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\ProtocolDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for ProtocolResource — read-only.
 *
 * Optional env var:
 *   DOCBEE_TEST_PROTOCOL_ID  — when set, the find($id) test is executed.
 */
final class ProtocolResourceIntegrationTest extends IntegrationTestCase
{
    /**
     * list() should return an array (may be empty on a fresh tenant).
     */
    public function testListReturnsArray(): void
    {
        $result = $this->client->protocols()->list();

        $this->assertIsArray($result);
    }

    /**
     * Every item returned by list() must be a ProtocolDTO instance.
     */
    public function testListItemsAreProtocolDTOs(): void
    {
        $result = $this->client->protocols()->list();

        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolDTO::class, $item);
        }
    }

    /**
     * Each ProtocolDTO from list() must have a positive integer ID.
     */
    public function testListItemsHavePositiveId(): void
    {
        $result = $this->client->protocols()->list();

        foreach ($result as $item) {
            $this->assertInstanceOf(ProtocolDTO::class, $item);
            $this->assertIsInt($item->getId());
            $this->assertGreaterThan(0, $item->getId());
        }
    }

    /**
     * find($id) must return a ProtocolDTO with the requested ID.
     * Skipped when DOCBEE_TEST_PROTOCOL_ID is not configured.
     */
    public function testFindByIdReturnsCorrectDTO(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PROTOCOL_ID');

        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PROTOCOL_ID in tests/.env.test to enable this test.');
        }

        $protocol = $this->client->protocols()->find($id);

        $this->assertInstanceOf(ProtocolDTO::class, $protocol);
        $this->assertSame($id, $protocol->getId());
    }
}
