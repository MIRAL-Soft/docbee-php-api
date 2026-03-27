<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Query;

use DateTimeImmutable;
use InvalidArgumentException;
use miralsoft\docbee\api\Query\FilterOperator;
use miralsoft\docbee\api\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see QueryBuilder}.
 */
final class QueryBuilderTest extends TestCase
{
    public function testBuildIncludesDefaultPagination(): void
    {
        $qs = QueryBuilder::new()->build();
        $this->assertStringContainsString('limit=50', $qs);
        $this->assertStringContainsString('offset=0', $qs);
    }

    public function testFilterEqAppendsCorrectParam(): void
    {
        $qs = QueryBuilder::new()->filterEq('status', 'open')->build();
        $this->assertStringContainsString('status-eq=open', $qs);
    }

    public function testFilterIlikeAppendsCorrectParam(): void
    {
        $qs = QueryBuilder::new()->filterIlike('name', '%acme%')->build();
        $this->assertStringContainsString('name-ilike', $qs);
    }

    public function testFilterInJoinsValues(): void
    {
        $qs = QueryBuilder::new()->filterIn('id', [1, 2, 3])->build();
        $this->assertStringContainsString('id-in', $qs);
        $this->assertStringContainsString('1%2C2%2C3', $qs); // URL-encoded commas
    }

    public function testModifiedSinceAddsChangedSince(): void
    {
        $since = new DateTimeImmutable('2024-01-15 10:00:00');
        $qs    = QueryBuilder::new()->modifiedSince($since)->build();
        $this->assertStringContainsString('changedSince', $qs);
        $this->assertStringContainsString('2024-01-15', $qs);
    }

    public function testSortAppendsCorrectParam(): void
    {
        $qs = QueryBuilder::new()->sort('createdAt', 'desc')->build();
        $this->assertStringContainsString('sort=createdAt-desc', $qs);
    }

    public function testSortThrowsOnInvalidDirection(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->sort('createdAt', 'sideways');
    }

    public function testSortByModifiedDefaultsToDesc(): void
    {
        $qs = QueryBuilder::new()->sortByModified()->build();
        $this->assertStringContainsString('changedAt-desc', $qs);
    }

    public function testLimitCappedAtHundred(): void
    {
        $qs = QueryBuilder::new()->limit(9999)->build();
        $this->assertStringContainsString('limit=100', $qs);
    }

    public function testOffsetIsIncluded(): void
    {
        $qs = QueryBuilder::new()->offset(25)->build();
        $this->assertStringContainsString('offset=25', $qs);
    }

    public function testFieldsAreCommaSeparated(): void
    {
        $qs = QueryBuilder::new()->fields(['id', 'name', 'email'])->build();
        $this->assertStringContainsString('fields=', $qs);
    }

    public function testBuildForCountSetsLimitZero(): void
    {
        $qs = QueryBuilder::new()->buildForCount();
        $this->assertStringContainsString('limit=0', $qs);
    }

    public function testMultipleFiltersAreCombined(): void
    {
        $qs = QueryBuilder::new()
            ->filterEq('status', 'open')
            ->filterGt('priority', 2)
            ->build();

        $this->assertStringContainsString('status-eq=open', $qs);
        $this->assertStringContainsString('priority-gt=2', $qs);
    }

    public function testBoolValueNormalisedToOne(): void
    {
        $qs = QueryBuilder::new()->filterEq('active', true)->build();
        $this->assertStringContainsString('active-eq=1', $qs);
    }

    public function testBoolFalseNormalisedToZero(): void
    {
        $qs = QueryBuilder::new()->filterEq('active', false)->build();
        $this->assertStringContainsString('active-eq=0', $qs);
    }

    public function testFilterWithEnumOperator(): void
    {
        $qs = QueryBuilder::new()
            ->filter('priority', FilterOperator::GT, 3)
            ->build();
        $this->assertStringContainsString('priority-gt=3', $qs);
    }

    public function testFilterWithRawStringOperatorStillWorks(): void
    {
        // Ensures backwards compatibility: raw strings are still accepted.
        $qs = QueryBuilder::new()
            ->filter('name', 'eq', 'Acme')
            ->build();
        $this->assertStringContainsString('name-eq=Acme', $qs);
    }

    public function testNegativeOffsetClampedToZero(): void
    {
        $qs = QueryBuilder::new()->offset(-10)->build();
        $this->assertStringContainsString('offset=0', $qs);
    }
}
