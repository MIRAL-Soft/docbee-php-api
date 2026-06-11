<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit;

use InvalidArgumentException;
use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\Client\RateLimiter;
use miralsoft\docbee\api\Config\DocbeeConfig;
use miralsoft\docbee\api\DTO\CustomFieldValueDTO;
use miralsoft\docbee\api\DTO\LinkDTO;
use miralsoft\docbee\api\DTO\ProtocolGroupDataDTO;
use miralsoft\docbee\api\DTO\TicketDTO;
use miralsoft\docbee\api\Exception\ServerException;
use miralsoft\docbee\api\Resource\DocumentResource;
use miralsoft\docbee\api\Resource\MapViewFilterResource;
use miralsoft\docbee\api\Resource\MisResource;
use miralsoft\docbee\api\Resource\TicketResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for the fixes from the 2026-06 full security/bug review.
 */
final class SecurityReviewFixesTest extends TestCase
{
    /** @return HttpClientInterface&MockObject */
    private function http(): HttpClientInterface
    {
        return $this->createMock(HttpClientInterface::class);
    }

    // ── Path injection: rawurlencode + empty guard ────────────────────────────

    public function testFindByNumberEncodesPathTraversalCharacters(): void
    {
        $http = $this->http();
        $http->expects($this->once())
             ->method('get')
             ->with('ticket/findByNumber/..%2Fuser%2F1%3Ffields%3D%2A')
             ->willReturn(['id' => 1]);

        (new TicketResource($http))->findByNumber('../user/1?fields=*');
    }

    public function testFindByNumberThrowsOnEmptyString(): void
    {
        $http = $this->http();
        $http->expects($this->never())->method('get');

        $this->expectException(InvalidArgumentException::class);
        (new TicketResource($http))->findByNumber('  ');
    }

    public function testMapViewFilterResourceRejectsUnknownType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new MapViewFilterResource($this->http(), 'evil/../path', 1);
    }

    // ── exportByIds: body shape + empty guard ─────────────────────────────────

    public function testExportByIdsSendsIdsObjectBody(): void
    {
        $http = $this->http();
        // Live-verified: the server requires {"ids":[...]} and rejects raw arrays (400).
        $http->expects($this->once())
             ->method('postRaw')
             ->with('docBeeDocument/exportByIds/1036', ['ids' => [164483, 165591]])
             ->willReturn('%PDF');

        (new DocumentResource($http))->exportByIds(1036, [164483, 165591]);
    }

    public function testExportByIdsThrowsOnEmptyIdList(): void
    {
        $http = $this->http();
        $http->expects($this->never())->method('postRaw');

        $this->expectException(InvalidArgumentException::class);
        (new DocumentResource($http))->exportByIds(1036, []);
    }

    // ── RateLimiter: POST not retried on 5xx ──────────────────────────────────

    public function testServerErrorIsNotRetriedForNonIdempotentRequests(): void
    {
        $calls    = 0;
        $response = new \GuzzleHttp\Psr7\Response(500);

        $limiter = new RateLimiter(maxRetries: 3, baseDelayMs: 1);
        try {
            $limiter->execute(function () use (&$calls, $response) {
                $calls++;
                return $response;
            }, retryServerErrors: false);
            $this->fail('Expected ServerException');
        } catch (ServerException) {
            // exactly ONE attempt — no retry that could duplicate a created record
            $this->assertSame(1, $calls);
        }
    }

    public function testServerErrorIsStillRetriedForIdempotentRequests(): void
    {
        $calls = 0;
        $limiter = new RateLimiter(maxRetries: 2, baseDelayMs: 1);
        try {
            $limiter->execute(function () use (&$calls) {
                $calls++;
                return new \GuzzleHttp\Psr7\Response(500);
            });
            $this->fail('Expected ServerException');
        } catch (ServerException) {
            $this->assertSame(3, $calls); // initial + 2 retries
        }
    }

    public function testRateLimitIsRetriedEvenWithoutServerErrorRetries(): void
    {
        $calls = 0;
        $limiter = new RateLimiter(maxRetries: 2, baseDelayMs: 1);
        $result  = $limiter->execute(function () use (&$calls) {
            $calls++;
            // 429 then success — a rate-limited request was never processed, retry is safe.
            return new \GuzzleHttp\Psr7\Response($calls === 1 ? 429 : 200);
        }, retryServerErrors: false);

        $this->assertSame(200, $result->getStatusCode());
        $this->assertSame(2, $calls);
    }

    // ── DTO serialisation fixes ───────────────────────────────────────────────

    public function testCustomFieldValueToArrayEmitsIdAndValue(): void
    {
        $dto = CustomFieldValueDTO::fromArray(['id' => 104, 'value' => 'WO-1', 'type' => 'TEXT']);

        // Write schema requires {id, value}; `type` is read-only and must not be sent.
        $this->assertSame(['id' => 104, 'value' => 'WO-1'], $dto->toArray());
    }

    public function testCustomFieldValueToArrayKeepsNullValueForClearing(): void
    {
        $dto = CustomFieldValueDTO::fromArray(['id' => 104, 'value' => null]);

        $this->assertSame(['id' => 104, 'value' => null], $dto->toArray());
    }

    public function testProtocolGroupDataToArrayIsNoLongerEmpty(): void
    {
        $dto = ProtocolGroupDataDTO::fromArray(['id' => 7, 'finished' => true, 'templateGroup' => 3]);

        $this->assertSame(['id' => 7, 'finished' => true, 'templateGroup' => 3], $dto->toArray());
    }

    public function testCustomFieldsIdOnlyListDoesNotCrash(): void
    {
        // ?fields=customFields returns plain IDs — previously a TypeError in 8 DTOs.
        $dto = TicketDTO::fromArray(['id' => 1, 'customFields' => [102, 104]]);

        $this->assertSame([], $dto->getCustomFields());
    }

    public function testMissingBoolStaysNullInsteadOfFalse(): void
    {
        // toBool($data[…] ?? null) previously turned a MISSING key into false,
        // which update() would then actively write.
        $dto = LinkDTO::fromArray(['id' => 1]);

        $this->assertNull($dto->getStartTimer());
        $this->assertArrayNotHasKey('startTimer', $dto->toArray());
    }

    public function testNonNumericIdBecomesNullNotZero(): void
    {
        $dto = TicketDTO::fromArray(['id' => 'garbage']);

        $this->assertNull($dto->getId());
    }

    // ── cursor(): missing totalCount must not stop after page 1 ───────────────

    public function testCursorContinuesWhenTotalCountMissingButPageIsFull(): void
    {
        $http  = $this->http();
        $calls = 0;
        $http->method('get')->willReturnCallback(function () use (&$calls) {
            $calls++;
            if ($calls === 1) {
                // FULL page (500 = DocumentResource default) but NO totalCount.
                return ['docBeeDocument' => array_map(fn($i) => ['id' => $i], range(1, 500))];
            }
            return ['docBeeDocument' => [['id' => 501]]]; // short page → ends pagination
        });

        $items = iterator_to_array((new DocumentResource($http))->cursor());

        $this->assertCount(501, $items);
        $this->assertSame(2, $calls);
    }

    // ── Config validation ─────────────────────────────────────────────────────

    public function testNegativeMaxRetriesRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DocbeeConfig(tenant: 't', token: 'x', maxRetries: -1);
    }

    // ── MisResource: params reach the wire, CSV is raw ────────────────────────

    public function testMisParamsAreAppendedToQueryString(): void
    {
        $http = $this->http();
        $http->expects($this->once())
             ->method('get')
             ->with('mis/customerChart?startDate=2026-01-01&endDate=2026-02-01')
             ->willReturn([]);

        (new MisResource($http))->getCustomerChart(['startDate' => '2026-01-01', 'endDate' => '2026-02-01']);
    }

    public function testMisCsvUsesRawTransport(): void
    {
        $http = $this->http();
        $http->expects($this->once())
             ->method('getRaw')
             ->with('mis/customerChartCsv')
             ->willReturn("a;b\n1;2");
        $http->expects($this->never())->method('get');

        $csv = (new MisResource($http))->getCustomerChartCsv();

        $this->assertSame("a;b\n1;2", $csv);
    }
}
