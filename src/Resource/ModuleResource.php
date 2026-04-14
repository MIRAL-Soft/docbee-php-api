<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ModuleDTO;

/** Provides access to Docbee module settings. */
final class ModuleResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /** Returns a list of all modules. */
    public function list(): array
    {
        return $this->http->get('v1/module')['module'] ?? [];
    }

    /** Returns a specific module by name. */
    public function find(string $moduleName): ModuleDTO
    {
        return ModuleDTO::fromArray($this->http->get("v1/module/{$moduleName}"));
    }

    /** Updates a module setting. */
    public function update(string $moduleName, array $data): ModuleDTO
    {
        return ModuleDTO::fromArray($this->http->put("v1/module/{$moduleName}", $data));
    }

    /** Deletes a module. */
    public function delete(string $moduleName): void
    {
        $this->http->delete("v1/module/{$moduleName}");
    }
}
