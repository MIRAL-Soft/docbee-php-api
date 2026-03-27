<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use LogicException;
use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AbstractDTO;
use miralsoft\docbee\api\Resource\AbstractResource;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see AbstractResource} subclass validation guards.
 */
final class AbstractResourceTest extends TestCase
{
    public function testThrowsWhenEndpointIsEmpty(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/endpoint/');

        new class($this->createMock(HttpClientInterface::class)) extends AbstractResource {
            protected string $endpoint = '';     // intentionally empty
            protected string $dtoClass = 'Foo';
            protected string $listKey  = 'foo';

            public static function fromArray(array $data): static { return new static(...[]); }
            public function toArray(): array { return []; }
            public function getId(): ?int { return null; }
        };
    }

    public function testThrowsWhenDtoClassIsEmpty(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/dtoClass/');

        new class($this->createMock(HttpClientInterface::class)) extends AbstractResource {
            protected string $endpoint = 'ticket';
            protected string $dtoClass = '';     // intentionally empty
            protected string $listKey  = 'ticket';

            public static function fromArray(array $data): static { return new static(...[]); }
            public function toArray(): array { return []; }
            public function getId(): ?int { return null; }
        };
    }

    public function testThrowsWhenListKeyIsEmpty(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/listKey/');

        new class($this->createMock(HttpClientInterface::class)) extends AbstractResource {
            protected string $endpoint = 'ticket';
            protected string $dtoClass = 'Foo';
            protected string $listKey  = '';     // intentionally empty

            public static function fromArray(array $data): static { return new static(...[]); }
            public function toArray(): array { return []; }
            public function getId(): ?int { return null; }
        };
    }

    public function testProperlyConfiguredResourceConstructsWithoutException(): void
    {
        // Should not throw
        $resource = new class($this->createMock(HttpClientInterface::class)) extends AbstractResource {
            protected string $endpoint = 'ticket';
            protected string $dtoClass = AbstractDTO::class;
            protected string $listKey  = 'ticket';
        };

        $this->assertInstanceOf(AbstractResource::class, $resource);
    }
}
