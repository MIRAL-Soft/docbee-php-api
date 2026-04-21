<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\AgreementCategoryDTO;
use miralsoft\docbee\api\DTO\AgreementComponentDTO;
use miralsoft\docbee\api\DTO\AgreementComponentTemplateDTO;
use miralsoft\docbee\api\DTO\AgreementDTO;
use miralsoft\docbee\api\DTO\AgreementInvoiceDTO;
use miralsoft\docbee\api\DTO\AgreementPeriodDTO;
use miralsoft\docbee\api\DTO\AgreementTemplateDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for agreement resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_AGREEMENT_ID                      — enables find($id) for Agreement
 *   DOCBEE_TEST_AGREEMENT_TEMPLATE_ID             — enables find($id) for AgreementTemplate
 *   DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID    — enables sub-resource list tests
 *   DOCBEE_TEST_AGREEMENT_COMPONENT_TEMPLATES_PARENT_ID — enables component template sub-resource
 */
final class AgreementResourceIntegrationTest extends IntegrationTestCase
{
    // ── Agreement ─────────────────────────────────────────────────────────────

    public function testAgreementListReturnsArray(): void
    {
        $this->assertIsArray($this->client->agreements()->list());
    }

    public function testAgreementListItemsAreAgreementDTOs(): void
    {
        foreach ($this->client->agreements()->list() as $item) {
            $this->assertInstanceOf(AgreementDTO::class, $item);
        }
    }

    public function testAgreementFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->agreements()->find($id);
        $this->assertInstanceOf(AgreementDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── AgreementCategory ─────────────────────────────────────────────────────

    public function testAgreementCategoryListReturnsArray(): void
    {
        $this->assertIsArray($this->client->agreementCategories()->list());
    }

    public function testAgreementCategoryListItemsAreAgreementCategoryDTOs(): void
    {
        foreach ($this->client->agreementCategories()->list() as $item) {
            $this->assertInstanceOf(AgreementCategoryDTO::class, $item);
        }
    }

    // ── AgreementTemplate ─────────────────────────────────────────────────────

    public function testAgreementTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->agreementTemplates()->list());
    }

    public function testAgreementTemplateListItemsAreAgreementTemplateDTOs(): void
    {
        foreach ($this->client->agreementTemplates()->list() as $item) {
            $this->assertInstanceOf(AgreementTemplateDTO::class, $item);
        }
    }

    public function testAgreementTemplateFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_TEMPLATE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_TEMPLATE_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->agreementTemplates()->find($id);
        $this->assertInstanceOf(AgreementTemplateDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── AgreementComponent (sub-resource) ─────────────────────────────────────

    public function testAgreementComponentListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->agreementComponents($parentId)->list());
    }

    public function testAgreementComponentListItemsAreAgreementComponentDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->agreementComponents($parentId)->list() as $item) {
            $this->assertInstanceOf(AgreementComponentDTO::class, $item);
        }
    }

    // ── AgreementPeriod (sub-resource) ────────────────────────────────────────

    public function testAgreementPeriodListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->agreementPeriods($parentId)->list());
    }

    public function testAgreementPeriodListItemsAreAgreementPeriodDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->agreementPeriods($parentId)->list() as $item) {
            $this->assertInstanceOf(AgreementPeriodDTO::class, $item);
        }
    }

    // ── AgreementInvoice (sub-resource) ───────────────────────────────────────

    public function testAgreementInvoiceListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->agreementInvoices($parentId)->list());
    }

    public function testAgreementInvoiceListItemsAreAgreementInvoiceDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENTS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->agreementInvoices($parentId)->list() as $item) {
            $this->assertInstanceOf(AgreementInvoiceDTO::class, $item);
        }
    }

    // ── AgreementComponentTemplate (sub-resource) ─────────────────────────────

    public function testAgreementComponentTemplateListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENT_TEMPLATES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENT_TEMPLATES_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->agreementComponentTemplates($parentId)->list());
    }

    public function testAgreementComponentTemplateListItemsAreAgreementComponentTemplateDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_AGREEMENT_COMPONENT_TEMPLATES_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_AGREEMENT_COMPONENT_TEMPLATES_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->agreementComponentTemplates($parentId)->list() as $item) {
            $this->assertInstanceOf(AgreementComponentTemplateDTO::class, $item);
        }
    }
}
