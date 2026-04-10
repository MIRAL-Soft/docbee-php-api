<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Security;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use miralsoft\docbee\api\Client\HttpClient;
use miralsoft\docbee\api\Config\DocbeeConfig;
use miralsoft\docbee\api\Exception\AuthenticationException;
use miralsoft\docbee\api\Exception\DocbeeApiException;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Exception\RateLimitException;
use miralsoft\docbee\api\Exception\ServerException;
use miralsoft\docbee\api\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Security tests for {@see HttpClient}.
 *
 * Uses Guzzle's history middleware to capture outgoing requests and verify
 * authentication headers, content negotiation, and exception sanitization.
 */
final class HttpClientSecurityTest extends TestCase
{
    private DocbeeConfig $config;

    protected function setUp(): void
    {
        $this->config = new DocbeeConfig(tenant: 'testco', token: 'super-secret-token');
    }

    // -------------------------------------------------------------------------
    // Helper: build a Guzzle client with a mock handler + history capture
    // -------------------------------------------------------------------------

    /**
     * @param  list<\GuzzleHttp\Exception\GuzzleException|Response> $responses
     * @param  list<array{request: RequestInterface}>                $history
     */
    private function buildMockClient(array $responses, array &$history = []): GuzzleClient
    {
        $mock  = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        return new GuzzleClient([
            'handler'     => $stack,
            // Mirror HttpClient's own Guzzle config so errors flow through our
            // throwIfError() path rather than Guzzle's middleware throwing first.
            'http_errors' => false,
        ]);
    }

    // -------------------------------------------------------------------------
    // Authorization header
    // -------------------------------------------------------------------------

    public function testGetRequestIncludesBearerAuthorizationHeader(): void
    {
        $history  = [];
        $guzzle   = $this->buildMockClient([new Response(200, [], '{}')], $history);
        $client   = new HttpClient($this->config, $guzzle);

        $client->get('ticket');

        $this->assertCount(1, $history);
        $authHeader = $history[0]['request']->getHeaderLine('Authorization');
        $this->assertStringStartsWith('Bearer ', $authHeader);
        $this->assertStringContainsString('super-secret-token', $authHeader);
    }

    public function testPostRequestIncludesBearerAuthorizationHeader(): void
    {
        $history = [];
        $guzzle  = $this->buildMockClient([new Response(200, [], '{"id":1}')], $history);
        $client  = new HttpClient($this->config, $guzzle);

        $client->post('ticket', ['title' => 'Test']);

        $authHeader = $history[0]['request']->getHeaderLine('Authorization');
        $this->assertStringStartsWith('Bearer ', $authHeader);
    }

    public function testPutRequestIncludesBearerAuthorizationHeader(): void
    {
        $history = [];
        $guzzle  = $this->buildMockClient([new Response(200, [], '{"id":1}')], $history);
        $client  = new HttpClient($this->config, $guzzle);

        $client->put('ticket/1', ['title' => 'Updated']);

        $authHeader = $history[0]['request']->getHeaderLine('Authorization');
        $this->assertStringStartsWith('Bearer ', $authHeader);
    }

    public function testDeleteRequestIncludesBearerAuthorizationHeader(): void
    {
        $history = [];
        $guzzle  = $this->buildMockClient([new Response(204)], $history);
        $client  = new HttpClient($this->config, $guzzle);

        $client->delete('ticket/1');

        $authHeader = $history[0]['request']->getHeaderLine('Authorization');
        $this->assertStringStartsWith('Bearer ', $authHeader);
    }

    // -------------------------------------------------------------------------
    // Content negotiation headers
    // -------------------------------------------------------------------------

    public function testGetRequestIncludesAcceptJsonHeader(): void
    {
        $history = [];
        $guzzle  = $this->buildMockClient([new Response(200, [], '{}')], $history);
        $client  = new HttpClient($this->config, $guzzle);

        $client->get('ticket');

        $this->assertSame('application/json', $history[0]['request']->getHeaderLine('Accept'));
    }

    public function testPostRequestIncludesContentTypeJsonHeader(): void
    {
        $history = [];
        $guzzle  = $this->buildMockClient([new Response(200, [], '{"id":1}')], $history);
        $client  = new HttpClient($this->config, $guzzle);

        $client->post('ticket', []);

        $this->assertStringContainsString(
            'application/json',
            $history[0]['request']->getHeaderLine('Content-Type')
        );
    }

    // -------------------------------------------------------------------------
    // Token must not appear in exception messages
    // -------------------------------------------------------------------------

    public function testConnectExceptionDoesNotLeakTokenInMessage(): void
    {
        $connectException = new ConnectException(
            'cURL error 6: Could not resolve host: testco.docbee.com (token=super-secret-token)',
            new Request('GET', 'https://testco.docbee.com/restApi/v1/ticket'),
        );

        $guzzle = $this->buildMockClient([$connectException]);
        $client = new HttpClient($this->config, $guzzle);

        try {
            $client->get('ticket');
            $this->fail('Expected DocbeeApiException.');
        } catch (DocbeeApiException $e) {
            $this->assertStringNotContainsString(
                'super-secret-token',
                $e->getMessage(),
                'The exception message must not expose the API token.'
            );
        }
    }

    public function testConnectExceptionDoesNotLeakTenantUrlInMessage(): void
    {
        $connectException = new ConnectException(
            'cURL error 6: Could not resolve host: testco.docbee.com',
            new Request('GET', 'https://testco.docbee.com/restApi/v1/ticket'),
        );

        $guzzle = $this->buildMockClient([$connectException]);
        $client = new HttpClient($this->config, $guzzle);

        try {
            $client->get('ticket');
            $this->fail('Expected DocbeeApiException.');
        } catch (DocbeeApiException $e) {
            // The sanitized message must not include the full URL
            // (it could reveal internal tenant topology or path structure).
            $this->assertStringNotContainsString(
                'testco.docbee.com',
                $e->getMessage(),
                'The exception message must not expose the internal API URL.'
            );
        }
    }

    // -------------------------------------------------------------------------
    // HTTP status code → exception type
    // -------------------------------------------------------------------------

    public function test401ResponseThrowsAuthenticationException(): void
    {
        $this->expectException(AuthenticationException::class);

        $guzzle = $this->buildMockClient([new Response(401)]);
        (new HttpClient($this->config, $guzzle))->get('ticket');
    }

    public function test403ResponseThrowsAuthenticationException(): void
    {
        $this->expectException(AuthenticationException::class);

        $guzzle = $this->buildMockClient([new Response(403)]);
        (new HttpClient($this->config, $guzzle))->get('ticket');
    }

    public function test404ResponseThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundException::class);

        $guzzle = $this->buildMockClient([new Response(404)]);
        (new HttpClient($this->config, $guzzle))->get('ticket/999');
    }

    public function test429ResponseThrowsRateLimitException(): void
    {
        // RateLimiter retries on 429; inject enough identical responses to
        // exhaust all retries (default 3 → 4 total calls).
        $this->expectException(RateLimitException::class);

        $guzzle = $this->buildMockClient(array_fill(0, 4, new Response(429)));
        (new HttpClient($this->config, $guzzle))->get('ticket');
    }

    public function test422ResponseThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);

        $guzzle = $this->buildMockClient([new Response(422)]);
        (new HttpClient($this->config, $guzzle))->get('ticket');
    }

    public function test500ResponseThrowsServerException(): void
    {
        $this->expectException(ServerException::class);

        // Enough responses to exhaust all retries.
        $guzzle = $this->buildMockClient(array_fill(0, 4, new Response(500)));
        (new HttpClient($this->config, $guzzle))->get('ticket');
    }

    // -------------------------------------------------------------------------
    // URL construction
    // -------------------------------------------------------------------------

    public function testBuildUrlDoesNotProduceDoubleSlashes(): void
    {
        $history = [];
        $guzzle  = $this->buildMockClient([new Response(200, [], '{}')], $history);
        $client  = new HttpClient($this->config, $guzzle);

        // Even if path is prefixed with '/' the final URL must not contain '//'.
        $client->get('/ticket');

        $url = (string) $history[0]['request']->getUri();
        $this->assertStringNotContainsString(
            '//',
            str_replace('https://', '', $url),
            'URL must not contain accidental double-slashes.'
        );
    }

    public function testTenantAppearsInRequestUrl(): void
    {
        $history = [];
        $guzzle  = $this->buildMockClient([new Response(200, [], '{}')], $history);
        $client  = new HttpClient($this->config, $guzzle);

        $client->get('ticket');

        $url = (string) $history[0]['request']->getUri();
        $this->assertStringContainsString('testco.docbee.com', $url);
    }
}
