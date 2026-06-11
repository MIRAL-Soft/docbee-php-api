<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Query;

use DateTimeImmutable;
use DateTimeZone;
use miralsoft\docbee\api\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the changedSince/createdSince date handling.
 *
 * Two requirements:
 *  - Format: the Docbee API requires `YYYY-MM-DDTHH:mm:ss.mmmZ` exactly; any other
 *    format yields HTTP 400 "changedSince has invalid date format".
 *  - Timezone: the value is declared as UTC (`…Z`), so the DateTime must be
 *    CONVERTED to UTC first.  The previous implementation formatted the clock time
 *    of the DateTime's own timezone and declared it as UTC, shifting delta-sync
 *    windows by 1–2 h on non-UTC servers (missed records).
 */
final class QueryBuilderChangedSinceTest extends TestCase
{
    public function testModifiedSinceUtcInputIsUnchanged(): void
    {
        $dt = new DateTimeImmutable('2026-05-18 10:30:00', new DateTimeZone('UTC'));
        $qs = QueryBuilder::new()->modifiedSince($dt)->build();

        // Must contain .000Z suffix — plain ISO without milliseconds/Z yields HTTP 400
        $this->assertStringContainsString('changedSince=2026-05-18T10%3A30%3A00.000Z', $qs);
        $this->assertStringNotContainsString('changedSince=2026-05-18T10%3A30%3A00&', $qs);
    }

    public function testModifiedSinceConvertsLocalTimezoneToUtc(): void
    {
        // 15:45 Berlin summer time (UTC+2) == 13:45 UTC.
        // The OLD behaviour emitted 15:45:30.000Z (local clock declared as UTC) —
        // shifting the delta window 2 h into the future and missing recent changes.
        $dt = new DateTimeImmutable('2026-05-18 15:45:30', new DateTimeZone('Europe/Berlin'));
        $qs = urldecode(QueryBuilder::new()->modifiedSince($dt)->build());

        $this->assertStringContainsString('changedSince=2026-05-18T13:45:30.000Z', $qs);
    }

    public function testCreatedSinceConvertsToUtcAndFormats(): void
    {
        // 00:00 Berlin winter time (UTC+1) == 23:00 UTC the previous day.
        $dt = new DateTimeImmutable('2026-01-01 00:00:00', new DateTimeZone('Europe/Berlin'));
        $qs = urldecode(QueryBuilder::new()->createdSince($dt)->build());

        $this->assertStringContainsString('createdSince=2025-12-31T23:00:00.000Z', $qs);
    }

    public function testModifiedSinceDoesNotMutateCallerDateTime(): void
    {
        $dt = new DateTimeImmutable('2026-05-18 15:45:30', new DateTimeZone('Europe/Berlin'));
        QueryBuilder::new()->modifiedSince($dt);

        $this->assertSame('Europe/Berlin', $dt->getTimezone()->getName());
    }
}
