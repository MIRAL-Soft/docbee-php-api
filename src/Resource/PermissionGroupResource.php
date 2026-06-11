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

    /** @throws \InvalidArgumentException when $type is empty. */
    public function getHierarchy(string $type): array
    {
        if (trim($type) === '') {
            throw new \InvalidArgumentException('getHierarchy(): type must not be empty.');
        }
        return $this->http->get("{$this->endpoint}/" . rawurlencode($type) . '/hierarchy');
    }
    public function getCalendarSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/calendarSiteConfig"); }
    public function updateCalendarSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/calendarSiteConfig", $data); }
    public function getDocBeeDocumentSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/docBeeDocumentSiteConfig"); }
    public function updateDocBeeDocumentSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/docBeeDocumentSiteConfig", $data); }
    public function getProtocolSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/protocolSiteConfig"); }
    public function updateProtocolSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/protocolSiteConfig", $data); }
    public function getSchedulerSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/schedulerSiteConfig"); }
    public function updateSchedulerSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/schedulerSiteConfig", $data); }
    public function getTicketSiteConfig(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/ticketSiteConfig"); }
    public function updateTicketSiteConfig(int $id, array $data): array { return $this->http->put("{$this->endpoint}/{$id}/ticketSiteConfig", $data); }
}