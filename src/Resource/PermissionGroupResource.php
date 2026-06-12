<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\PermissionGroupDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee PermissionGroup records.
 *
 * @extends AbstractResource<PermissionGroupDTO>
 */
final class PermissionGroupResource extends AbstractResource
{
    protected string $endpoint = 'permissionGroup';
    protected string $dtoClass = PermissionGroupDTO::class;
    protected string $listKey  = 'permissionGroup';

    /**
     * @return array<string, mixed>
     * @throws \InvalidArgumentException when $type is empty.
     */
    public function getHierarchy(string $type): array
    {
        if (trim($type) === '') {
            throw new \InvalidArgumentException('getHierarchy(): type must not be empty.');
        }
        return $this->http->get("{$this->endpoint}/" . rawurlencode($type) . '/hierarchy');
    }
    /** @return array<string, mixed> */
    public function getCalendarSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/calendarSiteConfig"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateCalendarSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/calendarSiteConfig", $data); }
    /** @return array<string, mixed> */
    public function getDocBeeDocumentSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/docBeeDocumentSiteConfig"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateDocBeeDocumentSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/docBeeDocumentSiteConfig", $data); }
    /** @return array<string, mixed> */
    public function getProtocolSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/protocolSiteConfig"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateProtocolSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/protocolSiteConfig", $data); }
    /** @return array<string, mixed> */
    public function getSchedulerSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/schedulerSiteConfig"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateSchedulerSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/schedulerSiteConfig", $data); }
    /** @return array<string, mixed> */
    public function getTicketSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/ticketSiteConfig"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateTicketSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/ticketSiteConfig", $data); }
}