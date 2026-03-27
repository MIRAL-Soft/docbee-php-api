# Docbee PHP API Client

A professional PHP client library for the [Docbee](https://www.docbee.com) REST API.

Provides typed access to all Docbee resources — tickets, customers, documents, webhooks and more — without having to deal with raw HTTP requests or JSON parsing.

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP         | ≥ 8.1   |
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

// List all open tickets
$tickets = $client->tickets()->findOpen();

// Create a new ticket
$ticket = $client->tickets()->create([
    'title'    => 'Printer offline',
    'customer' => 42,
    'priority' => 1,
]);

echo $ticket->getId();   // e.g. 1234
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

All resources are accessed via the `DocbeeClient` instance:

| Method                          | Resource               |
|---------------------------------|------------------------|
| `$client->tickets()`            | Support tickets        |
| `$client->customers()`          | Customers              |
| `$client->customerContacts()`   | Customer contacts      |
| `$client->customerLocations()`  | Customer locations     |
| `$client->users()`              | System users           |
| `$client->tags()`               | Tags                   |
| `$client->priorities()`         | Priority levels        |
| `$client->requestTypes()`       | Request types          |
| `$client->serviceTypes()`       | Service types          |
| `$client->ticketStatuses()`     | Ticket statuses        |
| `$client->documents()`          | Documents / protocols  |
| `$client->documentTasks()`      | Document tasks         |
| `$client->documentTemplates()`  | Document templates     |
| `$client->webhooks()`           | Webhook subscriptions  |

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
    QueryBuilder::new()->filterEq('status', 'open')
);
```

### Paginate all records (memory-efficient)

```php
foreach ($client->tickets()->cursor() as $ticket) {
    // processes one ticket at a time
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
    ->filterEq('status', 'open')             // status == 'open'
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

| Constant             | Operator       |
|----------------------|----------------|
| `FilterOperator::EQ`    | equals (==)    |
| `FilterOperator::NEQ`   | not equals (!=)|
| `FilterOperator::ILIKE` | case-insensitive LIKE |
| `FilterOperator::GT`    | greater than   |
| `FilterOperator::GTE`   | greater than or equal |
| `FilterOperator::LT`    | less than      |
| `FilterOperator::LTE`   | less than or equal |
| `FilterOperator::IN`    | IN list        |

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
$tickets = $client->tickets()->findByCustomer(42, status: 'open');
$tickets = $client->tickets()->findByOrderId('ORD-2024-001');
$tickets = $client->tickets()->findByAssignedUser(5);
$tickets = $client->tickets()->findOpen();
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

| Constant                                  | Event                                  |
|-------------------------------------------|----------------------------------------|
| `WebhookResource::TYPE_CREATE_DOCUMENT`   | A new document is created              |
| `WebhookResource::TYPE_CREATE_TICKET`     | A new ticket is created                |
| `WebhookResource::TYPE_EDIT_PROTOCOL`     | A protocol is edited                   |
| `WebhookResource::TYPE_CREATE_TICKET_MESSAGE` | A message is added to a ticket    |
| `WebhookResource::TYPE_EXECUTE_RULE_ENGINE`   | The rule engine is executed        |
| `WebhookResource::TYPE_CONTAINER`         | Container event                        |

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
    webhookId:         $webhook->getId(),
    ownerId:           1,           // required: user ID
    customerId:        42,          // optional
    customerLocationId: 3,          // optional
);

echo $link->getLink(); // share this URL with the customer
```

### Validate incoming webhook payloads

```php
use miralsoft\docbee\api\Util\WebhookValidator;

// In your webhook receiver script:
$body    = file_get_contents('php://input');
$payload = WebhookValidator::parseAndValidate($body);

WebhookValidator::validateType($payload['type']);

// Safe to process
handleWebhook($payload['type'], $payload);
```

---

## Error Handling

All exceptions extend `DocbeeApiException`, so you can catch everything with one handler:

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
    echo "Ticket not found: " . $e->getMessage();
} catch (AuthenticationException $e) {
    echo "Invalid API token (HTTP " . $e->getStatusCode() . ")";
} catch (RateLimitException $e) {
    echo "Rate limit exceeded — all retries exhausted";
} catch (ValidationException $e) {
    echo "Invalid request: " . $e->getMessage();
    echo "Response: " . $e->getResponseBody();
} catch (ServerException $e) {
    echo "Docbee server error: " . $e->getStatusCode();
} catch (DocbeeApiException $e) {
    // Catch-all for any other API error
    echo $e->getMessage();
    echo $e->getRequestUrl();
}
```

### Exception hierarchy

```
DocbeeApiException
├── AuthenticationException  – HTTP 401 / 403 (invalid/expired token)
├── NotFoundException        – HTTP 404 (resource not found)
├── RateLimitException       – HTTP 429 (rate limit exceeded)
├── ValidationException      – HTTP 400 / 422 (bad request)
└── ServerException          – HTTP 5xx (server error)
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

## Working with DTOs

All API responses are returned as typed DTO objects. Use `toArray()` to serialise them back for write operations:

```php
// Fetch an existing customer
$customer = $client->customers()->find(42);

// Modify and update (pass a data array to update())
$updated = $client->customers()->update(42, [
    'email' => 'new@email.com',
    'phone' => '+49 89 999999',
]);
```

---

## License

Proprietary – © MIRAL-Soft / Miralsoft. All rights reserved.
