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
        $result = $this->callApi(fn() => $this->client->users()->list());
        $this->assertIsArray($result);
    }

    public function testUserListItemsAreUserDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->users()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(UserDTO::class, $item);
        }
    }

    // ── UserProfile ───────────────────────────────────────────────────────────

    public function testUserProfileListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->userProfiles()->list());
        $this->assertIsArray($result);
    }

    public function testUserProfileListItemsAreUserProfileDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->userProfiles()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(UserProfileDTO::class, $item);
        }
    }

    // ── UserActivity ──────────────────────────────────────────────────────────

    public function testUserActivityListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->userActivities()->list());
        $this->assertIsArray($result);
    }

    public function testUserActivityListItemsAreUserActivityDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->userActivities()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(UserActivityDTO::class, $item);
        }
    }

    // ── Away ──────────────────────────────────────────────────────────────────

    public function testAwayListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->away()->list());
        $this->assertIsArray($result);
    }

    public function testAwayListItemsAreAwayDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->away()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(AwayDTO::class, $item);
        }
    }

    // ── AwayReason ────────────────────────────────────────────────────────────

    public function testAwayReasonListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->awayReasons()->list());
        $this->assertIsArray($result);
    }

    public function testAwayReasonListItemsAreAwayReasonDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->awayReasons()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(AwayReasonDTO::class, $item);
        }
    }

    // ── Note ──────────────────────────────────────────────────────────────────

    public function testNoteListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->notes()->list());
        $this->assertIsArray($result);
    }

    public function testNoteListItemsAreNoteDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->notes()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(NoteDTO::class, $item);
        }
    }

    // ── Timer ─────────────────────────────────────────────────────────────────

    public function testTimerListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->timers()->list());
        $this->assertIsArray($result);
    }

    public function testTimerListItemsAreTimerDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->timers()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TimerDTO::class, $item);
        }
    }

    // ── TimeRecord ────────────────────────────────────────────────────────────

    public function testTimeRecordListReturnsArray(): void
    {
        $result = $this->callApi(fn() => $this->client->timeRecords()->list());
        $this->assertIsArray($result);
    }

    public function testTimeRecordListItemsAreTimeRecordDTOs(): void
    {
        $result = $this->callApi(fn() => $this->client->timeRecords()->list());
        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(TimeRecordDTO::class, $item);
        }
    }
}
