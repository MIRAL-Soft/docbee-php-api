<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Tests\Integration\Resource;

use miralsoft\docbee\api\DTO\AwayDTO;
use miralsoft\docbee\api\DTO\AwayReasonDTO;
use miralsoft\docbee\api\DTO\NoteDTO;
use miralsoft\docbee\api\DTO\TimeRecordDTO;
use miralsoft\docbee\api\DTO\TimerDTO;
use miralsoft\docbee\api\DTO\UserActivityDTO;
use miralsoft\docbee\api\DTO\UserDTO;
use miralsoft\docbee\api\DTO\UserProfileDTO;
use miralsoft\docbee\api\Tests\Integration\IntegrationTestCase;

/**
 * Integration tests for user and time-tracking resources — read-only.
 */
final class UserResourceIntegrationTest extends IntegrationTestCase
{
    // ── User ──────────────────────────────────────────────────────────────────

    public function testUserListReturnsArray(): void
    {
        $this->assertIsArray($this->client->users()->list());
    }

    public function testUserListItemsAreUserDTOs(): void
    {
        foreach ($this->client->users()->list() as $item) {
            $this->assertInstanceOf(UserDTO::class, $item);
        }
    }

    // ── UserProfile ───────────────────────────────────────────────────────────

    public function testUserProfileListReturnsArray(): void
    {
        $this->assertIsArray($this->client->userProfiles()->list());
    }

    public function testUserProfileListItemsAreUserProfileDTOs(): void
    {
        foreach ($this->client->userProfiles()->list() as $item) {
            $this->assertInstanceOf(UserProfileDTO::class, $item);
        }
    }

    // ── UserActivity ──────────────────────────────────────────────────────────

    public function testUserActivityListReturnsArray(): void
    {
        $this->assertIsArray($this->client->userActivities()->list());
    }

    public function testUserActivityListItemsAreUserActivityDTOs(): void
    {
        foreach ($this->client->userActivities()->list() as $item) {
            $this->assertInstanceOf(UserActivityDTO::class, $item);
        }
    }

    // ── Away ──────────────────────────────────────────────────────────────────

    public function testAwayListReturnsArray(): void
    {
        $this->assertIsArray($this->client->away()->list());
    }

    public function testAwayListItemsAreAwayDTOs(): void
    {
        foreach ($this->client->away()->list() as $item) {
            $this->assertInstanceOf(AwayDTO::class, $item);
        }
    }

    // ── AwayReason ────────────────────────────────────────────────────────────

    public function testAwayReasonListReturnsArray(): void
    {
        $this->assertIsArray($this->client->awayReasons()->list());
    }

    public function testAwayReasonListItemsAreAwayReasonDTOs(): void
    {
        foreach ($this->client->awayReasons()->list() as $item) {
            $this->assertInstanceOf(AwayReasonDTO::class, $item);
        }
    }

    // ── Note ──────────────────────────────────────────────────────────────────

    public function testNoteListReturnsArray(): void
    {
        $this->assertIsArray($this->client->notes()->list());
    }

    public function testNoteListItemsAreNoteDTOs(): void
    {
        foreach ($this->client->notes()->list() as $item) {
            $this->assertInstanceOf(NoteDTO::class, $item);
        }
    }

    // ── Timer ─────────────────────────────────────────────────────────────────

    public function testTimerListReturnsArray(): void
    {
        $this->assertIsArray($this->client->timers()->list());
    }

    public function testTimerListItemsAreTimerDTOs(): void
    {
        foreach ($this->client->timers()->list() as $item) {
            $this->assertInstanceOf(TimerDTO::class, $item);
        }
    }

    // ── TimeRecord ────────────────────────────────────────────────────────────

    public function testTimeRecordListReturnsArray(): void
    {
        $this->assertIsArray($this->client->timeRecords()->list());
    }

    public function testTimeRecordListItemsAreTimeRecordDTOs(): void
    {
        foreach ($this->client->timeRecords()->list() as $item) {
            $this->assertInstanceOf(TimeRecordDTO::class, $item);
        }
    }
}
