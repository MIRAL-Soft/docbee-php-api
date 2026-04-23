<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\FileDTO;

/** Provides access to Docbee file upload and download. */
final class FileResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /** Upload a file. */
    public function upload(array $data): FileDTO
    {
        return FileDTO::fromArray($this->http->post('file/upload', $data));
    }

    /** Get file download URL data. */
    public function download(int $id): array
    {
        return $this->http->get("file/{$id}/download");
    }

    /** Get file show data. */
    public function show(int $id): array
    {
        return $this->http->get("file/{$id}/show");
    }
}
