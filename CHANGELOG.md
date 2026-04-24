# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]

### Added
- **`AbstractResource::search(string $query)`** — new method available on **every** resource
  class.  Sets the Docbee `search` parameter and returns all matching records via `listAll()`.
  The Docbee API supports `search` on 70 endpoints; what is searched depends on the resource
  (name, number, email, reference number, …).  Notable use cases:
  - `/customer` → matches customer name **and** Kundennummer (UI display number)
  - `/customerContact` → matches contact name, email, phone, …
  - `/customerLocation` → matches location name and address fields
  - `/ticket` → matches title, description, reference number, …
  - `/user` → matches username and email
  - `/object` → matches object name and serial/scan codes
- **`QueryBuilder::search(string $query)`** — backing method used by `AbstractResource::search()`.
  Can also be combined with other filters: `QueryBuilder::new()->search('…')->filterEq(…)`.
- **`CustomerResource::search()`** now inherits from `AbstractResource`; the Kundennummer
  workflow is documented in the class DocBlock.  Example:
  ```php
  $customers  = $client->customers()->search('12355');   // by Kundennummer
  $internalId = $customers[0]->getId();                  // e.g. 241957
  $contacts   = $client->customerContacts()->findByCustomer($internalId);
  $locations  = $client->customerLocations()->findByCustomer($internalId);
  ```
- **`QueryBuilder::param(string $key, mixed $value)`** — new method for plain query parameters
  without an operator suffix (e.g. `customer=42` instead of `customer-eq=42`).  Required for
  endpoints where the Docbee API accepts only the bare field name as a filter parameter.
- **`CustomerContactResource::findByCustomerLocation(int $customerLocationId)`** — new method
  to filter contacts by location, using the plain `customerLocation=<id>` parameter.
- **`AbstractResource::find(int $id, array $fields = [])`** — optional `$fields` parameter
  appends `?fields=f1,f2,...` to the request URL, allowing callers to restrict which fields
  the API returns.  Fully backward-compatible; existing calls without a second argument are
  unaffected.
- **Integration test infrastructure** — full read-only live-API test suite:
  - `tests/bootstrap.php` — PHPUnit bootstrap that loads `tests/.env.test` automatically
    (dependency-free parser; skips gracefully when the file is absent).
  - `tests/.env.test.example` — template with `DOCBEE_TENANT`, `DOCBEE_TOKEN`, and all
    ~25 optional parent-ID variables for sub-resource tests.
  - `tests/Integration/IntegrationTestCase` — base class that auto-skips all tests when
    credentials are missing; `callApi()` helper converts HTTP 403/404 responses to skipped
    tests instead of errors; `optionalIntEnv()` for conditional per-test parent IDs.
  - **13 integration test classes** (262 tests) covering every readable resource and
    sub-resource exposed by `DocbeeClient`:
    `AgreementResourceIntegrationTest`, `ConfigResourceIntegrationTest`,
    `CustomerResourceIntegrationTest`, `DocBeeDocumentSubResourceIntegrationTest`,
    `DocumentResourceIntegrationTest`, `FinanceResourceIntegrationTest`,
    `OrganizationResourceIntegrationTest`, `PlanningResourceIntegrationTest`,
    `ProtocolResourceIntegrationTest`, `ProtocolSubResourceIntegrationTest`,
    `SystemResourceIntegrationTest`, `TicketResourceIntegrationTest`,
    `UserResourceIntegrationTest`.
  - `phpunit.xml` — new `Integration` test suite; `defaultTestSuite="Unit"` ensures
    integration tests never run accidentally during normal `composer test`.
  - `composer.json` — `"test:integration"` script added.
  - `.gitignore` — `tests/.env.test` excluded to prevent credential commits.
- **15 new Resource classes** for previously unimplemented endpoint groups:
  - **Group C — Protocol entry / group variants**: `ProtocolGroupEntriesResource`
    (indexed group instance entries, `v1/protocol/{id}/protocolGroupEntries/{groupId}`),
    `ProtocolGroupResource` (standalone class covering entries + mapping at both the
    all-instances and per-`groupIdx` level), `ProtocolPlanningTimeResource`
    (`v1/protocol/{id}/planningTime`).
  - **Group D — Document tasks**: `DocBeeDocumentTaskResource`, `DocBeeDocumentTaskMaterialResource`,
    `DocBeeDocumentTaskPlanningTimeResource`, `DocBeeDocumentTaskWorkLogResource`
    (all nested under `v1/docBeeDocument/{id}/task/…`).
  - **Group E — Document travel logs**: `DocBeeDocumentTravelLogResource`
    (`v1/docBeeDocument/{id}/travelLog`).
  - **Group F — Document-template task templates**: `DocBeeDocumentTemplateTaskTemplateResource`,
    `DocBeeDocumentTemplateTaskTemplateMaterialTemplateResource`,
    `DocBeeDocumentTemplateTaskTemplatePlanningTimeTemplateResource`,
    `DocBeeDocumentTemplateTaskTemplateWorkLogTemplateResource`,
    `DocBeeDocumentTemplateTravelLogTemplateResource`.
  - **Group G — Protocol template entry elements**: `ProtocolTemplateEntryElementResource`
    (`v1/protocolTemplateEntry/{id}/element`).
  - **Messaging**: `MessageResource` (standalone class, `POST v1/message/sendMail`).
- **Action methods** added to existing resource classes:
  - `UserResource`: `changePassword()`, `disableMe2FA()`, `registerMe2FA()`.
  - `ProtocolTemplateTypeResource`: `getNavigationItems()`.
  - `ReportResource`: `getElementTypes()`, `getElementType(int $id)`,
    `getParameterTypes()`, `getParameterType(int $id)`.
  - `ProtocolTemplateResource`: `getComponent()`, `updateComponent()`,
    `getGroupComponent()`, `updateGroupComponent()`.
  - `ProtocolGroupDataResource`: `markFinish()`.
  - `ProtocolResource`: `export(int $exportProfileId)`, `exportByIds(int $exportProfileId, array $ids)`.
  - `ObjectCategoryResource`: `getCustomFields(int $id)`, `updateCustomFields(int $id, array $data)`.
  - `ExportProfileResource`: by-ID lookup methods for all six reference types
    (`getExportEncoding`, `getExportFormat`, `getExportType`, `getFieldDomain`,
    `getFieldDomainProperty`, `getFieldDomainPropertyDataFormatter`).
  - `CustomerResource`, `CustomerContactResource`, `CustomerObjectResource`:
    `export(int $exportProfileId)`, `exportByIds(int $exportProfileId, array $ids)`.
  - `ObjectResource`, `ObserverResource`: `guess(array $data)`.
- **Optional-constructor pattern** for `PaymentProfileMappingResource` and
  `ProtocolDocumentTemplateResource`: omitting the parent ID gives the standalone
  top-level endpoint; passing it gives the nested sub-resource endpoint.
- **`DocbeeClient` factory methods** for all new resource classes:
  `messages()`, `documentTravelLogs()`, `documentTemplateTaskTemplates()`,
  `documentTemplateTaskTemplateMaterials()`, `documentTemplateTaskTemplatePlanningTimes()`,
  `documentTemplateTaskTemplateWorkLogs()`, `documentTemplateTravelLogTemplates()`,
  `protocolTemplateEntryElements()`, `docBeeDocumentTasks()`,
  `docBeeDocumentTaskMaterials()`, `docBeeDocumentTaskPlanningTimes()`,
  `docBeeDocumentTaskWorkLogs()`, `protocolGroupEntries()`, `protocolGroup()`,
  `protocolPlanningTimes()`.
- **26 new unit tests** in `SubResourceTest` covering the new sub-resource
  constructor-injection pattern, endpoint construction, and DTO mapping.
- **API compatibility checker improvements** (`bin/ApiCompatChecker.php`):
  - Method-body scanning — `extractMethodEndpointsFromSource()` now scans every
    `$this->http->*()` call site for the path argument, resolving `{$this->endpoint}`
    and `{$this->base}` placeholders against the resource's discovered base endpoint.
  - Ternary constructor detection — endpoints set via a ternary expression
    (`$this->endpoint = $x ? 'a' : 'b'`) are now detected correctly.
  - Spec `${id}` normalization — the spec occasionally uses `${id}` (dollar sign
    before the brace); the normaliser now treats this identically to `{id}`.
  - Wildcard-segment matching — a `*` in an implemented pattern now matches any
    single path segment in the spec path, enabling MapView-filter coverage detection.
  - Standalone-resource support — resource classes without an `$endpoint` property
    (e.g. `MessageResource`, `ProtocolGroupResource`) are no longer silently skipped;
    their method bodies are scanned for explicit path literals.
  - Private helper-method extraction — URL-builder methods (e.g. `private function
    base(int $x): string { return "v1/…/{$x}"; }`) are detected and their return
    templates combined with the concatenated suffix in each HTTP call site.
  - These improvements reduce the false-positive "unimplemented" count from **262 to 1**
    (one known spec/implementation discrepancy: `protocol/findByNumber` uses a path
    parameter in the implementation vs. a query parameter in the spec).

### Fixed
- **Double `v1/` URL prefix** — `DocbeeConfig::BASE_URL_TEMPLATE` already includes
  `/v1/`; ~177 resource files had endpoint strings starting with `'v1/…'`, resulting in
  URLs like `restApi/v1/v1/agreement`.  All endpoint strings stripped to their bare path
  (e.g. `'agreement'`, `'invoice'`).  `SubResourceTest` expectations updated accordingly.
- **camelCase endpoint names** — eight resources used lowercase-compound endpoint strings
  that the API does not recognise (HTTP 404):
  - `DocumentResource`: `'docbeedocument'` → `'docBeeDocument'`
  - `DocumentTaskResource`: `'docbeedocumenttask'` → `'docBeeDocumentTask'`
  - `DocumentTemplateResource`: `'docbeedocumenttemplate'` → `'docBeeDocumentTemplate'`
  - `TicketStatusResource`: `'ticketstatus'` → `'ticketStatus'`
  - `RequestTypeResource`: `'requesttype'` → `'requestType'`
  - `ServiceTypeResource`: `'servicetype'` → `'serviceType'`
  - `CustomerContactResource`: `'customercontact'` → `'customerContact'`
  - `CustomerLocationResource`: `'customerlocation'` → `'customerLocation'`
- `ProtocolEntryResource`: endpoint corrected from `v1/protocol/{id}/entry`
  (non-existent path) to `v1/protocol/{id}/protocolEntry`; added six action methods:
  `getByEntryMapping()`, `updateByEntryMapping()`, `getByEntryMappingAndGroupIdx()`,
  `updateByEntryMappingAndGroupIdx()`, `findByPlaceholderName()`,
  `updateByPlaceholderName()`.
- `ContingentItemRecurrenceDTO`: removed spurious `name` field that is not present
  in the OpenAPI specification.
- **`CustomerContactResource::findByCustomer()` and `CustomerLocationResource::findByCustomer()`**
  — fixed server-side filtering.  The Docbee API requires `customer=<id>` (plain parameter)
  for these endpoints; the standard `customer-eq=<id>` operator form is silently ignored,
  causing all records to be returned regardless of the filter.  Both methods now use
  `QueryBuilder::param()` and auto-paginate via `listAll()` (previously `list()` returned
  only the first 50 records).
- **`DocumentTaskResource::create()`** — overrides the inherited `create()` with an
  immediate `\LogicException`.  `POST /docBeeDocumentTask` returns HTTP 500 from the
  Docbee server; tasks must be created via the sub-resource endpoint
  `docBeeDocument/{id}/task` (`$client->docBeeDocumentTasks($id)->create([...])`).
  The exception message contains the correct alternative.

### Changed
- `README.md`: sub-resource table updated with all new factory methods and corrected
  `protocolEntries` endpoint (`v1/protocol/{id}/protocolEntry`).
- **Documented known Docbee API limitations** in resource class DocBlocks (no behaviour change,
  except the `customer` plain-parameter fix described above):
  - `AbstractResource::find()`: several ticket fields are absent in `GET /ticket/{id}`
    responses (`description`, `erpReferenceNumber`, `internalDescription`, `priority`,
    `ticketStatus`) — server-side limitation, cannot be worked around via `$fields`.
  - `TicketResource::findByErpReferenceNumber()`: filter is a broad/fuzzy match, not an
    exact match; callers must filter the result client-side.
  - `TicketResource` and `CustomerContactResource`: `filterEq('id', …)` acts as a
    foreign-key (customer ID) filter, not a primary-key filter — use `find(int $id)`
    for single-record lookup.
  - `DocumentResource`: `filterEq('ticket', $ticketId)` is silently ignored by the API;
    workaround documented (fetch by customer, filter client-side).

---

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
