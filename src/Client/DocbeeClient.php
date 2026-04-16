<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Client;

use GuzzleHttp\Client as GuzzleClient;
use miralsoft\docbee\api\Config\DocbeeConfig;
use miralsoft\docbee\api\Resource\AgreementCategoryResource;
use miralsoft\docbee\api\Resource\AgreementResource;
use miralsoft\docbee\api\Resource\AgreementTemplateResource;
use miralsoft\docbee\api\Resource\AwayReasonResource;
use miralsoft\docbee\api\Resource\AwayResource;
use miralsoft\docbee\api\Resource\CompanyDataResource;
use miralsoft\docbee\api\Resource\ConfidentialTagResource;
use miralsoft\docbee\api\Resource\ContingentResource;
use miralsoft\docbee\api\Resource\CostEstimationResource;
use miralsoft\docbee\api\Resource\CostEstimationTemplateResource;
use miralsoft\docbee\api\Resource\CustomColorResource;
use miralsoft\docbee\api\Resource\CustomFieldResource;
use miralsoft\docbee\api\Resource\CustomerContactResource;
use miralsoft\docbee\api\Resource\CustomerLocationResource;
use miralsoft\docbee\api\Resource\CustomerObjectResource;
use miralsoft\docbee\api\Resource\CustomerProfileResource;
use miralsoft\docbee\api\Resource\CustomerResource;
use miralsoft\docbee\api\Resource\CustomerStatusResource;
use miralsoft\docbee\api\Resource\CustomerUserResource;
use miralsoft\docbee\api\Resource\DailyClosingConfigResource;
use miralsoft\docbee\api\Resource\DashboardResource;
use miralsoft\docbee\api\Resource\DepartmentProfileResource;
use miralsoft\docbee\api\Resource\DepartmentResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentRecurrenceResource;
use miralsoft\docbee\api\Resource\DocBeeScriptResource;
use miralsoft\docbee\api\Resource\DocumentResource;
use miralsoft\docbee\api\Resource\DocumentTaskResource;
use miralsoft\docbee\api\Resource\DocumentTemplateResource;
use miralsoft\docbee\api\Resource\DueDateColorResource;
use miralsoft\docbee\api\Resource\EnvVariableResource;
use miralsoft\docbee\api\Resource\ErrorLogResource;
use miralsoft\docbee\api\Resource\ExportProfileResource;
use miralsoft\docbee\api\Resource\InvoiceResource;
use miralsoft\docbee\api\Resource\MaterialItemResource;
use miralsoft\docbee\api\Resource\MessageTemplateResource;
use miralsoft\docbee\api\Resource\NoteResource;
use miralsoft\docbee\api\Resource\NotificationResource;
use miralsoft\docbee\api\Resource\ObjectCategoryResource;
use miralsoft\docbee\api\Resource\ObserverCategoryResource;
use miralsoft\docbee\api\Resource\ObserverTypeResource;
use miralsoft\docbee\api\Resource\ObserverUserResource;
use miralsoft\docbee\api\Resource\PaymentProfileResource;
use miralsoft\docbee\api\Resource\PdfLayoutResource;
use miralsoft\docbee\api\Resource\PermissionGroupResource;
use miralsoft\docbee\api\Resource\PresetProfileResource;
use miralsoft\docbee\api\Resource\PriorityResource;
use miralsoft\docbee\api\Resource\ProtocolResource;
use miralsoft\docbee\api\Resource\ProtocolTemplateResource;
use miralsoft\docbee\api\Resource\QueueResource;
use miralsoft\docbee\api\Resource\RequestTypeResource;
use miralsoft\docbee\api\Resource\RuleEngineActionResource;
use miralsoft\docbee\api\Resource\SelectionCategoryResource;
use miralsoft\docbee\api\Resource\ServiceProviderResource;
use miralsoft\docbee\api\Resource\ServiceProviderUserResource;
use miralsoft\docbee\api\Resource\ServiceTypeProfileResource;
use miralsoft\docbee\api\Resource\ServiceTypeResource;
use miralsoft\docbee\api\Resource\SkillResource;
use miralsoft\docbee\api\Resource\SlaProfileResource;
use miralsoft\docbee\api\Resource\TableConfigStorageResource;
use miralsoft\docbee\api\Resource\TagResource;
use miralsoft\docbee\api\Resource\TaskTemplateResource;
use miralsoft\docbee\api\Resource\TicketBoardProfileResource;
use miralsoft\docbee\api\Resource\TicketBoardResource;
use miralsoft\docbee\api\Resource\TicketCategoryResource;
use miralsoft\docbee\api\Resource\TicketLinkTypeResource;
use miralsoft\docbee\api\Resource\TicketMailParserConfigResource;
use miralsoft\docbee\api\Resource\TicketRecurrenceResource;
use miralsoft\docbee\api\Resource\TicketResource;
use miralsoft\docbee\api\Resource\TicketStatusResource;
use miralsoft\docbee\api\Resource\TicketTemplateResource;
use miralsoft\docbee\api\Resource\TimerResource;
use miralsoft\docbee\api\Resource\TimeRecordResource;
use miralsoft\docbee\api\Resource\TravelTypeResource;
use miralsoft\docbee\api\Resource\UserActivityResource;
use miralsoft\docbee\api\Resource\UserProfileResource;
use miralsoft\docbee\api\Resource\UserResource;
use miralsoft\docbee\api\Resource\WebhookResource;
use miralsoft\docbee\api\Resource\WorkPipeResource;
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
 *     'title'    => 'Printer not working',
 *     'customer' => 42,
 *     'priority' => 1,
 * ]);
 *
 * // Delta-sync: load everything changed in the last 5 minutes
 * $since   = new DateTimeImmutable('-5 minutes');
 * $tickets = $client->tickets()->findModifiedSince($since);
 * ```
 */
final class DocbeeClient
{
    private readonly HttpClient $http;

    // ── Lazily-initialised resource instances ──────────────────────────────────
    private ?AgreementResource           $agreements           = null;
    private ?AgreementCategoryResource   $agreementCategories  = null;
    private ?AgreementTemplateResource   $agreementTemplates   = null;
    private ?AwayResource                $away                 = null;
    private ?AwayReasonResource          $awayReasons          = null;
    private ?CompanyDataResource         $companyData          = null;
    private ?ConfidentialTagResource     $confidentialTags     = null;
    private ?ContingentResource          $contingents          = null;
    private ?CostEstimationResource      $costEstimations      = null;
    private ?CostEstimationTemplateResource $costEstimationTemplates = null;
    private ?CustomColorResource         $customColors         = null;
    private ?CustomFieldResource         $customFields         = null;
    private ?CustomerResource            $customers            = null;
    private ?CustomerContactResource     $customerContacts     = null;
    private ?CustomerLocationResource    $customerLocations    = null;
    private ?CustomerObjectResource      $customerObjects      = null;
    private ?CustomerProfileResource     $customerProfiles     = null;
    private ?CustomerStatusResource      $customerStatuses     = null;
    private ?CustomerUserResource        $customerUsers        = null;
    private ?DailyClosingConfigResource  $dailyClosingConfigs  = null;
    private ?DashboardResource           $dashboards           = null;
    private ?DepartmentResource          $departments          = null;
    private ?DepartmentProfileResource   $departmentProfiles   = null;
    private ?DocBeeDocumentRecurrenceResource $documentRecurrences = null;
    private ?DocBeeScriptResource        $docBeeScripts        = null;
    private ?DocumentResource            $documents            = null;
    private ?DocumentTaskResource        $documentTasks        = null;
    private ?DocumentTemplateResource    $documentTemplates    = null;
    private ?DueDateColorResource        $dueDateColors        = null;
    private ?EnvVariableResource         $envVariables         = null;
    private ?ErrorLogResource            $errorLogs            = null;
    private ?ExportProfileResource       $exportProfiles       = null;
    private ?InvoiceResource             $invoices             = null;
    private ?MaterialItemResource        $materialItems        = null;
    private ?MessageTemplateResource     $messageTemplates     = null;
    private ?NoteResource                $notes                = null;
    private ?NotificationResource        $notifications        = null;
    private ?ObjectCategoryResource      $objectCategories     = null;
    private ?ObserverCategoryResource    $observerCategories   = null;
    private ?ObserverTypeResource        $observerTypes        = null;
    private ?ObserverUserResource        $observerUsers        = null;
    private ?PaymentProfileResource      $paymentProfiles      = null;
    private ?PdfLayoutResource           $pdfLayouts           = null;
    private ?PermissionGroupResource     $permissionGroups     = null;
    private ?PresetProfileResource       $presetProfiles       = null;
    private ?PriorityResource            $priorities           = null;
    private ?ProtocolResource            $protocols            = null;
    private ?ProtocolTemplateResource    $protocolTemplates    = null;
    private ?QueueResource               $queues               = null;
    private ?RequestTypeResource         $requestTypes         = null;
    private ?RuleEngineActionResource    $ruleEngineActions    = null;
    private ?SelectionCategoryResource   $selectionCategories  = null;
    private ?ServiceProviderResource     $serviceProviders     = null;
    private ?ServiceProviderUserResource $serviceProviderUsers = null;
    private ?ServiceTypeResource         $serviceTypes         = null;
    private ?ServiceTypeProfileResource  $serviceTypeProfiles  = null;
    private ?SkillResource               $skills               = null;
    private ?SlaProfileResource          $slaProfiles          = null;
    private ?TableConfigStorageResource  $tableConfigStorages  = null;
    private ?TagResource                 $tags                 = null;
    private ?TaskTemplateResource        $taskTemplates        = null;
    private ?TicketResource              $tickets              = null;
    private ?TicketBoardResource         $ticketBoards         = null;
    private ?TicketBoardProfileResource  $ticketBoardProfiles  = null;
    private ?TicketCategoryResource      $ticketCategories     = null;
    private ?TicketLinkTypeResource      $ticketLinkTypes      = null;
    private ?TicketMailParserConfigResource $ticketMailParserConfigs = null;
    private ?TicketRecurrenceResource    $ticketRecurrences    = null;
    private ?TicketStatusResource        $ticketStatuses       = null;
    private ?TicketTemplateResource      $ticketTemplates      = null;
    private ?TimerResource               $timers               = null;
    private ?TimeRecordResource          $timeRecords          = null;
    private ?TravelTypeResource          $travelTypes          = null;
    private ?UserResource                $users                = null;
    private ?UserActivityResource        $userActivities       = null;
    private ?UserProfileResource         $userProfiles         = null;
    private ?WebhookResource             $webhooks             = null;
    private ?WorkPipeResource            $workPipes            = null;

    /**
     * @param DocbeeConfig         $config An immutable configuration object.
     * @param GuzzleClient|null    $guzzle Optional custom Guzzle client (useful for testing).
     * @param LoggerInterface|null $logger Optional PSR-3 logger for request logging.
     */
    public function __construct(
        DocbeeConfig     $config,
        ?GuzzleClient    $guzzle = null,
        ?LoggerInterface $logger = null,
    ) {
        $this->http = new HttpClient($config, $guzzle, $logger);
    }

    // ── Resource accessors ─────────────────────────────────────────────────────

    /** Returns the agreement resource handler. */
    public function agreements(): AgreementResource
    {
        return $this->agreements ??= new AgreementResource($this->http);
    }

    /** Returns the agreement category resource handler. */
    public function agreementCategories(): AgreementCategoryResource
    {
        return $this->agreementCategories ??= new AgreementCategoryResource($this->http);
    }

    /** Returns the agreement template resource handler. */
    public function agreementTemplates(): AgreementTemplateResource
    {
        return $this->agreementTemplates ??= new AgreementTemplateResource($this->http);
    }

    /** Returns the away resource handler. */
    public function away(): AwayResource
    {
        return $this->away ??= new AwayResource($this->http);
    }

    /** Returns the away reason resource handler. */
    public function awayReasons(): AwayReasonResource
    {
        return $this->awayReasons ??= new AwayReasonResource($this->http);
    }

    /** Returns the company data resource handler. */
    public function companyData(): CompanyDataResource
    {
        return $this->companyData ??= new CompanyDataResource($this->http);
    }

    /** Returns the confidential tag resource handler. */
    public function confidentialTags(): ConfidentialTagResource
    {
        return $this->confidentialTags ??= new ConfidentialTagResource($this->http);
    }

    /** Returns the contingent resource handler. */
    public function contingents(): ContingentResource
    {
        return $this->contingents ??= new ContingentResource($this->http);
    }

    /** Returns the cost estimation resource handler. */
    public function costEstimations(): CostEstimationResource
    {
        return $this->costEstimations ??= new CostEstimationResource($this->http);
    }

    /** Returns the cost estimation template resource handler. */
    public function costEstimationTemplates(): CostEstimationTemplateResource
    {
        return $this->costEstimationTemplates ??= new CostEstimationTemplateResource($this->http);
    }

    /** Returns the custom color resource handler. */
    public function customColors(): CustomColorResource
    {
        return $this->customColors ??= new CustomColorResource($this->http);
    }

    /** Returns the custom field resource handler. */
    public function customFields(): CustomFieldResource
    {
        return $this->customFields ??= new CustomFieldResource($this->http);
    }

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

    /** Returns the customer object resource handler. */
    public function customerObjects(): CustomerObjectResource
    {
        return $this->customerObjects ??= new CustomerObjectResource($this->http);
    }

    /** Returns the customer profile resource handler. */
    public function customerProfiles(): CustomerProfileResource
    {
        return $this->customerProfiles ??= new CustomerProfileResource($this->http);
    }

    /** Returns the customer status resource handler. */
    public function customerStatuses(): CustomerStatusResource
    {
        return $this->customerStatuses ??= new CustomerStatusResource($this->http);
    }

    /** Returns the customer user resource handler. */
    public function customerUsers(): CustomerUserResource
    {
        return $this->customerUsers ??= new CustomerUserResource($this->http);
    }

    /** Returns the daily closing config resource handler. */
    public function dailyClosingConfigs(): DailyClosingConfigResource
    {
        return $this->dailyClosingConfigs ??= new DailyClosingConfigResource($this->http);
    }

    /** Returns the dashboard resource handler. */
    public function dashboards(): DashboardResource
    {
        return $this->dashboards ??= new DashboardResource($this->http);
    }

    /** Returns the department resource handler. */
    public function departments(): DepartmentResource
    {
        return $this->departments ??= new DepartmentResource($this->http);
    }

    /** Returns the department profile resource handler. */
    public function departmentProfiles(): DepartmentProfileResource
    {
        return $this->departmentProfiles ??= new DepartmentProfileResource($this->http);
    }

    /** Returns the document recurrence resource handler. */
    public function documentRecurrences(): DocBeeDocumentRecurrenceResource
    {
        return $this->documentRecurrences ??= new DocBeeDocumentRecurrenceResource($this->http);
    }

    /** Returns the DocBee script resource handler. */
    public function docBeeScripts(): DocBeeScriptResource
    {
        return $this->docBeeScripts ??= new DocBeeScriptResource($this->http);
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

    /** Returns the due date color resource handler. */
    public function dueDateColors(): DueDateColorResource
    {
        return $this->dueDateColors ??= new DueDateColorResource($this->http);
    }

    /** Returns the environment variable resource handler. */
    public function envVariables(): EnvVariableResource
    {
        return $this->envVariables ??= new EnvVariableResource($this->http);
    }

    /** Returns the error log resource handler. */
    public function errorLogs(): ErrorLogResource
    {
        return $this->errorLogs ??= new ErrorLogResource($this->http);
    }

    /** Returns the export profile resource handler. */
    public function exportProfiles(): ExportProfileResource
    {
        return $this->exportProfiles ??= new ExportProfileResource($this->http);
    }

    /** Returns the invoice resource handler. */
    public function invoices(): InvoiceResource
    {
        return $this->invoices ??= new InvoiceResource($this->http);
    }

    /** Returns the material item resource handler. */
    public function materialItems(): MaterialItemResource
    {
        return $this->materialItems ??= new MaterialItemResource($this->http);
    }

    /** Returns the message template resource handler. */
    public function messageTemplates(): MessageTemplateResource
    {
        return $this->messageTemplates ??= new MessageTemplateResource($this->http);
    }

    /** Returns the note resource handler. */
    public function notes(): NoteResource
    {
        return $this->notes ??= new NoteResource($this->http);
    }

    /** Returns the notification resource handler. */
    public function notifications(): NotificationResource
    {
        return $this->notifications ??= new NotificationResource($this->http);
    }

    /** Returns the object category resource handler. */
    public function objectCategories(): ObjectCategoryResource
    {
        return $this->objectCategories ??= new ObjectCategoryResource($this->http);
    }

    /** Returns the observer category resource handler. */
    public function observerCategories(): ObserverCategoryResource
    {
        return $this->observerCategories ??= new ObserverCategoryResource($this->http);
    }

    /** Returns the observer type resource handler. */
    public function observerTypes(): ObserverTypeResource
    {
        return $this->observerTypes ??= new ObserverTypeResource($this->http);
    }

    /** Returns the observer user resource handler. */
    public function observerUsers(): ObserverUserResource
    {
        return $this->observerUsers ??= new ObserverUserResource($this->http);
    }

    /** Returns the payment profile resource handler. */
    public function paymentProfiles(): PaymentProfileResource
    {
        return $this->paymentProfiles ??= new PaymentProfileResource($this->http);
    }

    /** Returns the PDF layout resource handler. */
    public function pdfLayouts(): PdfLayoutResource
    {
        return $this->pdfLayouts ??= new PdfLayoutResource($this->http);
    }

    /** Returns the permission group resource handler. */
    public function permissionGroups(): PermissionGroupResource
    {
        return $this->permissionGroups ??= new PermissionGroupResource($this->http);
    }

    /** Returns the preset profile resource handler. */
    public function presetProfiles(): PresetProfileResource
    {
        return $this->presetProfiles ??= new PresetProfileResource($this->http);
    }

    /** Returns the priority resource handler. */
    public function priorities(): PriorityResource
    {
        return $this->priorities ??= new PriorityResource($this->http);
    }

    /** Returns the protocol resource handler. */
    public function protocols(): ProtocolResource
    {
        return $this->protocols ??= new ProtocolResource($this->http);
    }

    /** Returns the protocol template resource handler. */
    public function protocolTemplates(): ProtocolTemplateResource
    {
        return $this->protocolTemplates ??= new ProtocolTemplateResource($this->http);
    }

    /** Returns the queue resource handler. */
    public function queues(): QueueResource
    {
        return $this->queues ??= new QueueResource($this->http);
    }

    /** Returns the request type resource handler. */
    public function requestTypes(): RequestTypeResource
    {
        return $this->requestTypes ??= new RequestTypeResource($this->http);
    }

    /** Returns the rule engine action resource handler. */
    public function ruleEngineActions(): RuleEngineActionResource
    {
        return $this->ruleEngineActions ??= new RuleEngineActionResource($this->http);
    }

    /** Returns the selection category resource handler. */
    public function selectionCategories(): SelectionCategoryResource
    {
        return $this->selectionCategories ??= new SelectionCategoryResource($this->http);
    }

    /** Returns the service provider resource handler. */
    public function serviceProviders(): ServiceProviderResource
    {
        return $this->serviceProviders ??= new ServiceProviderResource($this->http);
    }

    /** Returns the service provider user resource handler. */
    public function serviceProviderUsers(): ServiceProviderUserResource
    {
        return $this->serviceProviderUsers ??= new ServiceProviderUserResource($this->http);
    }

    /** Returns the service type resource handler. */
    public function serviceTypes(): ServiceTypeResource
    {
        return $this->serviceTypes ??= new ServiceTypeResource($this->http);
    }

    /** Returns the service type profile resource handler. */
    public function serviceTypeProfiles(): ServiceTypeProfileResource
    {
        return $this->serviceTypeProfiles ??= new ServiceTypeProfileResource($this->http);
    }

    /** Returns the skill resource handler. */
    public function skills(): SkillResource
    {
        return $this->skills ??= new SkillResource($this->http);
    }

    /** Returns the SLA profile resource handler. */
    public function slaProfiles(): SlaProfileResource
    {
        return $this->slaProfiles ??= new SlaProfileResource($this->http);
    }

    /** Returns the table config storage resource handler. */
    public function tableConfigStorages(): TableConfigStorageResource
    {
        return $this->tableConfigStorages ??= new TableConfigStorageResource($this->http);
    }

    /** Returns the tag resource handler. */
    public function tags(): TagResource
    {
        return $this->tags ??= new TagResource($this->http);
    }

    /** Returns the task template resource handler. */
    public function taskTemplates(): TaskTemplateResource
    {
        return $this->taskTemplates ??= new TaskTemplateResource($this->http);
    }

    /** Returns the ticket resource handler. */
    public function tickets(): TicketResource
    {
        return $this->tickets ??= new TicketResource($this->http);
    }

    /** Returns the ticket board resource handler. */
    public function ticketBoards(): TicketBoardResource
    {
        return $this->ticketBoards ??= new TicketBoardResource($this->http);
    }

    /** Returns the ticket board profile resource handler. */
    public function ticketBoardProfiles(): TicketBoardProfileResource
    {
        return $this->ticketBoardProfiles ??= new TicketBoardProfileResource($this->http);
    }

    /** Returns the ticket category resource handler. */
    public function ticketCategories(): TicketCategoryResource
    {
        return $this->ticketCategories ??= new TicketCategoryResource($this->http);
    }

    /** Returns the ticket link type resource handler. */
    public function ticketLinkTypes(): TicketLinkTypeResource
    {
        return $this->ticketLinkTypes ??= new TicketLinkTypeResource($this->http);
    }

    /** Returns the ticket mail parser config resource handler. */
    public function ticketMailParserConfigs(): TicketMailParserConfigResource
    {
        return $this->ticketMailParserConfigs ??= new TicketMailParserConfigResource($this->http);
    }

    /** Returns the ticket recurrence resource handler. */
    public function ticketRecurrences(): TicketRecurrenceResource
    {
        return $this->ticketRecurrences ??= new TicketRecurrenceResource($this->http);
    }

    /** Returns the ticket status resource handler. */
    public function ticketStatuses(): TicketStatusResource
    {
        return $this->ticketStatuses ??= new TicketStatusResource($this->http);
    }

    /** Returns the ticket template resource handler. */
    public function ticketTemplates(): TicketTemplateResource
    {
        return $this->ticketTemplates ??= new TicketTemplateResource($this->http);
    }

    /** Returns the timer resource handler. */
    public function timers(): TimerResource
    {
        return $this->timers ??= new TimerResource($this->http);
    }

    /** Returns the time record resource handler. */
    public function timeRecords(): TimeRecordResource
    {
        return $this->timeRecords ??= new TimeRecordResource($this->http);
    }

    /** Returns the travel type resource handler. */
    public function travelTypes(): TravelTypeResource
    {
        return $this->travelTypes ??= new TravelTypeResource($this->http);
    }

    /** Returns the user resource handler. */
    public function users(): UserResource
    {
        return $this->users ??= new UserResource($this->http);
    }

    /** Returns the user activity resource handler. */
    public function userActivities(): UserActivityResource
    {
        return $this->userActivities ??= new UserActivityResource($this->http);
    }

    /** Returns the user profile resource handler. */
    public function userProfiles(): UserProfileResource
    {
        return $this->userProfiles ??= new UserProfileResource($this->http);
    }

    /** Returns the webhook resource handler. */
    public function webhooks(): WebhookResource
    {
        return $this->webhooks ??= new WebhookResource($this->http);
    }

    /** Returns the work pipe resource handler. */
    public function workPipes(): WorkPipeResource
    {
        return $this->workPipes ??= new WorkPipeResource($this->http);
    }

    // ── Sub-resource factory methods ───────────────────────────────────────────

    /**
     * Returns the agreement component resource for a specific agreement.
     *
     * @param int $agreementId The agreement's numeric ID.
     */
    public function agreementComponents(int $agreementId): \miralsoft\docbee\api\Resource\AgreementComponentResource
    {
        return new \miralsoft\docbee\api\Resource\AgreementComponentResource($this->http, $agreementId);
    }

    /**
     * Returns the agreement period resource for a specific agreement.
     */
    public function agreementPeriods(int $agreementId): \miralsoft\docbee\api\Resource\AgreementPeriodResource
    {
        return new \miralsoft\docbee\api\Resource\AgreementPeriodResource($this->http, $agreementId);
    }

    /**
     * Returns the agreement invoice resource for a specific agreement.
     */
    public function agreementInvoices(int $agreementId): \miralsoft\docbee\api\Resource\AgreementInvoiceResource
    {
        return new \miralsoft\docbee\api\Resource\AgreementInvoiceResource($this->http, $agreementId);
    }

    /**
     * Returns the agreement component template resource for a specific agreement template.
     */
    public function agreementComponentTemplates(int $agreementTemplateId): \miralsoft\docbee\api\Resource\AgreementComponentTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\AgreementComponentTemplateResource($this->http, $agreementTemplateId);
    }

    /**
     * Returns the contingent element resource for a specific contingent.
     */
    public function contingentElements(int $contingentId): \miralsoft\docbee\api\Resource\ContingentElementResource
    {
        return new \miralsoft\docbee\api\Resource\ContingentElementResource($this->http, $contingentId);
    }

    /**
     * Returns the contingent item resource for a specific contingent.
     */
    public function contingentItems(int $contingentId): \miralsoft\docbee\api\Resource\ContingentItemResource
    {
        return new \miralsoft\docbee\api\Resource\ContingentItemResource($this->http, $contingentId);
    }

    /**
     * Returns the contingent item recurrence resource for a specific contingent.
     */
    public function contingentItemRecurrences(int $contingentId): \miralsoft\docbee\api\Resource\ContingentItemRecurrenceResource
    {
        return new \miralsoft\docbee\api\Resource\ContingentItemRecurrenceResource($this->http, $contingentId);
    }

    /**
     * Returns the cost estimation task resource for a specific cost estimation.
     */
    public function costEstimationTasks(int $costEstimationId): \miralsoft\docbee\api\Resource\CostEstimationTaskResource
    {
        return new \miralsoft\docbee\api\Resource\CostEstimationTaskResource($this->http, $costEstimationId);
    }

    /**
     * Returns the cost estimation task template resource for a specific cost estimation template.
     */
    public function costEstimationTaskTemplates(int $costEstimationTemplateId): \miralsoft\docbee\api\Resource\CostEstimationTaskTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\CostEstimationTaskTemplateResource($this->http, $costEstimationTemplateId);
    }

    /**
     * Returns the dashboard widget resource for a specific dashboard.
     */
    public function dashboardWidgets(int $dashboardId): \miralsoft\docbee\api\Resource\DashboardWidgetResource
    {
        return new \miralsoft\docbee\api\Resource\DashboardWidgetResource($this->http, $dashboardId);
    }

    /**
     * Returns the document conflict resource for a specific document.
     */
    public function documentConflicts(int $documentId): \miralsoft\docbee\api\Resource\DocBeeDocumentConflictResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentConflictResource($this->http, $documentId);
    }

    /**
     * Returns the document message resource for a specific document.
     */
    public function documentMessages(int $documentId): \miralsoft\docbee\api\Resource\DocBeeDocumentMessageResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentMessageResource($this->http, $documentId);
    }

    /**
     * Returns the DocBee script parameter resource for a specific script.
     */
    public function docBeeScriptParameters(int $scriptId): \miralsoft\docbee\api\Resource\DocBeeScriptParameterResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeScriptParameterResource($this->http, $scriptId);
    }

    /**
     * Returns the protocol entry resource for a specific protocol.
     */
    public function protocolEntries(int $protocolId): \miralsoft\docbee\api\Resource\ProtocolEntryResource
    {
        return new \miralsoft\docbee\api\Resource\ProtocolEntryResource($this->http, $protocolId);
    }

    /**
     * Returns the protocol group data resource for a specific protocol.
     */
    public function protocolGroupData(int $protocolId): \miralsoft\docbee\api\Resource\ProtocolGroupDataResource
    {
        return new \miralsoft\docbee\api\Resource\ProtocolGroupDataResource($this->http, $protocolId);
    }

    /**
     * Returns the protocol document template resource for a specific protocol template.
     */
    public function protocolDocumentTemplates(int $protocolTemplateId): \miralsoft\docbee\api\Resource\ProtocolDocumentTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\ProtocolDocumentTemplateResource($this->http, $protocolTemplateId);
    }

    /**
     * Returns the rule engine condition resource for a specific rule engine action.
     */
    public function ruleEngineConditions(int $actionId): \miralsoft\docbee\api\Resource\RuleEngineConditionResource
    {
        return new \miralsoft\docbee\api\Resource\RuleEngineConditionResource($this->http, $actionId);
    }

    /**
     * Returns the rule engine reaction resource for a specific rule engine action.
     */
    public function ruleEngineReactions(int $actionId): \miralsoft\docbee\api\Resource\RuleEngineReactionResource
    {
        return new \miralsoft\docbee\api\Resource\RuleEngineReactionResource($this->http, $actionId);
    }

    /**
     * Returns the rule engine setting resource for a specific rule engine action.
     */
    public function ruleEngineSettings(int $actionId): \miralsoft\docbee\api\Resource\RuleEngineSettingResource
    {
        return new \miralsoft\docbee\api\Resource\RuleEngineSettingResource($this->http, $actionId);
    }

    /**
     * Returns the selection value resource for a specific selection category.
     */
    public function selectionValues(int $selectionCategoryId): \miralsoft\docbee\api\Resource\SelectionValueResource
    {
        return new \miralsoft\docbee\api\Resource\SelectionValueResource($this->http, $selectionCategoryId);
    }

    /**
     * Returns the SLA profile specialization resource for a specific SLA profile.
     */
    public function slaProfileSpecializations(int $slaProfileId): \miralsoft\docbee\api\Resource\SlaProfileSpecializationResource
    {
        return new \miralsoft\docbee\api\Resource\SlaProfileSpecializationResource($this->http, $slaProfileId);
    }

    /**
     * Returns the SLA profile working hour resource for a specific SLA profile.
     */
    public function slaProfileWorkingHours(int $slaProfileId): \miralsoft\docbee\api\Resource\SlaProfileWorkingHourResource
    {
        return new \miralsoft\docbee\api\Resource\SlaProfileWorkingHourResource($this->http, $slaProfileId);
    }

    /**
     * Returns the ticket board column resource for a specific ticket board.
     */
    public function ticketBoardColumns(int $boardId): \miralsoft\docbee\api\Resource\TicketBoardColumnResource
    {
        return new \miralsoft\docbee\api\Resource\TicketBoardColumnResource($this->http, $boardId);
    }

    /**
     * Returns the ticket board field resource for a specific ticket board.
     */
    public function ticketBoardFields(int $boardId): \miralsoft\docbee\api\Resource\TicketBoardFieldResource
    {
        return new \miralsoft\docbee\api\Resource\TicketBoardFieldResource($this->http, $boardId);
    }

    /**
     * Returns the ticket board filter resource for a specific ticket board.
     */
    public function ticketBoardFilters(int $boardId): \miralsoft\docbee\api\Resource\TicketBoardFilterResource
    {
        return new \miralsoft\docbee\api\Resource\TicketBoardFilterResource($this->http, $boardId);
    }

    /**
     * Returns the ticket link resource for a specific ticket.
     */
    public function ticketLinks(int $ticketId): \miralsoft\docbee\api\Resource\TicketLinkResource
    {
        return new \miralsoft\docbee\api\Resource\TicketLinkResource($this->http, $ticketId);
    }

    /**
     * Returns the ticket message resource for a specific ticket.
     */
    public function ticketMessages(int $ticketId): \miralsoft\docbee\api\Resource\TicketMessageResource
    {
        return new \miralsoft\docbee\api\Resource\TicketMessageResource($this->http, $ticketId);
    }

    /**
     * Returns the payment profile mapping resource for a specific payment profile.
     */
    public function paymentProfileMappings(int $paymentProfileId): \miralsoft\docbee\api\Resource\PaymentProfileMappingResource
    {
        return new \miralsoft\docbee\api\Resource\PaymentProfileMappingResource($this->http, $paymentProfileId);
    }

    /**
     * Returns the preset value resource for a specific preset profile.
     */
    public function presetValues(int $presetProfileId): \miralsoft\docbee\api\Resource\PresetValueResource
    {
        return new \miralsoft\docbee\api\Resource\PresetValueResource($this->http, $presetProfileId);
    }

    // ── Messaging ─────────────────────────────────────────────────────────────

    /** Returns the message resource (e.g. for sending e-mails via sendMail()). */
    public function messages(): \miralsoft\docbee\api\Resource\MessageResource
    {
        return new \miralsoft\docbee\api\Resource\MessageResource($this->http);
    }

    // ── Document travel logs ───────────────────────────────────────────────────

    /** Returns the travel-log sub-resource for a specific document. */
    public function documentTravelLogs(int $documentId): \miralsoft\docbee\api\Resource\DocBeeDocumentTravelLogResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTravelLogResource($this->http, $documentId);
    }

    // ── Document-template task templates (and their nested sub-resources) ─────

    /** Returns the task-template sub-resource for a specific document template. */
    public function documentTemplateTaskTemplates(int $documentId): \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateResource($this->http, $documentId);
    }

    /** Returns the material-template sub-resource for a task template inside a document template. */
    public function documentTemplateTaskTemplateMaterials(int $documentId, int $taskTemplateId): \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateMaterialTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateMaterialTemplateResource($this->http, $documentId, $taskTemplateId);
    }

    /** Returns the planning-time-template sub-resource for a task template inside a document template. */
    public function documentTemplateTaskTemplatePlanningTimes(int $documentId, int $taskTemplateId): \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplatePlanningTimeTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplatePlanningTimeTemplateResource($this->http, $documentId, $taskTemplateId);
    }

    /** Returns the work-log-template sub-resource for a task template inside a document template. */
    public function documentTemplateTaskTemplateWorkLogs(int $documentId, int $taskTemplateId): \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateWorkLogTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateWorkLogTemplateResource($this->http, $documentId, $taskTemplateId);
    }

    /** Returns the travel-log-template sub-resource for a specific document template. */
    public function documentTemplateTravelLogTemplates(int $documentId): \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTravelLogTemplateResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTravelLogTemplateResource($this->http, $documentId);
    }

    // ── Document tasks (nested under a specific document) ─────────────────────

    /** Returns the task sub-resource for a specific document (nested endpoint). */
    public function docBeeDocumentTasks(int $documentId): \miralsoft\docbee\api\Resource\DocBeeDocumentTaskResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTaskResource($this->http, $documentId);
    }

    /** Returns the material sub-resource for a task inside a document. */
    public function docBeeDocumentTaskMaterials(int $documentId, int $taskId): \miralsoft\docbee\api\Resource\DocBeeDocumentTaskMaterialResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTaskMaterialResource($this->http, $documentId, $taskId);
    }

    /** Returns the planning-time sub-resource for a task inside a document. */
    public function docBeeDocumentTaskPlanningTimes(int $documentId, int $taskId): \miralsoft\docbee\api\Resource\DocBeeDocumentTaskPlanningTimeResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTaskPlanningTimeResource($this->http, $documentId, $taskId);
    }

    /** Returns the work-log sub-resource for a task inside a document. */
    public function docBeeDocumentTaskWorkLogs(int $documentId, int $taskId): \miralsoft\docbee\api\Resource\DocBeeDocumentTaskWorkLogResource
    {
        return new \miralsoft\docbee\api\Resource\DocBeeDocumentTaskWorkLogResource($this->http, $documentId, $taskId);
    }

    // ── Protocol group entries ─────────────────────────────────────────────────

    /** Returns the group-entries resource for a specific protocol + template group. */
    public function protocolGroupEntries(int $protocolId, int $groupId): \miralsoft\docbee\api\Resource\ProtocolGroupEntriesResource
    {
        return new \miralsoft\docbee\api\Resource\ProtocolGroupEntriesResource($this->http, $protocolId, $groupId);
    }

    /** Returns the standalone group-actions resource for managing entries/mappings across all groups of a protocol. */
    public function protocolGroup(int $protocolId): \miralsoft\docbee\api\Resource\ProtocolGroupResource
    {
        return new \miralsoft\docbee\api\Resource\ProtocolGroupResource($this->http, $protocolId);
    }

    /** Returns the planning-time sub-resource for a specific protocol. */
    public function protocolPlanningTimes(int $protocolId): \miralsoft\docbee\api\Resource\ProtocolPlanningTimeResource
    {
        return new \miralsoft\docbee\api\Resource\ProtocolPlanningTimeResource($this->http, $protocolId);
    }

    // ── Protocol template entry elements ──────────────────────────────────────

    /** Returns the element sub-resource for a specific protocol template entry. */
    public function protocolTemplateEntryElements(int $entryId): \miralsoft\docbee\api\Resource\ProtocolTemplateEntryElementResource
    {
        return new \miralsoft\docbee\api\Resource\ProtocolTemplateEntryElementResource($this->http, $entryId);
    }
}
