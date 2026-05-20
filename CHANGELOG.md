# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]

### Fixed
- **`DocumentResource::fromTemplate()` and `TicketResource::fromTemplate()` — wrong payload key:**
  Both methods sent `'template' => $templateId` in the POST body, causing HTTP 400
  `"You must specify a templateId or a templateName"` on every call.  The correct key
  is `templateId`.  Fixed in both resources; regression tests added.

  **Additional findings (live-tested on `DocumentResource::fromTemplate()`):**
  - `customer` (int) — accepted and applied to the created document ✓
  - `ticket` (int) — causes HTTP 400 `"Unknown error"`; cannot be set via this endpoint
  - `erpReferenceNumber` (string) — silently ignored (HTTP 200, value stays null)
  - `billable` (bool) — silently ignored (HTTP 200, value stays null)

  **Alternative for creating a document with a ticket link:**
  ```php
  $payload = $client->documentTemplates()->createPayloadForDocBeeDocument($templateId);
  $payload['customer'] = $customerId;
  $payload['ticket']   = $ticketId;
  $doc = $client->documents()->create($payload);
  ```
  This creates the document including embedded tasks and the ticket link in one call.

- **`ServiceTypeResource::delete()` — documented HTTP 403 limitation:**
  `DELETE /serviceType/{id}` always returns HTTP 403 regardless of token permissions —
  service types cannot be deleted via the Docbee REST API.  Added a `@inheritDoc` override
  with an explicit note pointing callers to `update($id, ['deactivated' => true])` as the
  correct alternative.  (`customFields()->delete()` has the same restriction, already noted
  in the CHANGELOG.)

- **Universal `filterEq` / `param` filter-ignore bug — silent false-positive `findByX` results**
  (live-tested against a tenant with 125 service types, 2 261 customers, 13 ticket statuses,
  26 tags, 4 priorities, 120 document templates, 1 user; confirmed by non-existent-needle probe):

  The Docbee API **silently ignores ALL filter parameters** on most entity-list endpoints —
  `name-eq=`, `number-eq=`, `customerId=`, `email-eq=`, `isClosed-eq=` and plain variants all
  return the full unfiltered list.  Any `findByX()` helper that relied on `filterEq + limit(1)`
  would return the **first item in the list** regardless of the needle value, making `NotFoundException`
  unreachable on non-empty tenants and silently returning the wrong entity to every caller.

  The root cause was first discovered in `ServiceTypeResource::findByNumber()` (81 HTTP 400
  errors during a service-type sync: the wrong service type was being renamed instead of the
  correct one).

  **All affected methods have been rewritten to use a paginated cursor scan + exact client-side
  match.** Summary of changes:

  | Resource | Method | Before | After |
  |---|---|---|---|
  | `ServiceTypeResource` | `findByName()` | `filterEq('name', …)` — ignored | cursor + exact `getName()` match |
  | `ServiceTypeResource` | `findByNumber()` | `filterEq('number', …)` — ignored | cursor + exact `getNumber()` match |
  | `PriorityResource` | `findByName()` | `filterEq('name', …)` — ignored | cursor + exact `getName()` match |
  | `TagResource` | `findByName()` | `filterEq('name', …)` — ignored | cursor + exact `getName()` match |
  | `TicketStatusResource` | `findByName()` | `filterEq('name', …)` — ignored | cursor + exact `getName()` match |
  | `TicketStatusResource` | `findClosed()` | `filterEq('isClosed', true)` — field doesn't exist | cursor with `fields=id,name,behaviour` + `behaviour === 'CLOSED'` |
  | `DocumentTemplateResource` | `findByName()` | `filterEq('name', …)` — ignored | cursor + exact `getName()` match |
  | `UserResource` | `findByEmail()` | `filterEq('email', …)` — ignored | delegates to existing `findFirstByEmail()` (server-side endpoint) |
  | `CustomerResource` | `findByCustomerId()` | `param('customerId', …)` — ignored | cursor with `fields=id,customerId` + exact `getCustomerId()` match |
  | `CustomerResource` | `findOneByCustomerId()` | `param('customerId', …)` — ignored | same cursor approach, returns `null` instead of throwing |

  **Additional findings from live probes:**
  - `ServiceType.number` is NOT returned in the default list response — must be requested via
    `?fields=id,name,number,deactivated`.  `findByNumber()` now always requests this field.
  - `TicketStatus.isClosed` is NOT a real API field — it is absent from all API responses.
    "Closed" statuses have `behaviour === 'CLOSED'`; `TicketStatusResource` now exposes
    `BEHAVIOUR_CLOSED`, `BEHAVIOUR_NORMAL`, and `BEHAVIOUR_PAUSED` constants.
  - `CustomerResource` note: with 2 261+ customers the cursor scan takes ~23 pages (~11 s).
    For high-frequency lookups, cache the `customerId → id` mapping at startup via `cursor()`.

### Added
- **Performance fixes — server-filter corrections and N+1 elimination** (live-tested against
  a tenant with 55 070 tickets and 3 963 documents; OpenAPI spec 2025.3.0 used as reference):

  **`QueryBuilder` — `changedSince` / `createdSince` date format fixed (critical bug):**
  The Docbee API requires `YYYY-MM-DDTHH:mm:ss.mmmZ` (ISO 8601 UTC with millisecond precision).
  The previous `Y-m-d\TH:i:s` format caused HTTP 400 "changedSince has invalid date format" on
  every `findModifiedSince()` / `findCreatedSince()` call.  The `.000Z` suffix is now appended
  automatically — no caller changes needed.

  **`TicketResource::findByErpReferenceNumber()` — 5.5 min → ≤ 5 s:**
  The previous implementation used `erpReferenceNumber-eq=` (server filter silently ignored →
  full scan of all 55 070 tickets, 329 s).  The new implementation uses `search=<value>` (the
  only server-side mechanism for this field on tickets, confirmed by OpenAPI spec), requests
  `fields=id,erpReferenceNumber` so the field is present in the list response, and then
  applies an exact-match filter client-side.  Typical timing: 2–4 s regardless of tenant size.

  **`DocumentResource::findByTicket()` — new method, correct filter:**
  `filterEq('ticket', …)` (`ticket-eq=`) is silently ignored by the Docbee API and returns
  all documents unfiltered.  The OpenAPI spec documents `ticketIds` (array[string]) as the
  correct parameter.  The new `findByTicket(int $ticketId)` uses `param('ticketIds', …)` and
  was verified live (12 exact-match results for a ticket with 12 linked documents, vs 3 963
  with the old filter).

  **`DocumentResource::findByCustomFieldValue()` — N+1 eliminated:**
  Previously, looking up a document by a custom field value required one `getCustomFieldValues()`
  call per document (= 3 963 API calls for a customer with 3 963 documents).  The new method
  uses `fields=id,customFields.id,customFields.value` in the list query, loading custom field
  values inline for every document in each page response.  This reduces 3 963 calls to ~40
  paginated calls.

  **`DocumentResource::cursorWithCustomFields()` — memory-efficient bulk custom-field reader:**
  Generator-based helper that yields documents with their custom fields pre-loaded (dot-notation
  field selection).  Designed for processing large datasets without holding the entire list in memory.

  **`DocumentResource::findByErpReferenceNumber()` — new method (scoped scan):**
  No server-side filter exists for `erpReferenceNumber` on documents (neither `-eq` nor plain
  form is honoured; document full-text search also does not cover this field).  The new method
  performs a paginated scan scoped to one customer with `fields=id,erpReferenceNumber`, minimising
  payload size.

  **`MaterialItemResource::findByNumber()` — honest O(n) implementation:**
  `GET /materialItem` exposes only `deactivated`, `limit`, `offset`, `fields`, `changedSince`,
  `sortings`, `tableSortings` — no `number` or `search` filter exists (confirmed via OpenAPI spec
  2025.3.0).  `findByNumber()` performs a cursor-based full scan and returns the first exact match.
  Callers are advised to cache the result or build a `number → id` map at startup.

  **`MaterialItemResource::findActive()` — convenience filter:**
  Adds `deactivated=0` — the only content filter the endpoint supports.

  **Docbee API quirks documented (confirmed by live tests):**
  - `changedSince` requires millisecond-precision UTC: `2026-05-18T12:00:00.000Z` ✅  
    Any other format (`2026-05-18T12:00:00`, `+02:00`, epoch ms, date-only) → HTTP 400.
  - `ticketIds=<id>` (plain array param) works for document→ticket filter; `ticket-eq=` is ignored.
  - `erpReferenceNumber-eq=` is ignored on both tickets and documents; only `search=` produces
    server-side narrowing for tickets (2 hits in 2.4 s for a 55 000-ticket tenant).
  - `GET /docBeeDocument/findByExternalId/{val}` does NOT match `externalReferenceNumber`,
    `erpReferenceNumber`, or `referenceNumber` — confirmed by live test (set all three fields,
    all returned 404 on `findByExternalId`).  The endpoint appears to search an internal
    integration field not settable via the standard REST API.
  - `customer-eq=<id>` on documents filters by company/organisation-level customer; the
    `customer` field returned in each document may differ (child entity — location or contact).
  - Custom field values ARE readable in list responses via `fields=id,customFields.id,customFields.value`
    (dot-notation), enabling batch reads in one paginated request instead of N individual calls.

- **Custom Fields — complete lifecycle support** (provisioning, assignment, reading and writing
  values).  The Docbee API works differently from most REST APIs for custom fields; the
  following notes describe the key behaviours discovered during live testing:

  **How the API works:**
  1. *Define* — `POST /customField` creates the field definition (name, parentType, type).
  2. *Assign* — `PUT /docBeeDocument/customFields` (or `/ticket/customFields`) assigns the
     field to an entity type globally.  This is a merge operation: the current list must be
     read first, the new ID appended, and the full merged list written back.
  3. *Write* — `PUT /docBeeDocument/{id}` with `{"customFields":[{"id":<cfId>,"value":<v>}]}`
     sets a value on a specific document.
  4. *Read* — `GET /docBeeDocument/{id}` returns values **only when requested with dot-notation**:
     `?fields=customFields.id,customFields.value` → `[{"id":102,"value":123},{"id":104,"value":"WO-12345"}]`.
     The seemingly equivalent `?fields=customFields` returns only a flat list of IDs
     (`[102,104]`) — **no values**.  This is a Docbee API behaviour confirmed by live testing.

  **Docbee API quirks (confirmed by live tests):**
  - `parentType-eq=` filter is silently ignored on `GET /customField` — use the plain
    `parentType=` parameter instead.
  - The default `GET /customField/{id}` response omits `parentType` and `type`; request
    `?fields=id,name,parentType,type` explicitly.
  - `DELETE /customField/{id}` returns HTTP 403 — field deletion is admin-only, not available
    via the REST API.

- **`DocumentTemplateResource` — full custom field support:**
  Document templates share custom-field definitions with regular documents (both use
  `parentType = DOCBEE_DOCUMENT`).  A single field definition can appear on Leistungen *and*
  Leistungsvorlagen simultaneously.  The following methods have been added:
  - `setCustomFieldValue(int $templateId, int $fieldId, mixed $value)` — sets one CF value via PUT.
  - `setCustomFieldValues(int $templateId, array $valuesByFieldId)` — sets multiple CF values in one request.
  - `getCustomFieldValues(int $templateId): array<int, mixed>` — returns a `fieldId => value` map
    via `?fields=customFields.id,customFields.value` dot-notation.
  - `getCustomFieldValue(int $templateId, int $fieldId): mixed` — returns a single field value or null.
  - `getCustomFieldIds(int $templateId): list<int>` — lightweight check: which fields have values set.
  - `hasCustomFieldValue(int $templateId, int $fieldId): bool` — presence check without reading values.
  - `cursorWithCustomFields(int $customerId): Generator` — yields templates with inline custom field data.
  - `findByCustomFieldValue(int $customerId, int $fieldId, mixed $value): array` — cursor scan matching
    templates by CF value (no server-side CF filter exists; reads values inline per page, no N+1).
  - `getCustomFields() / updateCustomFields()` — global assignment list for the entity type.

  **`CustomFieldResource::ensureAssignedToDocumentTemplate(int $fieldId)`** — idempotent helper
  that ensures the field is available on document templates.  **Live probe confirmed:**
  `GET/PUT /docBeeDocumentTemplate/customFields` returns HTTP 400 — the endpoint does not exist
  for templates.  Templates and regular documents share the same `docBeeDocument/customFields`
  assignment list; assigning a field to `docBeeDocument` is both necessary *and* sufficient for
  CF values to be readable and writable on templates.  `ensureAssignedToDocumentTemplate` is
  therefore an alias for `ensureAssignedToDocument` and does not need to be called separately.

  **`DocBeeDocumentTemplateDTO`** — `customFields` is now deserialized as `CustomFieldValueDTO[]`
  (same as `DocBeeDocumentDTO`), enabling `instanceof` checks and typed access via `getCustomFields()`.

- **`CustomFieldResource::findByParentType(string $parentType): array`** — returns all custom
  field definitions for a given entity type (e.g. `PARENT_TYPE_DOCBEE_DOCUMENT`).  Uses the
  plain `parentType=<value>` parameter — the standard `parentType-eq=` form is silently
  ignored by the Docbee API and returns all fields regardless.  Also requests
  `?fields=id,name,parentType,type` explicitly since the default list omits those.
  `findByName()` is now implemented on top of `findByParentType()` (server-side pre-filter,
  then name match client-side) instead of scanning all fields globally.
- **`CustomFieldResource` — typed constants and provisioning helpers**:
  - `PARENT_TYPE_DOCBEE_DOCUMENT`, `PARENT_TYPE_TICKET`, `PARENT_TYPE_CUSTOMER`,
    `PARENT_TYPE_CUSTOMER_CONTACT`, `PARENT_TYPE_CUSTOMER_LOCATION`, `PARENT_TYPE_MATERIAL_ITEM`
    (typed `string` constants for the `parentType` field).
  - `TYPE_SINGLELINE_TEXT`, `TYPE_MULTILINE_TEXT`, `TYPE_NUMBER_LONG`, `TYPE_NUMBER_DECIMAL`,
    `TYPE_BOOLEAN`, `TYPE_DATE`, `TYPE_SELECTION` (typed `string` constants for the `type` field).
  - `findByName(string $name, string $parentType): ?CustomFieldDTO` — finds an existing
    definition by name + parent type.  Requests `?fields=id,name,parentType,type` explicitly
    because the default list response omits `parentType` and `type`.
  - `ensureDefinition(string $name, string $parentType, string $type, ...): CustomFieldDTO` —
    idempotent find-or-create: returns the existing field when a matching name + parentType
    combination already exists, otherwise POSTs a new definition.
  - `ensureAssignedToDocument(int $fieldId): void` — idempotent merge-and-write for
    `GET/PUT /docBeeDocument/customFields` (assigns a field to Leistungen global settings).
  - `ensureAssignedToTicket(int $fieldId): void` — same pattern for
    `GET/PUT /ticket/customFields`.
- **`DocumentResource` — custom field value helpers**:
  - `getCustomFieldValues(int $docId): array<int, mixed>` — returns all stored values as a
    `fieldId => value` map.  Uses `?fields=customFields.id,customFields.value` (dot-notation
    is required — the plain `?fields=customFields` form returns only IDs without values).
  - `getCustomFieldValue(int $docId, int $fieldId): mixed` — returns the value for a single
    field, or null when not set.
  - `getCustomFieldIds(int $docId): array` — lightweight presence check: returns IDs of
    fields that have a non-null value (`?fields=customFields`).
  - `hasCustomFieldValue(int $docId, int $fieldId): bool` — convenience wrapper around
    `getCustomFieldIds()`.
  - `setCustomFieldValue(int $docId, int $fieldId, mixed $value): void` — sets a single
    custom field value via `PUT /docBeeDocument/{id}` with the correct nested payload.
  - `setCustomFieldValues(int $docId, array $valuesByFieldId): void` — sets multiple
    custom field values in a single request; no-op on an empty map.
- **`DocBeeDocumentDTO::fromArray()`** — fixed crash when `customFields` contains plain
  integers (the `?fields=customFields` response format).  The mapper now only constructs
  `CustomFieldValueDTO` objects when elements are arrays; integer-only arrays are ignored
  gracefully.
- **`DocBeeDocumentTaskResource` — task helpers**:
  - `updateDescription(int $taskId, string $description): DocBeeDocumentTaskDTO` —
    convenience method wrapping `update()` with a single-field payload.
  - `canBeDeleted(int $taskId): DocumentTaskDeletionCheckDTO` — performs three lightweight
    count queries (`workLog`, `planningTime`, `material`) and returns a value object
    indicating whether the task is safe to delete.
- **`DocumentTaskDeletionCheckDTO`** — new read-only value object returned by
  `DocBeeDocumentTaskResource::canBeDeleted()`.  Exposes `canDelete(): bool`,
  `getBlockers(): array` (human-readable strings), and individual count accessors
  `getWorkLogCount()`, `getPlanningTimeCount()`, `getMaterialCount()`.
- **`DocBeeDocumentTaskMaterialResource` — material helpers**:
  - `findByMaterialItemId(int $materialItemId): ?MaterialDTO` — scans the task's material
    list client-side and returns the first matching entry, or null.
  - `addOrIncrementByMaterialItemId(int $materialItemId, float $quantity): MaterialDTO` —
    idempotent read-before-write: increments the `amount` of an existing entry, or creates
    a new one if absent.
- **`TicketResource::iterateNonClosed(int $closedStatusId): Generator`** — memory-efficient
  generator that yields all tickets not matching the given status ID, auto-paginating via
  `cursor()` with a `ticketStatus-neq=` filter.
- **`CustomFieldDTO::toArray()`** now includes `parentType` and `type` so callers can use
  `$dto->toArray()` as a create payload without extra boilerplate.
- **`TicketResource::findByCustomerContact(int $contactId)`** — returns all tickets linked to a
  specific customer contact.  Uses the plain `customerContact=<id>` parameter (the `-eq` operator
  form is silently ignored by the Docbee API for relation filters on this endpoint).
- **`TicketResource::findByCustomerLocation(int $locationId)`** — returns all tickets linked to a
  specific customer location.  Uses the plain `customerLocation=<id>` parameter for the same
  reason as `findByCustomerContact()`.
- **`CustomerResource::findOneByCustomerId(string $customerId): ?CustomerDTO`** — null-safe variant
  of `findByCustomerId()`.  Returns `null` instead of throwing `NotFoundException`, designed for
  existence checks during ERP imports (e.g. *"does this weclapp customer already exist in Docbee?"*).
- **`AbstractResource::search(string $query)`** — new method available on **every** resource
  class.  Sets the Docbee `search` parameter and returns all matching records via `listAll()`.
  The Docbee API supports `search` on 70 endpoints; what is searched depends on the resource
  (name, number, email, reference number, …).  Notable use cases:
  - `/customer` → matches customer name **and** customer number (UI display number)
  - `/customerContact` → matches contact name, email, phone, …
  - `/customerLocation` → matches location name and address fields
  - `/ticket` → matches title, description, reference number, …
  - `/user` → matches username and email
  - `/object` → matches object name and serial/scan codes
- **`QueryBuilder::search(string $query)`** — backing method used by `AbstractResource::search()`.
  Can also be combined with other filters: `QueryBuilder::new()->search('…')->filterEq(…)`.
- **`CustomerResource::search()`** now inherits from `AbstractResource`; the customer number
  workflow is documented in the class DocBlock.  Example:
  ```php
  $customers  = $client->customers()->search('12355');   // by customer number
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
- **`CustomerResource::findByCustomerId()`** — the filter was silently broken: `filterEq('customerId', …)`
  appends a `-eq` suffix that the Docbee API ignores even for scalar fields on this endpoint,
  causing the first customer in the list to be returned regardless of the value.  Fixed by
  switching to `QueryBuilder::param('customerId', …)`.  A regression-guard unit test
  (`testFindByCustomerIdDoesNotUseEqSuffix`) prevents the broken form from reappearing.
- **`CustomerLocationResource::findByCustomer()`** — address fields (`street`, `city`, `zipcode`)
  were always empty because the Docbee API omits them from list responses unless `fields=` is
  specified.  The method now requests these fields explicitly via `QueryBuilder::fields()`.
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
