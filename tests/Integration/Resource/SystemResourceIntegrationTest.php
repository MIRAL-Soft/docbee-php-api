<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\DocBeeScriptDTO;
use miralsoft\docbee\api\DTO\DocBeeScriptParameterDTO;
use miralsoft\docbee\api\DTO\EnvVariableDTO;
use miralsoft\docbee\api\DTO\ErrorLogDTO;
use miralsoft\docbee\api\DTO\MessageTemplateDTO;
use miralsoft\docbee\api\DTO\NotificationDTO;
use miralsoft\docbee\api\DTO\RuleEngineActionDTO;
use miralsoft\docbee\api\DTO\RuleEngineConditionDTO;
use miralsoft\docbee\api\DTO\RuleEngineReactionDTO;
use miralsoft\docbee\api\DTO\RuleEngineSettingDTO;
use miralsoft\docbee\api\DTO\WebhookDTO;
use miralsoft\docbee\api\DTO\WorkPipeDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for system and automation resources — read-only.
 *
 * Optional env vars:
 *   DOCBEE_TEST_DOC_BEE_SCRIPT_ID           — enables find($id) for DocBeeScript
 *   DOCBEE_TEST_RULE_ENGINE_ACTION_ID        — enables find($id) for RuleEngineAction
 *   DOCBEE_TEST_DOC_BEE_SCRIPT_PARAMS_PARENT_ID — enables docBeeScriptParameters sub-tests
 *   DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID       — enables ruleEngineConditions/Reactions/Settings sub-tests
 */
final class SystemResourceIntegrationTest extends IntegrationTestCase
{
    // ── EnvVariable ───────────────────────────────────────────────────────────

    public function testEnvVariableListReturnsArray(): void
    {
        $this->assertIsArray($this->client->envVariables()->list());
    }

    public function testEnvVariableListItemsAreEnvVariableDTOs(): void
    {
        foreach ($this->client->envVariables()->list() as $item) {
            $this->assertInstanceOf(EnvVariableDTO::class, $item);
        }
    }

    // ── ErrorLog ──────────────────────────────────────────────────────────────

    public function testErrorLogListReturnsArray(): void
    {
        $this->assertIsArray($this->client->errorLogs()->list());
    }

    public function testErrorLogListItemsAreErrorLogDTOs(): void
    {
        foreach ($this->client->errorLogs()->list() as $item) {
            $this->assertInstanceOf(ErrorLogDTO::class, $item);
        }
    }

    // ── Webhook ───────────────────────────────────────────────────────────────

    public function testWebhookListReturnsArray(): void
    {
        $this->assertIsArray($this->client->webhooks()->list());
    }

    public function testWebhookListItemsAreWebhookDTOs(): void
    {
        foreach ($this->client->webhooks()->list() as $item) {
            $this->assertInstanceOf(WebhookDTO::class, $item);
        }
    }

    // ── WorkPipe ──────────────────────────────────────────────────────────────

    public function testWorkPipeListReturnsArray(): void
    {
        $this->assertIsArray($this->client->workPipes()->list());
    }

    public function testWorkPipeListItemsAreWorkPipeDTOs(): void
    {
        foreach ($this->client->workPipes()->list() as $item) {
            $this->assertInstanceOf(WorkPipeDTO::class, $item);
        }
    }

    // ── Notification ──────────────────────────────────────────────────────────

    public function testNotificationListReturnsArray(): void
    {
        $this->assertIsArray($this->client->notifications()->list());
    }

    public function testNotificationListItemsAreNotificationDTOs(): void
    {
        foreach ($this->client->notifications()->list() as $item) {
            $this->assertInstanceOf(NotificationDTO::class, $item);
        }
    }

    // ── MessageTemplate ───────────────────────────────────────────────────────

    public function testMessageTemplateListReturnsArray(): void
    {
        $this->assertIsArray($this->client->messageTemplates()->list());
    }

    public function testMessageTemplateListItemsAreMessageTemplateDTOs(): void
    {
        foreach ($this->client->messageTemplates()->list() as $item) {
            $this->assertInstanceOf(MessageTemplateDTO::class, $item);
        }
    }

    // ── DocBeeScript ──────────────────────────────────────────────────────────

    public function testDocBeeScriptListReturnsArray(): void
    {
        $this->assertIsArray($this->client->docBeeScripts()->list());
    }

    public function testDocBeeScriptListItemsAreDocBeeScriptDTOs(): void
    {
        foreach ($this->client->docBeeScripts()->list() as $item) {
            $this->assertInstanceOf(DocBeeScriptDTO::class, $item);
        }
    }

    public function testDocBeeScriptFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_DOC_BEE_SCRIPT_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOC_BEE_SCRIPT_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->docBeeScripts()->find($id);
        $this->assertInstanceOf(DocBeeScriptDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── DocBeeScriptParameter (sub-resource) ──────────────────────────────────

    public function testDocBeeScriptParameterListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_DOC_BEE_SCRIPT_PARAMS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOC_BEE_SCRIPT_PARAMS_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->docBeeScriptParameters($parentId)->list());
    }

    public function testDocBeeScriptParameterListItemsAreDocBeeScriptParameterDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_DOC_BEE_SCRIPT_PARAMS_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_DOC_BEE_SCRIPT_PARAMS_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->docBeeScriptParameters($parentId)->list() as $item) {
            $this->assertInstanceOf(DocBeeScriptParameterDTO::class, $item);
        }
    }

    // ── RuleEngineAction ──────────────────────────────────────────────────────

    public function testRuleEngineActionListReturnsArray(): void
    {
        $this->assertIsArray($this->client->ruleEngineActions()->list());
    }

    public function testRuleEngineActionListItemsAreRuleEngineActionDTOs(): void
    {
        foreach ($this->client->ruleEngineActions()->list() as $item) {
            $this->assertInstanceOf(RuleEngineActionDTO::class, $item);
        }
    }

    public function testRuleEngineActionFindById(): void
    {
        $id = $this->optionalIntEnv('DOCBEE_TEST_RULE_ENGINE_ACTION_ID');
        if ($id === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_RULE_ENGINE_ACTION_ID in tests/.env.test to enable.');
        }
        $dto = $this->client->ruleEngineActions()->find($id);
        $this->assertInstanceOf(RuleEngineActionDTO::class, $dto);
        $this->assertSame($id, $dto->getId());
    }

    // ── RuleEngineCondition (sub-resource) ────────────────────────────────────

    public function testRuleEngineConditionListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->ruleEngineConditions($parentId)->list());
    }

    public function testRuleEngineConditionListItemsAreRuleEngineConditionDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ruleEngineConditions($parentId)->list() as $item) {
            $this->assertInstanceOf(RuleEngineConditionDTO::class, $item);
        }
    }

    // ── RuleEngineReaction (sub-resource) ─────────────────────────────────────

    public function testRuleEngineReactionListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->ruleEngineReactions($parentId)->list());
    }

    public function testRuleEngineReactionListItemsAreRuleEngineReactionDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ruleEngineReactions($parentId)->list() as $item) {
            $this->assertInstanceOf(RuleEngineReactionDTO::class, $item);
        }
    }

    // ── RuleEngineSetting (sub-resource) ──────────────────────────────────────

    public function testRuleEngineSettingListReturnsArray(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        $this->assertIsArray($this->client->ruleEngineSettings($parentId)->list());
    }

    public function testRuleEngineSettingListItemsAreRuleEngineSettingDTOs(): void
    {
        $parentId = $this->optionalIntEnv('DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID');
        if ($parentId === null) {
            $this->markTestSkipped('Set DOCBEE_TEST_RULE_ENGINE_SUB_PARENT_ID in tests/.env.test to enable.');
        }
        foreach ($this->client->ruleEngineSettings($parentId)->list() as $item) {
            $this->assertInstanceOf(RuleEngineSettingDTO::class, $item);
        }
    }
}
