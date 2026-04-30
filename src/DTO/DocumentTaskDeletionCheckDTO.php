<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\DTO;

/**
 * Read-only result of a task deletion pre-check.
 *
 * Indicates whether a DocBeeDocument task can be safely deleted and, if not,
 * which linked records prevent deletion.
 *
 * ```php
 * $check = $client->tasks($docId)->canBeDeleted($taskId);
 *
 * if (!$check->canDelete()) {
 *     throw new \RuntimeException(
 *         "Task cannot be deleted: " . implode(', ', $check->getBlockers())
 *     );
 * }
 * ```
 */
final class DocumentTaskDeletionCheckDTO
{
    public function __construct(
        private readonly int $workLogCount,
        private readonly int $planningTimeCount,
        private readonly int $materialCount,
    ) {}

    /**
     * Returns true when no linked records block deletion.
     */
    public function canDelete(): bool
    {
        return $this->workLogCount === 0
            && $this->planningTimeCount === 0
            && $this->materialCount === 0;
    }

    /**
     * Returns a list of human-readable blocker descriptions.
     *
     * Empty when {@see canDelete()} returns true.
     *
     * @return list<string>
     */
    public function getBlockers(): array
    {
        $blockers = [];

        if ($this->workLogCount > 0) {
            $blockers[] = "has {$this->workLogCount} work log(s)";
        }
        if ($this->planningTimeCount > 0) {
            $blockers[] = "has {$this->planningTimeCount} planning time(s)";
        }
        if ($this->materialCount > 0) {
            $blockers[] = "has {$this->materialCount} material(s)";
        }

        return $blockers;
    }

    public function getWorkLogCount(): int      { return $this->workLogCount; }
    public function getPlanningTimeCount(): int  { return $this->planningTimeCount; }
    public function getMaterialCount(): int      { return $this->materialCount; }
}
