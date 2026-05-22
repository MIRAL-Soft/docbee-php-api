<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Client;

use miralsoft\docbee\api\Exception\DocbeeApiException;

/**
 * Contract for HTTP clients used by Docbee resource classes.
 *
 * Implementing this interface allows resource tests to inject a mock or stub
 * without depending on the concrete {@see HttpClient} class.
 */
interface HttpClientInterface
{
    /**
     * Performs a GET request and returns the decoded response array.
     *
     * @return array<string, mixed>
     * @throws DocbeeApiException
     */
    public function get(string $path): array;

    /**
     * Performs a POST request with a JSON body and returns the decoded response.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws DocbeeApiException
     */
    public function post(string $path, array $data = []): array;

    /**
     * Performs a PUT request with a JSON body and returns the decoded response.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws DocbeeApiException
     */
    public function put(string $path, array $data = []): array;

    /**
     * Performs a DELETE request.
     *
     * @throws DocbeeApiException
     */
    public function delete(string $path): void;

    /**
     * Performs a GET request and returns the raw (binary) response body.
     *
     * Use this for endpoints that return file data (PDF, CSV, …) rather than JSON.
     *
     * @return string Raw response bytes.
     * @throws DocbeeApiException
     */
    public function getRaw(string $path): string;

    /**
     * Performs a POST request with a JSON body and returns the raw (binary) response body.
     *
     * Use this for export endpoints that return file data (PDF, CSV, …) rather than JSON.
     *
     * @param array<string, mixed> $data
     * @return string Raw response bytes.
     * @throws DocbeeApiException
     */
    public function postRaw(string $path, array $data = []): string;
}
