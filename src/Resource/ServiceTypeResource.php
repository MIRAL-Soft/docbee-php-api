<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ServiceTypeDTO;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee service types (Leistungen).
 *
 * @extends AbstractResource<ServiceTypeDTO>
 *
 * **Docbee filter quirks (confirmed by live tests):**
 *
 * - No filter parameter works server-side on this endpoint: `name-eq=`, `name=`,
 *   `number-eq=`, and `number=` are all silently ignored — the API always returns
 *   the unfiltered list.  `findByName()` and `findByNumber()` therefore perform a
 *   paginated cursor scan with an exact client-side match.
 *
 * - The `number` field is NOT included in the default list response — calling
 *   `list()` / `listAll()` / `cursor()` without an explicit `fields()` yields
 *   DTOs where `getNumber()` returns `null`.  Request it explicitly:
 *   `QueryBuilder::new()->fields(['id', 'name', 'number', 'deactivated'])`.
 *   `findByName()` and `findByNumber()` already do this internally.
 *
 * - There are no dedicated `/serviceType/findByNumber/{n}` or
 *   `/serviceType/findByName/{n}` endpoints (confirmed: both return HTTP 404).
 */
final class ServiceTypeResource extends AbstractResource
{
    use NotSearchable;

    protected string $endpoint = 'serviceType';
    protected string $dtoClass = ServiceTypeDTO::class;
    protected string $listKey  = 'serviceType';

    /**
     * Finds a service type by its exact name.
     *
     * The Docbee API does not honour any server-side filter on the `/serviceType`
     * endpoint — `name-eq=` and `name=` are both silently ignored.  This method
     * therefore performs a paginated cursor scan and applies an exact client-side
     * match.  For a typical tenant with ≤ 200 service types this takes ~2 API calls.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByName(string $name): ServiceTypeDTO
    {
        foreach ($this->cursor(QueryBuilder::new()->fields(['id', 'name', 'number', 'deactivated'])) as $st) {
            if ($st->getName() === $name) {
                return $st;
            }
        }

        throw new NotFoundException(
            message:    "ServiceType with name '{$name}' not found.",
            statusCode: 404,
            requestUrl: $this->endpoint,
        );
    }

    /**
     * Finds a service type by its number (external ERP article number).
     *
     * The Docbee API does not honour any server-side filter on the `/serviceType`
     * endpoint — `number-eq=` and `number=` are both silently ignored, and the
     * `number` field is excluded from the default list response.  This method
     * requests `fields=id,name,number,deactivated` explicitly and performs a
     * paginated cursor scan with an exact client-side match.
     *
     * @throws NotFoundException when not found.
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function findByNumber(string $number): ServiceTypeDTO
    {
        foreach ($this->cursor(QueryBuilder::new()->fields(['id', 'name', 'number', 'deactivated'])) as $st) {
            if ($st->getNumber() === $number) {
                return $st;
            }
        }

        throw new NotFoundException(
            message:    "ServiceType with number '{$number}' not found.",
            statusCode: 404,
            requestUrl: $this->endpoint,
        );
    }

    /**
     * @inheritDoc
     *
     * **Docbee API limitation:** `DELETE /serviceType/{id}` always returns HTTP 403 —
     * service types cannot be deleted via the REST API regardless of token permissions.
     * This method will throw {@see \miralsoft\docbee\api\Exception\AuthenticationException}.
     * Use `update($id, ['deactivated' => true])` to deactivate a service type instead.
     */
    public function delete(int $id): void
    {
        parent::delete($id);
    }

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}
