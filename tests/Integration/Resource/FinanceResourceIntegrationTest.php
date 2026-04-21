<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\ExportProfileDTO;
use miralsoft\docbee\api\DTO\InvoiceDTO;
use miralsoft\docbee\api\DTO\PaymentProfileDTO;
use miralsoft\docbee\api\DTO\PaymentProfileMappingDTO;
use miralsoft\docbee\api\DTO\PdfLayoutDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for finance and output resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_EXPORT_PROFILE_ID            — enables find($id) for ExportProfile
 *   DOCBEE_TEST_PAYMENT_PROFILE_ID           — enables find($id) for PaymentProfile
 *   DOCBEE_TEST_PAYMENT_PROFILE_MAPPINGS_PARENT_ID — enables paymentProfileMappings sub-tests
 */
final class FinanceResourceIntegrationTest extends IntegrationTestCase
{
    // ── Invoice ───────────────────────────────────────────────────────────────

    public function testInvoiceListReturnsArray(): void
    {
        $this->assertIsArray($this->client->invoices()->list());
    }

    public function testInvoiceListItemsAreInvoiceDTOs(): void
    {
        foreach ($this->client->invoices()->list() as $item) {
            $this->assertInstanceOf(InvoiceDTO::class, $item);
        }
    }

    // ── PaymentProfile ────────────────────────────────────────────────────────

    public function testPaymentProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->paymentProfiles()->list());
    }

    public function testPaymentProfileListItemsArePaymentProfileDTOs(): void
    {
        foreach ($this->client->paymentProfiles()->list() as $item) {
            $this->assertInstanceOf(PaymentProfileDTO::class, $item);
        }
    }

    public function testPaymentProfileFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_PAYMENT_PROFILE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PAYMENT_PROFILE_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->paymentProfiles()->find($id);
        $this->assertInstanceOf(PaymentProfileDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── PaymentProfileMapping (sub-resource) ──────────────────────────────────

    public function testPaymentProfileMappingListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PAYMENT_PROFILE_MAPPINGS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PAYMENT_PROFILE_MAPPINGS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->paymentProfileMappings($parentId)->list());
    }

    public function testPaymentProfileMappingListItemsArePaymentProfileMappingDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_PAYMENT_PROFILE_MAPPINGS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_PAYMENT_PROFILE_MAPPINGS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->paymentProfileMappings($parentId)->list() as $item) {
            $this->assertInstanceOf(PaymentProfileMappingDTO::class, $item);
        }
    }

    // ── ExportProfile ─────────────────────────────────────────────────────────

    public function testExportProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->exportProfiles()->list());
    }

    public function testExportProfileListItemsAreExportProfileDTOs(): void
    {
        foreach ($this->client->exportProfiles()->list() as $item) {
            $this->assertInstanceOf(ExportProfileDTO::class, $item);
        }
    }

    public function testExportProfileFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_EXPORT_PROFILE_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_EXPORT_PROFILE_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->exportProfiles()->find($id);
        $this->assertInstanceOf(ExportProfileDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── PdfLayout ─────────────────────────────────────────────────────────────

    public function testPdfLayoutListReturnsArray(): void
    {
        $this->assertIsArray($this->client->pdfLayouts()->list());
    }

    public function testPdfLayoutListItemsArePdfLayoutDTOs(): void
    {
        foreach ($this->client->pdfLayouts()->list() as $item) {
            $this->assertInstanceOf(PdfLayoutDTO::class, $item);
        }
    }
}
