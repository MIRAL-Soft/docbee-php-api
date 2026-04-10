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

### Changed
- `DocbeeClient` expanded with accessor methods for all new top-level resources and factory
  methods for all sub-resources.
- `README.md` updated: PHP requirement corrected to ≥ 8.3, full resource table added,
  sub-resource section added with usage examples.

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
