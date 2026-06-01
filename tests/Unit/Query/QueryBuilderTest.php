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
        // Default limit raised from 50 → 100 (2026-05-23 performance improvement)
        $this->assertStringContainsString('limit=100', $qs);
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

    public function testFilterThrowsOnEmptyFieldName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('', FilterOperator::EQ, 'value');
    }

    public function testFilterThrowsOnWhitespaceOnlyFieldName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('   ', FilterOperator::EQ, 'value');
    }

    public function testNegativeLimitClampedToOne(): void
    {
        $qs = QueryBuilder::new()->limit(-5)->build();
        $this->assertStringContainsString('limit=1', $qs);
    }

    public function testZeroLimitClampedToOne(): void
    {
        $qs = QueryBuilder::new()->limit(0)->build();
        $this->assertStringContainsString('limit=1', $qs);
    }

    public function testSortThrowsOnEmptyFieldName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->sort('');
    }

    public function testSortThrowsOnWhitespaceOnlyFieldName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->sort('   ');
    }

    public function testFilterThrowsOnFieldNameWithDot(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('field.name', FilterOperator::EQ, 'value');
    }

    public function testFilterThrowsOnFieldNameWithDash(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('field-name', FilterOperator::EQ, 'value');
    }

    public function testFilterThrowsOnFieldNameStartingWithDigit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->filter('1field', FilterOperator::EQ, 'value');
    }

    public function testSortThrowsOnFieldNameWithDot(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->sort('field.name');
    }

    public function testSortThrowsOnFieldNameStartingWithDigit(): void
    {
        $this->expectException(InvalidArgumentException::class);
        QueryBuilder::new()->sort('1field');
    }

    public function testFilterAcceptsUnderscoreInFieldName(): void
    {
        $qs = QueryBuilder::new()->filter('field_name', FilterOperator::EQ, 'value')->build();
        $this->assertStringContainsString('field_name-eq=value', $qs);
    }

    // ── getFields() ──────────────────────────────────────────────────────────

    public function testGetFieldsReturnsEmptyArrayByDefault(): void
    {
        $this->assertSame([], QueryBuilder::new()->getFields());
    }

    public function testGetFieldsReturnsConfiguredFields(): void
    {
        $fields = ['id', 'name', 'modified'];
        $qb     = QueryBuilder::new()->fields($fields);

        $this->assertSame($fields, $qb->getFields());
    }

    public function testGetFieldsReturnsLastSetAfterMultipleCalls(): void
    {
        $qb = QueryBuilder::new()->fields(['id', 'name'])->fields(['id', 'status']);

        $this->assertSame(['id', 'status'], $qb->getFields());
    }
}
