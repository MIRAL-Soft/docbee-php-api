<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\FileDTO;

/** Provides access to Docbee file upload and download. */
final class FileResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /**
     * Upload a file.
     *
     * @param array<string, mixed> $data
     */
    public function upload(array $data): FileDTO
    {
        return FileDTO::fromArray($this->http->post('file/upload', $data));
    }

    /**
     * Downloads a file and returns its raw bytes.
     *
     * The endpoint returns `application/octet-stream` (binary, per OpenAPI spec) —
     * previously this method JSON-parsed the response and always failed on real files.
     *
     * @return string Raw file bytes.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function download(int $id): string
    {
        return $this->http->getRaw("file/{$id}/download");
    }

    /**
     * Returns the file's raw bytes for inline display (`Content-Disposition: inline`).
     *
     * Same binary response as {@see download()} (`application/octet-stream` per spec).
     *
     * @return string Raw file bytes.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function show(int $id): string
    {
        return $this->http->getRaw("file/{$id}/show");
    }
}
