<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Unit\DTO;

use miralsoft\docbee\api\DTO\DocumentTaskDeletionCheckDTO;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see DocumentTaskDeletionCheckDTO}.
 */
final class DocumentTaskDeletionCheckDTOTest extends TestCase
{
    public function testCanDeleteReturnsTrueWhenAllCountsAreZero(): void
    {
        $dto = new DocumentTaskDeletionCheckDTO(0, 0, 0);

        $this->assertTrue($dto->canDelete());
        $this->assertSame([], $dto->getBlockers());
    }

    public function testCanDeleteReturnsFalseWhenWorkLogsExist(): void
    {
        $dto = new DocumentTaskDeletionCheckDTO(workLogCount: 2, planningTimeCount: 0, materialCount: 0);

        $this->assertFalse($dto->canDelete());
        $blockers = $dto->getBlockers();
        $this->assertCount(1, $blockers);
        $this->assertStringContainsString('work log', $blockers[0]);
        $this->assertStringContainsString('2', $blockers[0]);
    }

    public function testCanDeleteReturnsFalseWhenPlanningTimesExist(): void
    {
        $dto = new DocumentTaskDeletionCheckDTO(0, 3, 0);

        $this->assertFalse($dto->canDelete());
        $blockers = $dto->getBlockers();
        $this->assertCount(1, $blockers);
        $this->assertStringContainsString('planning time', $blockers[0]);
    }

    public function testCanDeleteReturnsFalseWhenMaterialsExist(): void
    {
        $dto = new DocumentTaskDeletionCheckDTO(0, 0, 1);

        $this->assertFalse($dto->canDelete());
        $blockers = $dto->getBlockers();
        $this->assertCount(1, $blockers);
        $this->assertStringContainsString('material', $blockers[0]);
    }

    public function testAllThreeBlockersReported(): void
    {
        $dto = new DocumentTaskDeletionCheckDTO(1, 2, 3);

        $this->assertFalse($dto->canDelete());
        $this->assertCount(3, $dto->getBlockers());
        $this->assertSame(1, $dto->getWorkLogCount());
        $this->assertSame(2, $dto->getPlanningTimeCount());
        $this->assertSame(3, $dto->getMaterialCount());
    }
}
