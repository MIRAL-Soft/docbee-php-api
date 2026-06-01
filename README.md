# Docbee PHP API Client

A professional PHP client library for the [Docbee](https://www.docbee.com) REST API.

Provides typed access to all Docbee resources — tickets, customers, documents, webhooks and more — without having to deal with raw HTTP requests or JSON parsing.

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP         | ≥ 8.3   |
| Guzzle      | ^7.0    |
| PSR Log     | ^2.0 or ^3.0 |

---

## Installation

```bash
composer require miralsoft/docbee-api
```

---

## Quick Start

```php
use miralsoft\docbee\api\Client\DocbeeClient;
use miralsoft\docbee\api\Config\DocbeeConfig;

$client = new DocbeeClient(new DocbeeConfig(
    tenant: 'mycompany',   // your Docbee subdomain
    token:  'your-api-token',
));

// List tickets with a specific status
$tickets = $client->tickets()->findByTicketStatus(1);

// Create a new ticket
$ticket = $client->tickets()->create([
    'description' => 'Printer offline',
    'customer'    => 42,
    'priority'    => 1,
]);

echo $ticket->getId();            // e.g. 1234
echo $ticket->getDescription();   // "Printer offline"
```

---

## Configuration

### Direct construction

```php
$config = new DocbeeConfig(
    tenant:         'mycompany',
    token:          'your-api-token',
    timeout:        30,   // request timeout in seconds (default: 30)
    connectTimeout: 10,   // connection timeout in seconds (default: 10)
    maxRetries:     3,    // max retries on 429/5xx (default: 3)
);
```

### From environment variables

```bash
export DOCBEE_TENANT=mycompany
export DOCBEE_TOKEN=your-api-token
```

```php
$config = DocbeeConfig::fromEnv();
$client = new DocbeeClient($config);
```

### From an array (e.g. config file)

```php
$config = DocbeeConfig::fromArray([
    'tenant' => 'mycompany',
    'token'  => 'your-api-token',
]);
```

---

## Resources

### Top-level resources

Access via the `DocbeeClient` instance. All resources support `find()`, `list()`, `listAll()`, `cursor()`, `count()`, `create()`, `update()`, `delete()`, `findModifiedSince()`, and `findCreatedSince()` unless the API endpoint is read-only.

| Method | Description |
|--------|-------------|
| `$client->agreements()` | Service agreements |
| `$client->agreementCategories()` | Agreement categories |
| `$client->agreementTemplates()` | Agreement templates |
| `$client->away()` | Away / out-of-office entries |
| `$client->awayReasons()` | Away reason types |
| `$client->companyData()` | Company master data |
| `$client->confidentialTags()` | Confidential tags |
| `$client->contingents()` | Service contingents |
| `$client->costEstimations()` | Cost estimations |
| `$client->costEstimationTemplates()` | Cost estimation templates |
| `$client->customColors()` | Custom UI colours |
| `$client->customFields()` | Custom field definitions |
| `$client->customers()` | Customers |
| `$client->customerContacts()` | Customer contacts |
| `$client->customerLocations()` | Customer locations |
| `$client->customerObjects()` | Customer objects / assets |
| `$client->customerProfiles()` | Customer profiles |
| `$client->customerStatuses()` | Customer status types |
| `$client->customerUsers()` | Customer portal users |
| `$client->dailyClosingConfigs()` | Daily closing configurations |
| `$client->dashboards()` | Dashboards |
| `$client->departments()` | Departments |
| `$client->departmentProfiles()` | Department profiles |
| `$client->documentRecurrences()` | Document recurrence rules |
| `$client->docBeeScripts()` | Automation scripts |
| `$client->documents()` | Documents / protocols |
| `$client->documentTasks()` | Document tasks |
| `$client->documentTemplates()` | Document templates |
| `$client->dueDateColors()` | Due-date colour rules |
| `$client->envVariables()` | Environment variables |
| `$client->errorLogs()` | Error logs |
| `$client->exportProfiles()` | Export profiles |
| `$client->invoices()` | Invoices |
| `$client->materialItems()` | Material items / products |
| `$client->messages()` | Send e-mails (`sendMail()`) |
| `$client->messageTemplates()` | Message templates |
| `$client->notes()` | Notes |
| `$client->notifications()` | Notifications |
| `$client->objectCategories()` | Object categories |
| `$client->observerCategories()` | Observer categories |
| `$client->observerTypes()` | Observer types |
| `$client->observerUsers()` | Observer (portal) users |
| `$client->paymentProfiles()` | Payment profiles |
| `$client->pdfLayouts()` | PDF layout configurations |
| `$client->permissionGroups()` | Permission groups |
| `$client->presetProfiles()` | Preset profiles |
| `$client->priorities()` | Priority levels |
| `$client->protocols()` | Protocols |
| `$client->protocolTemplates()` | Protocol templates |
| `$client->queues()` | Ticket queues |
| `$client->requestTypes()` | Request types |
| `$client->ruleEngineActions()` | Rule engine actions |
| `$client->selectionCategories()` | Selection / dropdown categories |
| `$client->serviceProviders()` | Service providers |
| `$client->serviceProviderUsers()` | Service provider users |
| `$client->serviceTypes()` | Service types |
| `$client->serviceTypeProfiles()` | Service type profiles |
| `$client->skills()` | Technician skills |
| `$client->slaProfiles()` | SLA profiles |
| `$client->tableConfigStorages()` | Saved table view configurations |
| `$client->tags()` | Tags |
| `$client->taskTemplates()` | Task templates |
| `$client->tickets()` | Support tickets |
| `$client->ticketBoards()` | Kanban-style ticket boards |
| `$client->ticketBoardProfiles()` | Ticket board profiles |
| `$client->ticketCategories()` | Ticket categories |
| `$client->ticketLinkTypes()` | Ticket link type definitions |
| `$client->ticketMailParserConfigs()` | Ticket mail parser rules |
| `$client->ticketRecurrences()` | Ticket recurrence rules |
| `$client->ticketStatuses()` | Ticket status types |
| `$client->ticketTemplates()` | Ticket templates |
| `$client->timers()` | Running timers |
| `$client->timeRecords()` | Time records |
| `$client->travelTypes()` | Travel type definitions |
| `$client->userActivities()` | User activity logs |
| `$client->userProfiles()` | User profiles |
| `$client->users()` | System users |
| `$client->webhooks()` | Webhook subscriptions |
| `$client->workPipes()` | Work pipes |

### Sub-resources

Sub-resources are scoped to a parent record. Each call returns a fresh resource instance bound to the given parent ID.

| Factory method | Endpoint |
|----------------|----------|
| `$client->agreementComponents(int $agreementId)` | `v1/agreement/{id}/component` |
| `$client->agreementPeriods(int $agreementId)` | `v1/agreement/{id}/period` |
| `$client->agreementInvoices(int $agreementId)` | `v1/agreement/{id}/invoice` |
| `$client->agreementComponentTemplates(int $agreementTemplateId)` | `v1/agreementTemplate/{id}/componentTemplate` |
| `$client->contingentElements(int $contingentId)` | `v1/contingent/{id}/element` |
| `$client->contingentItems(int $contingentId)` | `v1/contingent/{id}/item` |
| `$client->contingentItemRecurrences(int $contingentId)` | `v1/contingent/{id}/itemRecurrence` |
| `$client->costEstimationTasks(int $costEstimationId)` | `v1/costEstimation/{id}/task` |
| `$client->costEstimationTaskTemplates(int $templateId)` | `v1/costEstimationTemplate/{id}/task` |
| `$client->dashboardWidgets(int $dashboardId)` | `v1/dashboard/{id}/widget` |
| `$client->documentConflicts(int $documentId)` | `v1/docBeeDocument/{id}/conflict` |
| `$client->documentMessages(int $documentId)` | `v1/docBeeDocument/{id}/message` |
| `$client->docBeeScriptParameters(int $scriptId)` | `v1/docBeeScript/{id}/param` |
| `$client->paymentProfileMappings(int $paymentProfileId)` | `v1/paymentProfile/{id}/mapping` |
| `$client->presetValues(int $presetProfileId)` | `v1/presetProfile/{id}/value` |
| `$client->protocolEntries(int $protocolId)` | `v1/protocol/{id}/protocolEntry` |
| `$client->protocolGroupData(int $protocolId)` | `v1/protocol/{id}/groupData` |
| `$client->protocolDocumentTemplates(int $protocolTemplateId)` | `v1/protocolTemplate/{id}/documentTemplate` |
| `$client->ruleEngineConditions(int $actionId)` | `v1/ruleEngineAction/{id}/condition` |
| `$client->ruleEngineReactions(int $actionId)` | `v1/ruleEngineAction/{id}/reaction` |
| `$client->ruleEngineSettings(int $actionId)` | `v1/ruleEngineAction/{id}/setting` |
| `$client->selectionValues(int $selectionCategoryId)` | `v1/selectionCategory/{id}/selectionValue` |
| `$client->slaProfileSpecializations(int $slaProfileId)` | `v1/slaProfile/{id}/specialization` |
| `$client->slaProfileWorkingHours(int $slaProfileId)` | `v1/slaProfile/{id}/workingHour` |
| `$client->ticketBoardColumns(int $boardId)` | `v1/ticketBoard/{id}/column` |
| `$client->ticketBoardFields(int $boardId)` | `v1/ticketBoard/{id}/field` |
| `$client->ticketBoardFilters(int $boardId)` | `v1/ticketBoard/{id}/filter` |
| `$client->ticketLinks(int $ticketId)` | `v1/ticket/{id}/link` |
| `$client->ticketMessages(int $ticketId)` | `v1/ticket/{id}/message` |
| `$client->documentTravelLogs(int $documentId)` | `v1/docBeeDocument/{id}/travelLog` |
| `$client->documentTemplateTaskTemplates(int $documentId)` | `v1/docBeeDocumentTemplate/{id}/taskTemplate` |
| `$client->documentTemplateTaskTemplateMaterials(int $documentId, int $taskTemplateId)` | `v1/docBeeDocumentTemplate/{id}/taskTemplate/{taskTemplateId}/materialTemplate` |
| `$client->documentTemplateTaskTemplatePlanningTimes(int $documentId, int $taskTemplateId)` | `v1/docBeeDocumentTemplate/{id}/taskTemplate/{taskTemplateId}/planningTimeTemplate` |
| `$client->documentTemplateTaskTemplateWorkLogs(int $documentId, int $taskTemplateId)` | `v1/docBeeDocumentTemplate/{id}/taskTemplate/{taskTemplateId}/workLogTemplate` |
| `$client->documentTemplateTravelLogTemplates(int $documentId)` | `v1/docBeeDocumentTemplate/{id}/travelLogTemplate` |
| `$client->docBeeDocumentTasks(int $documentId)` | `v1/docBeeDocument/{id}/task` |
| `$client->docBeeDocumentTaskMaterials(int $documentId, int $taskId)` | `v1/docBeeDocument/{id}/task/{taskId}/material` |
| `$client->docBeeDocumentTaskPlanningTimes(int $documentId, int $taskId)` | `v1/docBeeDocument/{id}/task/{taskId}/planningTime` |
| `$client->docBeeDocumentTaskWorkLogs(int $documentId, int $taskId)` | `v1/docBeeDocument/{id}/task/{taskId}/workLog` |
| `$client->protocolGroupEntries(int $protocolId, int $groupId)` | `v1/protocol/{id}/protocolGroupEntries/{groupId}` |
| `$client->protocolGroup(int $protocolId)` | `v1/protocol/{id}/group/{templateGroupId}/…` |
| `$client->protocolPlanningTimes(int $protocolId)` | `v1/protocol/{id}/planningTime` |
| `$client->protocolTemplateEntryElements(int $entryId)` | `v1/protocolTemplateEntry/{id}/element` |

**Example:**

```php
// List all messages for ticket #42
$messages = $client->ticketMessages(42)->listAll();

// List all components of agreement #7
$components = $client->agreementComponents(7)->list();

// Paginate through rule engine conditions for action #3
foreach ($client->ruleEngineConditions(3)->cursor() as $condition) {
    process($condition);
}
```

---

## Common Operations

### Find by ID

```php
$ticket   = $client->tickets()->find(42);
$customer = $client->customers()->find(7);

// Restrict the returned fields to reduce payload size
$ticket = $client->tickets()->find(42, fields: ['id', 'ticketNumber', 'customer']);
```

### List with filters

```php
use miralsoft\docbee\api\Query\QueryBuilder;

$tickets = $client->tickets()->list(
    QueryBuilder::new()
        ->filterEq('customer', 42)
        ->filterEq('priority', 2)
        ->sort('createdAt', 'desc')
        ->limit(20)
);
```

### Count records

```php
$total = $client->tickets()->count(
    QueryBuilder::new()->filterEq('status', 1)
);
```

### Paginate all records (memory-efficient)

```php
foreach ($client->tickets()->cursor() as $ticket) {
    // processes one ticket at a time — no full list loaded into memory
    process($ticket);
}
```

### Load all records at once

```php
$all = $client->customers()->listAll();
```

---

## Delta Synchronisation

Use `findModifiedSince()` and `findCreatedSince()` to efficiently synchronise only changed records:

```php
$since = new DateTimeImmutable('-5 minutes');

// Tickets changed in the last 5 minutes
$changed = $client->tickets()->findModifiedSince($since);

// Customers created today
$new = $client->customers()->findCreatedSince(new DateTimeImmutable('today'));
```

This is the recommended approach for keeping an external system (e.g. ERP, CRM) in sync with Docbee.

### Fully-populated DTOs in list results (DocumentResource & InvoiceResource)

**`DocumentResource`** and **`InvoiceResource`** automatically request a comprehensive field
set in every list/cursor/findModifiedSince call — so the returned DTOs are as fully populated
as those from `find($id)`, without any extra work on the caller side:

```php
// All fields — including modified, ticket, approved, billable, invoiceNumber, etc. —
// are populated automatically. No QueryBuilder::fields() required.
foreach ($client->documents()->findModifiedSince($since) as $doc) {
    if ($doc->getModified() > $lastSync) {        // ✓ never null
        processDocument(
            id:            $doc->getId(),
            ticketId:      $doc->getTicket(),     // ✓ never null
            approved:      $doc->getApproved(),   // ✓ never null
            invoiceNumber: $doc->getInvoiceNumber(),
        );
    }
}
```

> **Background:** The Docbee API list endpoint returns a narrower default field set than the
> single-record endpoint.  Without an explicit `?fields=` parameter, `modified`, `ticket`,
> `approved`, `billable`, `invoiceNumber` etc. are absent from list responses and silently
> return `null`.  `DocumentResource` and `InvoiceResource` work around this automatically
> via an internal `$defaultListFields` property.  Other resources still use the API's
> narrow default — pass `QueryBuilder::new()->fields([...])` to request additional fields.

To request a **slim projection** instead (e.g. for performance-sensitive scans over many
records), pass an explicit field selector — this always overrides the default:

```php
// Only fetch id and modified — skips all other fields for speed
$docs = $client->documents()->findModifiedSince(
    $since,
    QueryBuilder::new()->fields(['id', 'modified']),
);
```

---

## Querying

The `QueryBuilder` provides a fluent, type-safe interface for building API queries:

```php
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Query\FilterOperator;

$query = QueryBuilder::new()
    ->filterEq('status', 1)                 // status == 1
    ->filterGt('priority', 2)               // priority > 2
    ->filterIlike('title', '%printer%')     // title LIKE '%printer%'
    ->filterIn('customer', [1, 2, 3])       // customer IN (1, 2, 3)
    ->modifiedSince(new DateTimeImmutable('-1 hour'))
    ->sort('createdAt', 'desc')
    ->limit(50)
    ->offset(0);

$tickets = $client->tickets()->list($query);
```

### Available filter operators (`FilterOperator`)

| Constant             | Operator              |
|----------------------|-----------------------|
| `FilterOperator::EQ`    | equals (==)        |
| `FilterOperator::NEQ`   | not equals (!=)    |
| `FilterOperator::ILIKE` | case-insensitive LIKE |
| `FilterOperator::GT`    | greater than       |
| `FilterOperator::GTE`   | greater than or equal |
| `FilterOperator::LT`    | less than          |
| `FilterOperator::LTE`   | less than or equal |
| `FilterOperator::IN`    | IN list            |

### Field Selection

Use `QueryBuilder::fields([...])` to restrict which fields the API returns.  This reduces
payload size and is the primary tool for working around the Docbee API's uneven default
field coverage across endpoints:

```php
// Only fetch the fields you actually need
$docs = $client->documents()->list(
    QueryBuilder::new()->fields(['id', 'modified', 'ticket', 'approved', 'billable'])
);
```

#### Default field sets — list vs. find

The Docbee API returns **different default field sets** for list endpoints (`GET /resource`)
and single-record endpoints (`GET /resource/{id}`).  Many fields — such as `modified`,
`ticket`, `approved`, `billable`, `invoiceNumber` — are absent from list responses unless
explicitly requested.  Without them, getter methods silently return `null`.

**`DocumentResource`** and **`InvoiceResource`** address this automatically: they define a
`$defaultListFields` set that mirrors their `find()` field set.  Every call to `list()`,
`cursor()`, `listAll()`, `findModifiedSince()`, `findCreatedSince()`, or `search()` includes
these fields automatically — no extra configuration needed.

Other resources (tickets, customers, etc.) still use the API's narrow default.  For those,
request the fields you need explicitly:

```php
// ServiceType.number is absent from the default list response
$types = $client->serviceTypes()->list(
    QueryBuilder::new()->fields(['id', 'name', 'number', 'deactivated'])
);

// For delta-sync on tickets, request the fields your sync logic depends on
$changed = $client->tickets()->findModifiedSince(
    new DateTimeImmutable('-1 hour'),
    QueryBuilder::new()->fields(['id', 'modified', 'ticketStatus', 'erpReferenceNumber']),
);
```

> **Rule of thumb:** if a getter returns `null` unexpectedly on a list result, add that
> field name to a `QueryBuilder::fields([...])` call — or check whether the resource
> already defines `$defaultListFields` (see class docblock).

### Performance — Page Size (`pageSize()`)

`cursor()` fetches records in pages.  The **default page size is 100** (raised from 50).
For large datasets, request fewer round trips with `QueryBuilder::pageSize(N)`:

```php
// Document cursor: 500/page → 8 requests instead of 80 for 4 000 docs
// (live-measured: 4.4 s vs 8.4 s, pcs tenant 2026-05-23)
foreach ($client->documents()->cursor(QueryBuilder::new()->pageSize(500)) as $doc) { … }

// Invoice batch lookup: findByDocuments() in one scan instead of N × findByDocument()
// (live-measured: 10.8 s vs 43.1 s for 3 documents, pcs tenant 2026-05-23)
$map = $client->invoices()->findByDocuments([$docId1, $docId2, $docId3]);
```

`pageSize()` is separate from `limit()`:
- `limit(N)` — max records returned by a single `list()` call; capped at 100 by `MAX_LIMIT`.
- `pageSize(N)` — chunk size used by `cursor()` for pagination; **not** capped (endpoint-specific).

> **⚠ Endpoint-specific server limits (live-verified 2026-05-23):**
> - `/invoice` — silently returns **0 items** for `limit > 100`. Keep `pageSize ≤ 100` for invoice scans.
> - `/docBeeDocument` — accepts at least 500 per page. `DocumentResource` uses 500 by default.
>
> `DocumentResource` automatically sets `$defaultPageSize = 500`.
> `InvoiceResource` keeps the safe default of 100.

### Benchmarks & realistic expectations

All figures below were measured live against the `pcs` tenant (≈ 3 829 invoice records,
≈ 4 000 documents, PHP 8.3, 2026-05-23). Your numbers scale with **tenant size**, not with
the size of the result you actually want — see the note on the invoice-scan floor below.

| Operation | Before | After | Speed-up |
|---|---|---|---|
| Invoice cursor (full, 3 829 records) | 22.5 s | **9.6 s** | 2.3× |
| Document cursor (full, 4 000 records) | 8.4 s | **4.4 s** | 1.9× |
| `findByDocuments([3 ids])` | 43.1 s (3 scans) | **10.8 s** (1 scan) | 4.0× |
| `findByTicket($id)` (server-side filter) | — | **0.7 s** | n/a |

**Why some operations have a hard time floor.** The Docbee API offers **no server-side
filter** for `invoice.docBeeDocument` or for document custom-field values (both confirmed
silently ignored — see notes below). Any lookup that depends on those must scan the whole
collection client-side. For invoices that means **~10 s minimum** on a 3 800-record tenant,
*no matter how few documents you are looking up* — the cost is the scan, not the match.

This is why a `document → invoice` mapping cannot be brought "under 5 s" through the library
alone. The two ways to go faster are both **consumer-side**:

1. **Look up by ticket instead of by document where possible.** `findByTicket($ticketId)`
   uses the server-side `ticketIds` filter and returns in well under a second regardless of
   tenant size. If your sync key can be resolved to a ticket, prefer this path.

2. **Cache the `docId → invoiceId` map.** Build it once per run with a single
   `findByDocuments()` / cursor scan and reuse it for all subsequent lookups in that run,
   rather than scanning again per document.

```php
// Build once …
$invoiceByDoc = $client->invoices()->findByDocuments($allDocIdsThisRun); // one ~10 s scan

// … then reuse for every document — O(1), no further API calls
foreach ($docsToBill as $doc) {
    $invoice = $invoiceByDoc[$doc->getId()] ?? null;
    if ($invoice !== null) {
        $client->invoices()->update($invoice->getId(), ['invoiceNumber' => $doc->getErpRef()]);
    }
}
```

> **Server-side filters that do NOT exist (confirmed silently ignored, not errors):**
> - `/invoice?docBeeDocument-eq=<id>` (and `-in`, `docBeeDocumentId-eq`, plain forms) →
>   returns the full invoice set; the filter has no effect.
> - `/docBeeDocument?customFields.<id>-eq=<value>` → returns all documents; no effect.
>
> When a future Docbee API version adds these filters, the corresponding `findBy…()` methods
> can drop the client-side scan and the floor disappears. Until then the scan is unavoidable.

---

## Resource-specific Methods

### Customers

> **Important — customer number vs. internal ID:** The number displayed next to the customer
> name in the Docbee UI (e.g. *"Testfirma 12355"*) is the **customer number**, a sequential
> display counter.  It is **not** the internal database ID used by the REST API.  Use
> `search()` to resolve a customer number to the internal ID.

```php
// Search by name OR customer number (the number shown in the Docbee UI header)
$customers  = $client->customers()->search('12355');     // finds by customer number
$customers  = $client->customers()->search('Testfirma'); // finds by name
$internalId = $customers[0]->getId();                    // e.g. 241957

// Full workflow: customer number → internal ID → contacts and locations
$customers  = $client->customers()->search('12355');
$internalId = $customers[0]->getId();
$contacts   = $client->customerContacts()->findByCustomer($internalId);
$locations  = $client->customerLocations()->findByCustomer($internalId);

// Find by ERP customer ID — throws NotFoundException if absent
$customer = $client->customers()->findByCustomerId('K-1001');

// Existence check during ERP import — returns null instead of throwing
$customer = $client->customers()->findOneByCustomerId('K-1001');
if ($customer !== null) {
    $internalId = $customer->getId(); // already exists in Docbee
}

$active = $client->customers()->findByCustomerStatus(1);

// Filter contacts or locations by a specific customer location
$contacts = $client->customerContacts()->findByCustomerLocation(3);
```

> **Note:** The Docbee API requires plain parameters (without `-eq` suffix) for several
> relation and scalar filters.  Always use the dedicated `findBy…()` methods rather than
> `list(QueryBuilder::new()->filterEq(…))` for the following:
> - `customerContacts` / `customerLocations`: `customer=<id>` (not `customer-eq=<id>`)
> - `customers`: `customerId=<id>` (not `customerId-eq=<id>`)
> - `tickets`: `customerContact=<id>` (not `customerContact-eq=<id>`)


### Tickets

```php
$tickets = $client->tickets()->findByCustomer(42);
$tickets = $client->tickets()->findByCustomer(42, ticketStatusId: 1);
$tickets = $client->tickets()->findByCustomerContact(7);   // all tickets for a contact
$tickets = $client->tickets()->findByCustomerLocation(3);  // all tickets for a location
$tickets = $client->tickets()->findByTicketStatus(1);
$tickets = $client->tickets()->findByReferenceNumber('REF-2024-001');
$tickets = $client->tickets()->findByOwner(5);

// Find tickets by ERP reference number — uses server-side search + exact-match filter.
// Fast even on large tenants (2–4 s for 55 000 tickets vs 5+ min with a naive filter).
$tickets = $client->tickets()->findByErpReferenceNumber('WO-12345');

// Memory-efficient iteration over all non-closed tickets (auto-paginates)
foreach ($client->tickets()->iterateNonClosed($closedStatusId) as $ticket) {
    sync($ticket);
}
```

> **Docbee API note:** `erpReferenceNumber-eq=` is silently ignored by the Docbee server
> for tickets — the parameter is absent from the OpenAPI spec.  `findByErpReferenceNumber()`
> uses `search=<value>` (the only server-side mechanism) plus client-side exact-match
> filtering.  Verified live: 2 hits returned in 2.4 s on a 55 070-ticket tenant.

### Custom Fields

Custom fields in Docbee require a three-step provisioning workflow: define the field,
assign it to an entity type, then set values on individual records.

```php
use miralsoft\docbee\api\Resource\CustomFieldResource;

// List all fields by entity type
$leistungsFelder = $client->customFields()->findByParentType(
    CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT
);
$vorgangsFelder = $client->customFields()->findByParentType(
    CustomFieldResource::PARENT_TYPE_TICKET
);

$cf = $client->customFields()->ensureDefinition(
    name:       'weclappOrderItemId',     // label shown in the Docbee UI
    parentType: CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT,
    type:       CustomFieldResource::TYPE_SINGLELINE_TEXT,
);

// Assign to Leistungen global settings (idempotent, merge-safe)
$client->customFields()->ensureAssignedToDocument($cf->getId());

// Set a value on a document
$client->documents()->setCustomFieldValue($docId, $cf->getId(), 'WO-12345');

// Set multiple values at once
$client->documents()->setCustomFieldValues($docId, [
    $cf->getId()    => 'WO-12345',
    $otherCf->getId() => 'extra-value',
]);

// Read all stored values as a fieldId => value map
$values = $client->documents()->getCustomFieldValues($docId);
// e.g. [102 => 123, 104 => 'WO-12345']
$value = $client->documents()->getCustomFieldValue($docId, $cf->getId());

// Lightweight presence check (no values, just which fields are set)
$client->documents()->hasCustomFieldValue($docId, $cf->getId());  // true / false
$client->documents()->getCustomFieldIds($docId);                   // [101, 102, ...]
```

Available parent-type and field-type constants:

| Parent type | Constant |
|---|---|
| Leistung (service document) | `CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT` |
| Vorgang (ticket / process) | `CustomFieldResource::PARENT_TYPE_TICKET` |
| Customer | `CustomFieldResource::PARENT_TYPE_CUSTOMER` |
| Customer contact | `CustomFieldResource::PARENT_TYPE_CUSTOMER_CONTACT` |
| Customer location | `CustomFieldResource::PARENT_TYPE_CUSTOMER_LOCATION` |
| Material item | `CustomFieldResource::PARENT_TYPE_MATERIAL_ITEM` |

| Field type | Constant |
|---|---|
| Single-line text | `CustomFieldResource::TYPE_SINGLELINE_TEXT` |
| Multi-line text | `CustomFieldResource::TYPE_MULTILINE_TEXT` |
| Integer | `CustomFieldResource::TYPE_NUMBER_LONG` |
| Decimal | `CustomFieldResource::TYPE_NUMBER_DECIMAL` |
| Boolean | `CustomFieldResource::TYPE_BOOLEAN` |
| Date | `CustomFieldResource::TYPE_DATE` |
| Selection | `CustomFieldResource::TYPE_SELECTION` |

> **Note:** Custom field values are readable via `getCustomFieldValues()` which uses the
> `?fields=customFields.id,customFields.value` dot-notation.  The plain
> `?fields=customFields` form returns only IDs (used by `getCustomFieldIds()` for lightweight
> presence checks).

> **Document Templates:** `documents()` and `documentTemplates()` share the same CF
> definitions and the same assignment list.  `ensureAssignedToDocument()` covers both —
> see the [Document Templates](#document-templates) section for the full CF workflow.

### Documents — Performance-Optimised Lookups

```php
// Find all documents linked to a specific ticket (uses the correct `ticketIds` param).
// ticket-eq= is silently ignored by the Docbee API and must NOT be used.
// Fast: server-side filter, typically < 1 s regardless of tenant size.
$docs = $client->documents()->findByTicket($ticketId);

// Find documents by ERP reference number (scoped to one customer, fields-only scan).
// No server-side filter exists; this performs a paginated scan with minimal payload.
$docs = $client->documents()->findByErpReferenceNumber($customerId, 'WO-12345');

// Find a document by a custom field value — N+1 eliminated.
// Fetches all docs for the customer with custom fields inline (one HTTP call per page).
// No server-side custom-field filter exists (confirmed — silently ignored).
$docs = $client->documents()->findByCustomFieldValue(
    customerId: 205023,
    fieldId:    104,         // e.g. weclappOrderItemId custom field
    value:      'WO-12345',
);
// With $defaultPageSize = 500: ~8 requests for 4 000 docs (4.4 s) vs 80 requests (8.4 s).

// Generator-based alternative for memory-efficient processing of large datasets:
foreach ($client->documents()->cursorWithCustomFields($customerId) as $doc) {
    $fields = $doc->getCustomFields() ?? [];  // list<CustomFieldValueDTO>
    foreach ($fields as $cf) {
        echo $cf->getId() . ' => ' . $cf->getValue() . "\n";
    }
}
```

> **Docbee API filter limitations (live-verified):**
> - `ticketIds=<id>` works server-side; `ticket-eq=<id>` is silently ignored (returns all docs).
> - `erpReferenceNumber-eq=` is ignored; document full-text search does not cover it — client-side scan only.
> - `customFields.<id>-eq=<value>` is silently ignored — client-side scan only.
> - `/findByExternalId/{val}` does **not** match `externalReferenceNumber`, `erpReferenceNumber`, or
>   `referenceNumber` — it searches an internal integration field.  Use custom fields as a unique key instead.

### Billing / Invoices

```php
// ── Finding the Invoice record for a document ─────────────────────────────────
// Each approved+billable document has exactly one Invoice record.
// findByDocument() performs a full cursor scan (no server-side filter available).
$invoice = $client->invoices()->findByDocument($docId);

// ✓ Batch lookup: ONE scan for multiple documents (4× faster than calling findByDocument() N times)
// live-measured: 10.8 s for all 3 docs vs 43.1 s with 3 individual calls (pcs tenant)
$map = $client->invoices()->findByDocuments([$docId1, $docId2, $docId3]);
// $map is array<int, InvoiceDTO> keyed by document ID

// ── Setting the billing number (Abrechnungsnummer) ───────────────────────────
// invoices()->update() sets invoiceNumber AND transitions status OPEN → INVOICED.
$client->invoices()->update($invoice->getId(), ['invoiceNumber' => 'RE-2024-001']);

// ── Exporting billing PDFs ────────────────────────────────────────────────────
$pdf = $client->invoices()->exportOverviewPdfByIds($pdfLayoutId, [$invoice->getId()]);
file_put_contents('sammelreport.pdf', $pdf);

// ── Approved & billable documents ────────────────────────────────────────────
$docs = $client->documents()->findApprovedBillable($customerId);
$ids  = array_map(fn($d) => $d->getId(), $docs);
$csv  = $client->documents()->exportByIds($exportProfileId, $ids);
```

> **Performance notes for invoice scans:**
> - The `/invoice` endpoint silently returns **0 items** for `limit > 100` (live-verified bug).
>   `InvoiceResource` enforces `$defaultPageSize = 100` automatically.
> - No server-side filter for `docBeeDocument` exists — all variants (`docBeeDocument-eq`,
>   `docBeeDocument-in`, `docBeeDocumentId-eq`) are silently ignored; the full 3 800+ invoice
>   set is returned regardless.  Full cursor scan is unavoidable.
> - Prefer `findByDocuments(array $docIds)` over multiple `findByDocument()` calls.

### Material Items

```php
// Find by number — O(n) full-catalogue scan (no server-side filter available).
// For production use with large catalogues, cache the result or build a number→id
// map at startup from a single listAll() call.
$item = $client->materialItems()->findByNumber('SW-1234');

// List only active (non-deactivated) items
$items = $client->materialItems()->findActive();
```

> **Note:** `GET /materialItem` supports only `deactivated`, `limit`, `offset`, `fields`,
> `changedSince`, `sortings`, and `tableSortings` — no `number`, `name`, or `search` filter
> exists (confirmed against OpenAPI spec 2025.3.0).

### Customer Contacts

```php
$contacts = $client->customerContacts()->findByCustomer($customerId);
$contacts = $client->customerContacts()->findByCustomerLocation($locationId);
$contacts = $client->customerContacts()->findByEmail('user@example.com');

// Create with all name fields
$client->customerContacts()->create([
    'customer'   => $customerId,
    'name'       => 'Mustermann, Erika',  // display name — set explicitly
    'firstName'  => 'Erika',
    'lastName'   => 'Mustermann',
    'email'      => 'erika@example.com',
    'website'    => 'https://example.com',
]);

// Read firstName/lastName/website — must request explicitly (absent from default response)
$contact = $client->customerContacts()->find($id, fields: [
    'id', 'name', 'firstName', 'lastName', 'email', 'website',
]);
echo $contact->getFirstName();  // 'Erika'
echo $contact->getLastName();   // 'Mustermann'
echo $contact->getWebsite();    // 'https://example.com'
```

> **`name` vs. `firstName` / `lastName`:** These three fields are fully independent —
> Docbee does **not** auto-derive `name` from `firstName`/`lastName`, and updating
> the individual components does not change `name`.  Set all three explicitly.
> Confirmed by live roundtrip test.

> **Fields absent from default response:** `firstName`, `lastName`, and `website`
> are not included unless requested via `fields=`.  `name`, `email`, `customer`,
> and `id` are always present.

### Document Templates

```php
// Find by exact name (cursor scan — name-eq= is silently ignored by the Docbee API)
$tpl = $client->documentTemplates()->findByName('Wartungsprotokoll');

// Clone a template to create a new document
$client->documentTemplates()->clone($templateId);
```

#### Custom Fields on Document Templates

Document templates share CF definitions with regular documents — `parentType` is always
`PARENT_TYPE_DOCBEE_DOCUMENT` for both.  **`ensureAssignedToDocument()` is sufficient for
templates as well** — calling `ensureAssignedToDocumentTemplate()` is equivalent (it is an
alias).  Skipping the assignment call causes `setCustomFieldValue()` to return HTTP 200
silently without persisting the value — no error is raised.

```php
// 1. Define (idempotent)
$cf = $client->customFields()->ensureDefinition(
    name:       'weclappOrderItemId',
    parentType: CustomFieldResource::PARENT_TYPE_DOCBEE_DOCUMENT,   // always DOCBEE_DOCUMENT
    type:       CustomFieldResource::TYPE_SINGLELINE_TEXT,
);

// 2. Assign (do this once — covers both documents AND templates)
$client->customFields()->ensureAssignedToDocument($cf->getId());

// 3. Set / read on a specific template
$client->documentTemplates()->setCustomFieldValue($templateId, $cf->getId(), 'WO-12345');
$client->documentTemplates()->setCustomFieldValues($templateId, [
    $cf->getId() => 'WO-12345',
]);

$values = $client->documentTemplates()->getCustomFieldValues($templateId);
// e.g. [107 => 'WO-12345']
$value  = $client->documentTemplates()->getCustomFieldValue($templateId, $cf->getId());

$client->documentTemplates()->hasCustomFieldValue($templateId, $cf->getId()); // true/false
$client->documentTemplates()->getCustomFieldIds($templateId);                  // [107, ...]

// 4. Find templates by CF value (N+1-free cursor scan, inline CF data per page)
$templates = $client->documentTemplates()->findByCustomFieldValue(
    customerId: 205023,
    fieldId:    $cf->getId(),
    value:      'WO-12345',
);

// 5. Iterate all templates of a customer with CF values pre-loaded
foreach ($client->documentTemplates()->cursorWithCustomFields(205023) as $tpl) {
    foreach ($tpl->getCustomFields() ?? [] as $cfVal) {
        // $cfVal is CustomFieldValueDTO
        echo $cfVal->getId() . ' => ' . $cfVal->getValue() . "\n";
    }
}
```

> **Critical:** `setCustomFieldValue()` returns HTTP 200 and silently discards the value
> when the field has not been assigned via `ensureAssignedToDocument()` first.  Always
> call `ensureAssignedToDocument()` during provisioning.

> **Note:** `GET/PUT /docBeeDocumentTemplate/customFields` returns HTTP 400 — the
> template-specific assignment endpoint does not exist.  Confirmed by live probe.

### Service Types

```php
// Find by exact name or article number (cursor scan — no server-side filter available)
$type = $client->serviceTypes()->findByName('Vor-Ort-Service');
$type = $client->serviceTypes()->findByNumber('1105');

// Deactivate a service type (use this instead of delete — see note below)
$client->serviceTypes()->update($id, ['deactivated' => true]);
```

> **Note:** `DELETE /serviceType/{id}` returns HTTP 403 — service types cannot be
> deleted via the Docbee REST API regardless of token permissions.  Use
> `update($id, ['deactivated' => true])` to remove a type from active use instead.
> Custom field definitions (`customFields()->delete()`) are subject to the same
> restriction.

### Creating Documents from Templates

Two methods exist with different capabilities:

| Method | Ticket linkage | `erpReferenceNumber` | `billable` | Embedded tasks |
|---|---|---|---|---|
| `fromTemplate($tplId, $data)` | ✗ HTTP 400 | ✗ silently ignored | ✗ silently ignored | ✓ |
| `createFromTemplate($tplId, $overrides)` | ✓ | ✓ | ✓ | ✓ |

```php
// Simple case — customer only, no ticket link needed
$doc = $client->documents()->fromTemplate(4109, ['customer' => 205023]);

// Full case — with ticket, ERP reference, billable flag
$doc = $client->documents()->createFromTemplate(
    templateId: 4109,
    overrides: [
        'customer'           => 205023,
        'ticket'             => 261861,
        'erpReferenceNumber' => 'WO-12345',
        'billable'           => true,
    ],
);
```

`createFromTemplate()` fetches the full template payload via
`GET /docBeeDocumentTemplate/{id}/createPayloadForDocBeeDocument` (including embedded
task structure), merges `$overrides` on top, and creates the document in one call.

> **Note:** `fromTemplate()` uses `POST /docBeeDocument/fromTemplate` which only
> accepts `customer` from the payload.  Passing `ticket` to it causes HTTP 400;
> `erpReferenceNumber` and `billable` are silently discarded.

### Document Tasks

```php
$tasks = $client->docBeeDocumentTasks($docId)->list();

// Update description
$task = $client->docBeeDocumentTasks($docId)->updateDescription($taskId, 'New description');

// Check whether a task can be deleted (has no work logs, planning times, or materials)
$check = $client->docBeeDocumentTasks($docId)->canBeDeleted($taskId);
if ($check->canDelete()) {
    $client->docBeeDocumentTasks($docId)->delete($taskId);
} else {
    echo implode(', ', $check->getBlockers()); // e.g. "has 2 work log(s), has 1 material(s)"
}
```

### Document Task Materials

```php
$materials = $client->docBeeDocumentTaskMaterials($docId, $taskId)->list();

// Find a material entry by material item ID
$mat = $client->docBeeDocumentTaskMaterials($docId, $taskId)->findByMaterialItemId(55);

// Add or increment a material (idempotent — increments amount if already present)
$mat = $client->docBeeDocumentTaskMaterials($docId, $taskId)
    ->addOrIncrementByMaterialItemId(materialItemId: 55, quantity: 3.0);
```

### Comments / Messages (Kommentare)

Docbee calls comments "messages" in the REST API; the UI labels them "Kommentare".
Both tickets and documents support a comment thread.

#### Ticket comments

```php
$msgs = $client->ticketMessages($ticketId);

// Check for any activity (fast — fetches totalCount only)
if ($msgs->hasMessages()) {
    echo "Ticket has " . $msgs->countMessages() . " comment(s).";
}

// Iterate all comments
foreach ($msgs->cursor() as $msg) {
    echo $msg->getCreated() . '  ' . $msg->getSender() . ': ' . $msg->getContent();
}

// Add a public comment
$msg = $msgs->add('Will be fixed by Friday.');

// Add an internal note (visible to staff only)
$msg = $msgs->add('Kunde angerufen.', internal: true);

// Add with subject line
$msg = $msgs->add('See below.', subject: 'Update', internal: false);
```

Ticket comments support full CRUD: `list()`, `find()`, `add()`, `update()`, `delete()`.

#### Document comments

```php
$msgs = $client->documentMessages($documentId);

// Check for any activity
if ($msgs->hasMessages()) {
    echo "Document has " . $msgs->countMessages() . " comment(s).";
}

// Iterate all comments
foreach ($msgs->cursor() as $msg) {
    echo $msg->getCreated() . '  ' . $msg->getSender() . ': ' . $msg->getContent();
}

// Add a comment
$msg = $msgs->add('Erledigt.');

// Add an internal comment
$msg = $msgs->add('Bitte nicht verrechnen.', internal: true);
```

> **API limitation:** `DocBeeDocumentMessage` supports only GET (list/single) and POST (add).
> `update()` and `delete()` inherited from `AbstractResource` are **not available** on this
> endpoint — calling them results in HTTP 404 or 405.  Use `TicketMessageResource` if full
> CRUD is required.

> **Note:** There is no message/comment endpoint for document tasks
> (`/docBeeDocumentTask/{id}/message`). The Docbee API spec does not expose this resource.

### Users

```php
$user = $client->users()->findByEmail('technician@company.com');
$all  = $client->users()->findActive();
```

---

## Webhooks

Docbee webhooks allow external systems to receive notifications when events occur.

### Available event types

| Constant | Event |
|----------|-------|
| `WebhookResource::TYPE_CREATE_DOCUMENT` | A new document is created |
| `WebhookResource::TYPE_CREATE_TICKET` | A new ticket is created |
| `WebhookResource::TYPE_EDIT_PROTOCOL` | A protocol is edited |
| `WebhookResource::TYPE_CREATE_TICKET_MESSAGE` | A message is added to a ticket |
| `WebhookResource::TYPE_EXECUTE_RULE_ENGINE` | The rule engine is executed |
| `WebhookResource::TYPE_CONTAINER` | Container event |

### Create a webhook

```php
use miralsoft\docbee\api\Resource\WebhookResource;

$webhook = $client->webhooks()->register(
    name:      'New Ticket Alert',
    type:      WebhookResource::TYPE_CREATE_TICKET,
    withEmail: true,
);

echo $webhook->getId();
```

### Generate a personalised link

```php
$link = $client->webhooks()->createLink(
    webhookId:          $webhook->getId(),
    ownerId:            1,   // required: user ID
    customerId:         42,  // optional
    customerLocationId: 3,   // optional
);

echo $link->getLink();
```

### Validate incoming webhook payloads

```php
use miralsoft\docbee\api\Util\WebhookValidator;

$body    = file_get_contents('php://input');
$payload = WebhookValidator::parseAndValidate($body);

WebhookValidator::validateType($payload['type']);

handleWebhook($payload['type'], $payload);
```

---

## Error Handling

All exceptions extend `DocbeeApiException`:

```php
use miralsoft\docbee\api\Exception\DocbeeApiException;
use miralsoft\docbee\api\Exception\AuthenticationException;
use miralsoft\docbee\api\Exception\NotFoundException;
use miralsoft\docbee\api\Exception\RateLimitException;
use miralsoft\docbee\api\Exception\ValidationException;
use miralsoft\docbee\api\Exception\ServerException;

try {
    $ticket = $client->tickets()->find(42);
} catch (NotFoundException $e) {
    echo "Not found: " . $e->getMessage();
} catch (AuthenticationException $e) {
    echo "Invalid token (HTTP " . $e->getStatusCode() . ")";
} catch (RateLimitException $e) {
    echo "Rate limit exceeded";
} catch (ValidationException $e) {
    echo "Bad request: " . $e->getMessage();
} catch (DocbeeApiException $e) {
    echo $e->getMessage();
}
```

### Exception hierarchy

```
DocbeeApiException
├── AuthenticationException  – HTTP 401 / 403
├── NotFoundException        – HTTP 404
├── RateLimitException       – HTTP 429
├── ValidationException      – HTTP 400 / 422
└── ServerException          – HTTP 5xx
```

---

## Logging

Pass any PSR-3 compatible logger to enable request logging:

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('docbee');
$logger->pushHandler(new StreamHandler('docbee.log'));

$client = new DocbeeClient(
    config: DocbeeConfig::fromEnv(),
    logger: $logger,
);
```

---

## Working with DTOs

All API responses are returned as typed DTO objects:

```php
$customer = $client->customers()->find(42);
echo $customer->getName();
echo $customer->getCustomerId();

// Update — pass a data array to update()
$updated = $client->customers()->update(42, [
    'info' => 'Updated via API',
]);

// DTOs implement JsonSerializable
echo json_encode($customer); // serialises via toArray()
```

---

## API Compatibility Checking

A built-in tool compares the live Docbee OpenAPI specification against the PHP implementation and reports every discrepancy: missing fields, extra fields, type mismatches, deprecated fields, and unimplemented endpoints.

### Run a compatibility report

```bash
php bin/check-api-compat.php
```

### Save a snapshot (baseline for drift detection)

```bash
php bin/check-api-compat.php --save-snapshot
```

Once a snapshot exists, every subsequent run also shows **what changed in the API itself** since the snapshot was saved (new schemas, removed fields, new endpoints, deprecated operations).

### Save reports to files

```bash
# Human-readable text report
php bin/check-api-compat.php --output=var/api-compat/report.txt

# Machine-readable JSON report (useful for automated processing)
php bin/check-api-compat.php --json-output=var/api-compat/report.json

# Combined: update snapshot and save both formats
php bin/check-api-compat.php --save-snapshot \
    --output=var/api-compat/report.txt \
    --json-output=var/api-compat/report.json
```

### PHPUnit integration

The compatibility check also runs as part of the test suite (`ApiCompatibilityTest`).  If the API is unreachable the test is **skipped**; if differences are found it is marked **incomplete** — never failed — so a spec drift does not break the build.

### Configuration

The spec URL and all paths are configured in `config/api-compat.php`.  This file is committed to the repository because the Docbee OpenAPI spec is publicly accessible.

---

## Testing

### Unit tests

```bash
composer install
composer test
```

### Integration tests (live API)

Integration tests make real HTTP requests to a Docbee tenant and verify that every resource and DTO works correctly against the live API.

**Setup:**

```bash
cp tests/.env.test.example tests/.env.test
# edit tests/.env.test and set DOCBEE_TENANT and DOCBEE_TOKEN
```

**Run:**

```bash
composer test:integration
# or
php vendor/bin/phpunit --testsuite Integration --testdox
```

**Behaviour:**

- If `tests/.env.test` is missing or `DOCBEE_TENANT` / `DOCBEE_TOKEN` are not set, every integration test is **automatically skipped** — the regular unit tests are never affected.
- HTTP 403 responses (feature not licensed or insufficient permissions) are converted to **skipped** tests.
- HTTP 404 responses (optional module not installed on the tenant) are also **skipped**.
- Only read-only (GET) calls are made — live data is never modified.
- Optional env vars in `.env.test` unlock additional `find($id)` and sub-resource tests (see `tests/.env.test.example` for the full list).

### Static analysis

```bash
composer analyse
```

---

## License

Proprietary — © Miralsoft
