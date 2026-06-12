<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use InvalidArgumentException;
use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ModuleDTO;

/** Provides access to Docbee module settings. */
final class ModuleResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /**
     * Returns a list of all modules.
     *
     * @return array<int, mixed>
     */
    public function list(): array
    {
        return $this->http->get('module')['module'] ?? [];
    }

    /**
     * Returns a specific module by name.
     *
     * @throws InvalidArgumentException when $moduleName is empty.
     */
    public function find(string $moduleName): ModuleDTO
    {
        return ModuleDTO::fromArray($this->http->get('module/' . $this->encodeName($moduleName)));
    }

    /**
     * Updates a module setting.
     *
     * @param array<string, mixed> $data
     * @throws InvalidArgumentException when $moduleName is empty.
     */
    public function update(string $moduleName, array $data): ModuleDTO
    {
        return ModuleDTO::fromArray($this->http->put('module/' . $this->encodeName($moduleName), $data));
    }

    /**
     * Deletes a module.
     *
     * @throws InvalidArgumentException when $moduleName is empty.
     */
    public function delete(string $moduleName): void
    {
        $this->http->delete('module/' . $this->encodeName($moduleName));
    }

    /**
     * Validates and URL-encodes a module name for use as a path segment.
     *
     * An empty name would silently hit the collection endpoint (`DELETE /module`!),
     * and an unencoded name could redirect the request to a different resource.
     */
    private function encodeName(string $moduleName): string
    {
        if (trim($moduleName) === '') {
            throw new InvalidArgumentException('ModuleResource: moduleName must not be empty.');
        }
        return rawurlencode($moduleName);
    }
}
