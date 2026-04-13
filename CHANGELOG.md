# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]

### Added
- **Complete API coverage** — 98 new DTO classes and 93 new Resource classes covering every
  endpoint in the Docbee OpenAPI specification (v1).
- **Sub-resource pattern** — 29 sub-resource classes for nested endpoints (e.g.
  `AgreementComponentResource`, `TicketMessageResource`, `SlaProfileWorkingHourResource`).
  Each is instantiated via a factory method on `DocbeeClient` that accepts the parent ID:
  `$client->ticketMessages(42)->listAll()`.
- `AbstractDTO::toFloat()` helper for `number`-type API fields.
- 15 new unit tests in `SubResourceTest` covering the sub-resource constructor-injection
  pattern, endpoint construction, and DTO mapping.
- **API compatibility checker** (`bin/check-api-compat.php` + `bin/ApiCompatChecker.php`) —
  compares the live Docbee OpenAPI specification against every DTO and Resource in the
  implementation.  Reports missing fields, extra fields (potentially deprecated), type
  mismatches, read-only mismatches, nested sub-field coverage, and unimplemented endpoints.
  Supports snapshot-based drift detection: saving a baseline with `--save-snapshot` lets
  future runs show exactly what changed in the API since the last acknowledged state.
  Reports can be saved as text (`--output`) or machine-readable JSON (`--json-output`).
- `config/api-compat.php` — committed configuration file containing the OpenAPI spec URL
  (`https://pcs.docbee.com/restApi/v1/openapi.json`) and all checker settings.
- `tests/Unit/ApiCompatibilityTest.php` — PHPUnit integration: skipped when spec is
  unreachable, marked incomplete (not failed) when differences are found.
- Comprehensive security test suite (`tests/Unit/Security/`) covering HTTP header injection,
  authentication header handling, response parser edge cases, rate-limiter behaviour,
  webhook payload validation, input validation, and DTO serialisation.

### Fixed
- **104 DTOs regenerated** from the OpenAPI specification using recursive `$ref` resolution,
  fixing all field-name and field-set mismatches introduced by the original manual authoring:
  - `CustomerDTO`: corrected `customerId` (was `customerNumber`), `customerStatus` (was
    `status`); removed non-spec fields (`email`, `phone`, `mobile`, `fax`, `website`,
    `street`, `zip`, `city`, `country`, `notes`, `active`).
  - `CustomerStatusDTO`: added missing fields (`name`, `selectable`).
  - `MaterialItemDTO`: replaced empty stub with correct fields; fixed `hasHasSerialNumber()`
    getter double-prefix → `hasSerialNumber()`.
  - `TicketDTO`: corrected `ticketStatus` (was `status`), `owner` (was `assignedUser`),
    `description` (was `title`); removed invented fields (`orderId`, `closedAt`, etc.).
- `CustomerResource`: replaced `findByCustomerNumber()` with `findByCustomerId()`;
  replaced `findByEmail()` / `findActive()` (non-spec methods) with `findByCustomerStatus()`.
- `TicketResource`: replaced `findByStatus()` with `findByTicketStatus()`;
  `findByAssignedUser()` with `findByOwner()`; `findByOrderId()` with
  `findByReferenceNumber()`; added `findByErpReferenceNumber()`.
- `RateLimiter`: guard against negative `Retry-After` header values causing a negative
  `usleep()` argument (undefined behaviour).
- `WebhookValidator::parseAndValidate()`: guard against non-object JSON (scalars, arrays)
  causing a `TypeError` on the `validate(?array)` call.

### Changed
- `DocbeeClient` expanded with accessor methods for all new top-level resources and factory
  methods for all sub-resources.
- `README.md` updated: PHP requirement corrected to ≥ 8.3, full resource table added,
  sub-resource section added, resource-specific method examples corrected to match the API
  spec, API compatibility checker section added.
- All unit tests for `TicketDTO`, `TicketResource`, `CustomerDTO`, and `CustomerResource`
  rewritten to use spec-correct field names.

---

## [2.0.0] — 2026-01-xx

> Complete rewrite. Replaces the original procedural API wrappers with a professional,
> layered architecture.

### Added
- `AbstractResource` base class providing `find()`, `list()`, `listAll()`, `cursor()`,
  `count()`, `create()`, `update()`, `delete()`, `findModifiedSince()`, `findCreatedSince()`.
- `AbstractDTO` base class with `fromArray()`, `toArray()`, `toInt()`, `toBool()`,
  `toString()` helpers; implements `JsonSerializable`.
- `DocbeeClient` as the single entry point, with lazily-initialised resource accessors.
- `DocbeeConfig` with direct construction, `fromEnv()`, and `fromArray()` factory methods;
  configurable timeout, connect-timeout, and max-retries.
- `QueryBuilder` fluent API for filters, sorting, pagination, `modifiedSince`, `createdSince`.
- `FilterOperator` enum for type-safe filter construction.
- `HttpClient` / `HttpClientInterface` with Guzzle integration, automatic retries on
  429/5xx, and PSR-3 logging.
- Exception hierarchy: `DocbeeApiException`, `AuthenticationException`, `NotFoundException`,
  `RateLimitException`, `ValidationException`, `ServerException`.
- `WebhookResource` with `register()`, `createLink()`, and typed event-type constants.
- `WebhookValidator` utility for validating and parsing incoming webhook payloads.
- Resource classes: `CustomerResource`, `CustomerContactResource`,
  `CustomerLocationResource`, `TicketResource`, `UserResource`, `TagResource`,
  `ServiceTypeResource`, `PriorityResource`, `RequestTypeResource`,
  `TicketStatusResource`, `DocumentResource`, `DocumentTaskResource`,
  `DocumentTemplateResource`.
- Full PHPUnit test suite (87 tests) covering DTOs, resources, query builder,
  configuration, and webhook validation.
- PHP 8.3 language features: typed class constants (`const string`), `#[Override]`
  attribute, `json_validate()`.

### Changed
- Minimum PHP version raised from 8.0 to **8.3**.
- `composer.json` updated with proper `require`, `require-dev`, `autoload`,
  `autoload-dev`, and `scripts` sections.

### Removed
- All original procedural API wrapper classes (`APICall.php`, `Customer.php`,
  `Ticket.php`, etc.).
- Legacy test scripts in `tests/`.

---

## [1.x] — Pre-rewrite

Original implementation using direct procedural API wrappers. Not versioned formally.
