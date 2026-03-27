<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Client;

use GuzzleHttp\Client as GuzzleClient;
use miralsoft\docbee\api\Config\DocbeeConfig;
use miralsoft\docbee\api\Resource\CustomerContactResource;
use miralsoft\docbee\api\Resource\CustomerLocationResource;
use miralsoft\docbee\api\Resource\CustomerResource;
use miralsoft\docbee\api\Resource\DocumentResource;
use miralsoft\docbee\api\Resource\DocumentTaskResource;
use miralsoft\docbee\api\Resource\DocumentTemplateResource;
use miralsoft\docbee\api\Resource\PriorityResource;
use miralsoft\docbee\api\Resource\RequestTypeResource;
use miralsoft\docbee\api\Resource\ServiceTypeResource;
use miralsoft\docbee\api\Resource\TagResource;
use miralsoft\docbee\api\Resource\TicketResource;
use miralsoft\docbee\api\Resource\TicketStatusResource;
use miralsoft\docbee\api\Resource\UserResource;
use miralsoft\docbee\api\Resource\WebhookResource;
use Psr\Log\LoggerInterface;

/**
 * Main entry point for the Docbee PHP API library.
 *
 * Instantiate this class with a {@see DocbeeConfig} and access all resources
 * via typed accessor methods. Each accessor lazily creates a resource instance
 * on first call and caches it for the lifetime of the client.
 *
 * ## Quick start
 *
 * ```php
 * use miralsoft\docbee\api\Client\DocbeeClient;
 * use miralsoft\docbee\api\Config\DocbeeConfig;
 *
 * $client = new DocbeeClient(new DocbeeConfig(
 *     tenant: 'mycompany',
 *     token:  'your-api-token',
 * ));
 *
 * // Create a ticket
 * $ticket = $client->tickets()->create([
 *     'title'       => 'Printer not working',
 *     'customer'    => 42,
 *     'priority'    => 1,
 * ]);
 *
 * // Delta-sync: load everything changed in the last 5 minutes
 * $since   = new DateTimeImmutable('-5 minutes');
 * $tickets = $client->tickets()->findModifiedSince($since);
 *
 * // Register a webhook
 * $webhook = $client->webhooks()->register(
 *     name:      'New Ticket Alert',
 *     type:      WebhookResource::TYPE_CREATE_TICKET,
 *     withEmail: true,
 * );
 * $link = $client->webhooks()->createLink($webhook->getId(), ownerId: 1, customerId: 42);
 * ```
 *
 * ## Configuration from environment variables
 *
 * ```php
 * // Reads DOCBEE_TENANT and DOCBEE_TOKEN from the environment.
 * $client = new DocbeeClient(DocbeeConfig::fromEnv());
 * ```
 */
final class DocbeeClient
{
    private readonly HttpClient $http;

    // Lazily-initialised resource instances.
    private ?CustomerResource         $customers         = null;
    private ?CustomerContactResource  $customerContacts  = null;
    private ?CustomerLocationResource $customerLocations = null;
    private ?TicketResource           $tickets           = null;
    private ?UserResource             $users             = null;
    private ?TagResource              $tags              = null;
    private ?ServiceTypeResource      $serviceTypes      = null;
    private ?PriorityResource         $priorities        = null;
    private ?RequestTypeResource      $requestTypes      = null;
    private ?TicketStatusResource     $ticketStatuses    = null;
    private ?DocumentResource         $documents         = null;
    private ?DocumentTaskResource     $documentTasks     = null;
    private ?DocumentTemplateResource $documentTemplates = null;
    private ?WebhookResource          $webhooks          = null;

    /**
     * @param DocbeeConfig        $config An immutable configuration object.
     * @param GuzzleClient|null   $guzzle Optional custom Guzzle client (useful for testing).
     * @param LoggerInterface|null $logger Optional PSR-3 logger for request logging.
     */
    public function __construct(
        DocbeeConfig     $config,
        ?GuzzleClient    $guzzle = null,
        ?LoggerInterface $logger = null,
    ) {
        $this->http = new HttpClient($config, $guzzle, $logger);
    }

    // -------------------------------------------------------------------------
    // Resource accessors
    // -------------------------------------------------------------------------

    /** Returns the customer resource handler. */
    public function customers(): CustomerResource
    {
        return $this->customers ??= new CustomerResource($this->http);
    }

    /** Returns the customer contact resource handler. */
    public function customerContacts(): CustomerContactResource
    {
        return $this->customerContacts ??= new CustomerContactResource($this->http);
    }

    /** Returns the customer location resource handler. */
    public function customerLocations(): CustomerLocationResource
    {
        return $this->customerLocations ??= new CustomerLocationResource($this->http);
    }

    /** Returns the ticket resource handler. */
    public function tickets(): TicketResource
    {
        return $this->tickets ??= new TicketResource($this->http);
    }

    /** Returns the user resource handler. */
    public function users(): UserResource
    {
        return $this->users ??= new UserResource($this->http);
    }

    /** Returns the tag resource handler. */
    public function tags(): TagResource
    {
        return $this->tags ??= new TagResource($this->http);
    }

    /** Returns the service type resource handler. */
    public function serviceTypes(): ServiceTypeResource
    {
        return $this->serviceTypes ??= new ServiceTypeResource($this->http);
    }

    /** Returns the priority resource handler. */
    public function priorities(): PriorityResource
    {
        return $this->priorities ??= new PriorityResource($this->http);
    }

    /** Returns the request type resource handler. */
    public function requestTypes(): RequestTypeResource
    {
        return $this->requestTypes ??= new RequestTypeResource($this->http);
    }

    /** Returns the ticket status resource handler. */
    public function ticketStatuses(): TicketStatusResource
    {
        return $this->ticketStatuses ??= new TicketStatusResource($this->http);
    }

    /** Returns the document (protocol) resource handler. */
    public function documents(): DocumentResource
    {
        return $this->documents ??= new DocumentResource($this->http);
    }

    /** Returns the document task resource handler. */
    public function documentTasks(): DocumentTaskResource
    {
        return $this->documentTasks ??= new DocumentTaskResource($this->http);
    }

    /** Returns the document template resource handler. */
    public function documentTemplates(): DocumentTemplateResource
    {
        return $this->documentTemplates ??= new DocumentTemplateResource($this->http);
    }

    /** Returns the webhook resource handler. */
    public function webhooks(): WebhookResource
    {
        return $this->webhooks ??= new WebhookResource($this->http);
    }
}
