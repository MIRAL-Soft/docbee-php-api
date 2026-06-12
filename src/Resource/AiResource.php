<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/** Provides access to Docbee AI features. */
final class AiResource
{
    public function __construct(private readonly HttpClientInterface $http) {}

    /**
     * Generate a mail message using AI.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function generateMailMessage(array $data): array
    {
        return $this->http->post('ai/generateMailMessage', $data);
    }

    /**
     * Generate a task description using AI.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function generateTaskDescription(array $data): array
    {
        return $this->http->post('ai/generateTaskDescription', $data);
    }
}
