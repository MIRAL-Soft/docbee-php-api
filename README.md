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
$tickets = $client->tickets()->findByStatus(1);

// Create a new ticket
$ticket = $client->tickets()->create([
    'title'    => 'Printer offline',
    'customer' => 42,
    'priority' => 1,
]);

echo $ticket->getId();    // e.g. 1234
echo $ticket->getTitle(); // "Printer offline"
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
| `$client->protocolEntries(int $protocolId)` | `v1/protocol/{id}/entry` |
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

---

## Resource-specific Methods

### Customers

```php
$customer = $client->customers()->findByCustomerNumber('K-1001');
$matches  = $client->customers()->findByName('Acme');
$active   = $client->customers()->findActive();
```

### Tickets

```php
$tickets = $client->tickets()->findByCustomer(42);
$tickets = $client->tickets()->findByCustomer(42, statusId: 1);
$tickets = $client->tickets()->findByStatus(1);
$tickets = $client->tickets()->findByOrderId('ORD-2024-001');
$tickets = $client->tickets()->findByAssignedUser(5);
```

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
echo $customer->getEmail();

// Update — pass a data array to update()
$updated = $client->customers()->update(42, [
    'email' => 'new@email.com',
]);

// DTOs implement JsonSerializable
echo json_encode($customer); // serialises via toArray()
```

---

## Testing

```bash
composer install
composer test
```

Run static analysis:

```bash
composer analyse
```

---

## License

Proprietary — © Miralsoft
