<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Query;

use DateTimeImmutable;
use miralsoft\docbee\api\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the changedSince/createdSince date-format fix.
 *
 * The Docbee API requires `YYYY-MM-DDTHH:mm:ss.mmmZ` format exactly.
 * Any other format yields HTTP 400 "changedSince has invalid date format".
 */
final class QueryBuilderChangedSinceTest extends TestCase
{
    public function testModifiedSinceProducesMillisecondUtcFormat(): void
    {
        $dt  = new DateTimeImmutable('2026-05-18 10:30:00');
        $qs  = QueryBuilder::new()->modifiedSince($dt)->build();

        // Must contain .000Z suffix — plain ISO without milliseconds/Z yields HTTP 400
        $this->assertStringContainsString('changedSince=2026-05-18T10%3A30%3A00.000Z', $qs);
        $this->assertStringNotContainsString('changedSince=2026-05-18T10%3A30%3A00&', $qs);
    }

    public function testCreatedSinceProducesMillisecondUtcFormat(): void
    {
        $dt  = new DateTimeImmutable('2026-01-01 00:00:00');
        $qs  = QueryBuilder::new()->createdSince($dt)->build();

        $this->assertStringContainsString('createdSince=2026-01-01T00%3A00%3A00.000Z', $qs);
    }

    public function testModifiedSinceFormatDoesNotContainRawColon(): void
    {
        // http_build_query percent-encodes the colons — verify the raw value format
        $dt  = new DateTimeImmutable('2026-05-18 15:45:30');
        $qs  = QueryBuilder::new()->modifiedSince($dt)->build();

        // Decoded it should look like: changedSince=2026-05-18T15:45:30.000Z
        $decoded = urldecode($qs);
        $this->assertStringContainsString('changedSince=2026-05-18T15:45:30.000Z', $decoded);
    }
}
