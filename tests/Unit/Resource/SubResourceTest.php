<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\AgreementComponentDTO;
use miralsoft\docbee\api\DTO\AgreementInvoiceDTO;
use miralsoft\docbee\api\DTO\AgreementPeriodDTO;
use miralsoft\docbee\api\DTO\ContingentItemDTO;
use miralsoft\docbee\api\DTO\CostEstimationTaskDTO;
use miralsoft\docbee\api\DTO\DashboardWidgetDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentMessageDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTaskDTO;
use miralsoft\docbee\api\DTO\ElementDTO;
use miralsoft\docbee\api\DTO\MaterialDTO;
use miralsoft\docbee\api\DTO\PlanningTimeDTO;
use miralsoft\docbee\api\DTO\ProtocolEntryDTO;
use miralsoft\docbee\api\DTO\RuleEngineConditionDTO;
use miralsoft\docbee\api\DTO\SelectionValueDTO;
use miralsoft\docbee\api\DTO\SlaProfileSpecializationDTO;
use miralsoft\docbee\api\DTO\DocBeeDocumentTemplateTaskTemplateDTO;
use miralsoft\docbee\api\DTO\TaskTemplateDTO;
use miralsoft\docbee\api\DTO\TicketMessageDTO;
use miralsoft\docbee\api\DTO\TravelLogDTO;
use miralsoft\docbee\api\DTO\WorkLogDTO;
use miralsoft\docbee\api\Resource\AgreementComponentResource;
use miralsoft\docbee\api\Resource\AgreementInvoiceResource;
use miralsoft\docbee\api\Resource\AgreementPeriodResource;
use miralsoft\docbee\api\Resource\ContingentItemResource;
use miralsoft\docbee\api\Resource\CostEstimationTaskResource;
use miralsoft\docbee\api\Resource\DashboardWidgetResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentMessageResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTaskMaterialResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTaskPlanningTimeResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTaskResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTaskWorkLogResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTravelLogTemplateResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateMaterialTemplateResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplatePlanningTimeTemplateResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTemplateTaskTemplateWorkLogTemplateResource;
use miralsoft\docbee\api\Resource\DocBeeDocumentTravelLogResource;
use miralsoft\docbee\api\Resource\ProtocolGroupEntriesResource;
use miralsoft\docbee\api\Resource\ProtocolPlanningTimeResource;
use miralsoft\docbee\api\Resource\ProtocolTemplateEntryElementResource;
use miralsoft\docbee\api\Resource\RuleEngineConditionResource;
use miralsoft\docbee\api\Resource\SelectionValueResource;
use miralsoft\docbee\api\Resource\SlaProfileSpecializationResource;
use miralsoft\docbee\api\Resource\TicketMessageResource;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Verifies the sub-resource constructor-injection pattern:
 * each sub-resource class accepts a parent ID and bakes it into the endpoint.
 *
 * Also asserts that list() queries the correct nested endpoint and maps
 * responses to the expected DTO type.
 */
final class SubResourceTest extends TestCase
{
    /** @var HttpClientInterface&MockObject */
    private HttpClientInterface $http;

    protected function setUp(): void
    {
        $this->http = $this->createMock(HttpClientInterface::class);
    }

    // ── Endpoint construction ─────────────────────────────────────────────────

    public function testAgreementComponentEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/agreement/7/component'))
            ->willReturn(['totalCount' => 0, 'agreementComponent' => []]);

        (new AgreementComponentResource($this->http, 7))->list();
    }

    public function testAgreementPeriodEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/agreement/12/period'))
            ->willReturn(['totalCount' => 0, 'agreementPeriod' => []]);

        (new AgreementPeriodResource($this->http, 12))->list();
    }

    public function testAgreementInvoiceEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/agreement/3/invoice'))
            ->willReturn(['totalCount' => 0, 'agreementInvoice' => []]);

        (new AgreementInvoiceResource($this->http, 3))->list();
    }

    public function testContingentItemEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/contingent/99/item'))
            ->willReturn(['totalCount' => 0, 'contingentItem' => []]);

        (new ContingentItemResource($this->http, 99))->list();
    }

    public function testCostEstimationTaskEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/costEstimation/5/task'))
            ->willReturn(['totalCount' => 0, 'costEstimationTask' => []]);

        (new CostEstimationTaskResource($this->http, 5))->list();
    }

    public function testDashboardWidgetEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/dashboard/20/widget'))
            ->willReturn(['totalCount' => 0, 'dashboardWidget' => []]);

        (new DashboardWidgetResource($this->http, 20))->list();
    }

    public function testDocBeeDocumentMessageEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocument/8/message'))
            ->willReturn(['totalCount' => 0, 'docBeeDocumentMessage' => []]);

        (new DocBeeDocumentMessageResource($this->http, 8))->list();
    }

    public function testRuleEngineConditionEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/ruleEngineAction/4/condition'))
            ->willReturn(['totalCount' => 0, 'ruleEngineCondition' => []]);

        (new RuleEngineConditionResource($this->http, 4))->list();
    }

    public function testSelectionValueEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/selectionCategory/11/selectionValue'))
            ->willReturn(['totalCount' => 0, 'selectionValue' => []]);

        (new SelectionValueResource($this->http, 11))->list();
    }

    public function testSlaProfileSpecializationEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/slaProfile/2/specialization'))
            ->willReturn(['totalCount' => 0, 'slaProfileSpecialization' => []]);

        (new SlaProfileSpecializationResource($this->http, 2))->list();
    }

    public function testTicketMessageEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/ticket/42/message'))
            ->willReturn(['totalCount' => 0, 'ticketMessage' => []]);

        (new TicketMessageResource($this->http, 42))->list();
    }

    // ── DTO mapping ───────────────────────────────────────────────────────────

    public function testAgreementComponentListReturnsDTOArray(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount'         => 1,
                'agreementComponent' => [['id' => 1]],
            ]);

        $results = (new AgreementComponentResource($this->http, 1))->list();

        $this->assertCount(1, $results);
        $this->assertInstanceOf(AgreementComponentDTO::class, $results[0]);
    }

    public function testTicketMessageListReturnsDTOArray(): void
    {
        $this->http
            ->method('get')
            ->willReturn([
                'totalCount'    => 2,
                'ticketMessage' => [['id' => 10], ['id' => 11]],
            ]);

        $results = (new TicketMessageResource($this->http, 42))->list();

        $this->assertCount(2, $results);
        $this->assertInstanceOf(TicketMessageDTO::class, $results[0]);
    }

    public function testSelectionValueFindReturnsDTOWithCorrectId(): void
    {
        $this->http
            ->method('get')
            ->with($this->stringContains('v1/selectionCategory/3/selectionValue/55'))
            ->willReturn(['id' => 55]);

        $dto = (new SelectionValueResource($this->http, 3))->find(55);

        $this->assertInstanceOf(SelectionValueDTO::class, $dto);
        $this->assertSame(55, $dto->getId());
    }

    // ── New sub-resources (Groups C, D, E, F, G) ─────────────────────────────

    public function testDocBeeDocumentTravelLogEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocument/5/travelLog'))
            ->willReturn(['totalCount' => 0, 'travelLog' => []]);

        (new DocBeeDocumentTravelLogResource($this->http, 5))->list();
    }

    public function testDocBeeDocumentTemplateTaskTemplateEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocumentTemplate/10/taskTemplate'))
            ->willReturn(['totalCount' => 0, 'taskTemplate' => []]);

        (new DocBeeDocumentTemplateTaskTemplateResource($this->http, 10))->list();
    }

    public function testDocBeeDocumentTemplateTaskTemplateMaterialEndpointContainsBothIds(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocumentTemplate/10/taskTemplate/3/materialTemplate'))
            ->willReturn(['totalCount' => 0, 'materialTemplate' => []]);

        (new DocBeeDocumentTemplateTaskTemplateMaterialTemplateResource($this->http, 10, 3))->list();
    }

    public function testDocBeeDocumentTemplateTaskTemplatePlanningTimeEndpointContainsBothIds(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocumentTemplate/10/taskTemplate/3/planningTimeTemplate'))
            ->willReturn(['totalCount' => 0, 'planningTimeTemplate' => []]);

        (new DocBeeDocumentTemplateTaskTemplatePlanningTimeTemplateResource($this->http, 10, 3))->list();
    }

    public function testDocBeeDocumentTemplateTaskTemplateWorkLogEndpointContainsBothIds(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocumentTemplate/10/taskTemplate/3/workLogTemplate'))
            ->willReturn(['totalCount' => 0, 'workLogTemplate' => []]);

        (new DocBeeDocumentTemplateTaskTemplateWorkLogTemplateResource($this->http, 10, 3))->list();
    }

    public function testDocBeeDocumentTemplateTravelLogTemplateEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocumentTemplate/7/travelLogTemplate'))
            ->willReturn(['totalCount' => 0, 'travelLogTemplate' => []]);

        (new DocBeeDocumentTemplateTravelLogTemplateResource($this->http, 7))->list();
    }

    public function testProtocolTemplateEntryElementEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/protocolTemplateEntry/9/element'))
            ->willReturn(['totalCount' => 0, 'element' => []]);

        (new ProtocolTemplateEntryElementResource($this->http, 9))->list();
    }

    public function testDocBeeDocumentTaskEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocument/3/task'))
            ->willReturn(['totalCount' => 0, 'docBeeDocumentTask' => []]);

        (new DocBeeDocumentTaskResource($this->http, 3))->list();
    }

    public function testDocBeeDocumentTaskMaterialEndpointContainsBothIds(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocument/3/task/7/material'))
            ->willReturn(['totalCount' => 0, 'material' => []]);

        (new DocBeeDocumentTaskMaterialResource($this->http, 3, 7))->list();
    }

    public function testDocBeeDocumentTaskPlanningTimeEndpointContainsBothIds(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocument/3/task/7/planningTime'))
            ->willReturn(['totalCount' => 0, 'planningTime' => []]);

        (new DocBeeDocumentTaskPlanningTimeResource($this->http, 3, 7))->list();
    }

    public function testDocBeeDocumentTaskWorkLogEndpointContainsBothIds(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/docBeeDocument/3/task/7/workLog'))
            ->willReturn(['totalCount' => 0, 'workLog' => []]);

        (new DocBeeDocumentTaskWorkLogResource($this->http, 3, 7))->list();
    }

    public function testProtocolGroupEntriesEndpointContainsBothIds(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/protocol/2/protocolGroupEntries/5'))
            ->willReturn(['totalCount' => 0, 'protocolEntry' => []]);

        (new ProtocolGroupEntriesResource($this->http, 2, 5))->list();
    }

    public function testProtocolPlanningTimeEndpointContainsParentId(): void
    {
        $this->http
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('v1/protocol/6/planningTime'))
            ->willReturn(['totalCount' => 0, 'planningTime' => []]);

        (new ProtocolPlanningTimeResource($this->http, 6))->list();
    }

    // ── DTO mapping for new sub-resources ─────────────────────────────────────

    public function testDocBeeDocumentTravelLogListReturnsDTOArray(): void
    {
        $this->http->method('get')->willReturn([
            'totalCount' => 1,
            'travelLog'  => [['id' => 1]],
        ]);

        $results = (new DocBeeDocumentTravelLogResource($this->http, 1))->list();

        $this->assertCount(1, $results);
        $this->assertInstanceOf(TravelLogDTO::class, $results[0]);
    }

    public function testDocBeeDocumentTaskListReturnsDTOArray(): void
    {
        $this->http->method('get')->willReturn([
            'totalCount' => 1,
            'task'       => [['id' => 42]],
        ]);

        $results = (new DocBeeDocumentTaskResource($this->http, 1))->list();

        $this->assertCount(1, $results);
        $this->assertInstanceOf(DocBeeDocumentTaskDTO::class, $results[0]);
    }

    public function testDocBeeDocumentTemplateTaskTemplateListReturnsDTOArray(): void
    {
        $this->http->method('get')->willReturn([
            'totalCount'   => 1,
            'taskTemplate' => [['id' => 5]],
        ]);

        $results = (new DocBeeDocumentTemplateTaskTemplateResource($this->http, 1))->list();

        $this->assertCount(1, $results);
        $this->assertInstanceOf(DocBeeDocumentTemplateTaskTemplateDTO::class, $results[0]);
    }

    public function testProtocolGroupEntriesListReturnsDTOArray(): void
    {
        $this->http->method('get')->willReturn([
            'totalCount'    => 2,
            'protocolEntry' => [['id' => 10], ['id' => 11]],
        ]);

        $results = (new ProtocolGroupEntriesResource($this->http, 1, 2))->list();

        $this->assertCount(2, $results);
        $this->assertInstanceOf(ProtocolEntryDTO::class, $results[0]);
    }

    // ── Different parent IDs produce different endpoints ──────────────────────

    public function testDifferentParentIdsProduceDifferentEndpoints(): void
    {
        $calls = [];
        $this->http
            ->method('get')
            ->willReturnCallback(function (string $url) use (&$calls) {
                $calls[] = $url;
                return ['totalCount' => 0, 'costEstimationTask' => []];
            });

        (new CostEstimationTaskResource($this->http, 1))->list();
        (new CostEstimationTaskResource($this->http, 2))->list();

        $this->assertStringContainsString('costEstimation/1/task', $calls[0]);
        $this->assertStringContainsString('costEstimation/2/task', $calls[1]);
    }
}
